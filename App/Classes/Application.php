<?php

namespace App\Classes;
use App\Core\Singletons\Database;
use App\Core\Singletons\Request;
use App\Core\Exceptions\FunctionalException;
use App\Libraries\Mailer;
use App\Classes\Helpers;

class Application {

    /**
     * Returns all vacation applications
     */
    public function all()
    {
        $applications = Database::selectAll('SELECT id, 
            `from`, 
            `to`, 
            DATEDIFF(`to`, `from`) + 1 AS days_requested, 
            DATE(created_at) AS created_at, 
            status 
            FROM vacation_applications;');

        return $applications;
    }

    /**
     * Creates a new vacation application
     * 
     * @param $data
     * @param $employee
     */
    public function create($data, $employee)
    {
        global $settings;

        $totalDaysRequested = $this->totalDaysRequested($data->from, $data->to);

        if ($totalDaysRequested < 1) throw new FunctionalException('Please provide valid dates');

        Database::beginTransaction();

        // Check if employee has remaining vacation days
        $remainingDays = $this->remainingDays($employee['id']);

        if ($remainingDays < $totalDaysRequested) {
            Database::rollback();

            throw new FunctionalException('You have no remaining days left');
        }

        // Check if dates overlap
        $datesOverlap = $this->datesOverlap($data->from, $data->to, $employee['id']);
        
        if ($datesOverlap) {
            Database::rollback();

            throw new FunctionalException('Selected dates overlap with other application');
        }
        
        // Insert application, update pait time off remaining days and create approve / decline links
        Database::insert('INSERT INTO vacation_applications(`from`, `to`, reason, employee_id) 
            VALUES(?, ?, ?, ?)', [
                $data->from,
                $data->to,
                $data->reason,
                $employee['id']
            ]);

        Database::update('UPDATE pto 
            SET remaining_days = remaining_days - ? 
            WHERE employee_id = ?', [
                $totalDaysRequested,
                $employee['id']
            ]);

        $application = Database::select('SELECT id, `from`, `to`, reason, created_at FROM vacation_applications ORDER BY id DESC');

        Database::insert('INSERT INTO vacation_application_links(vacation_application_id, `hash`, `type`) 
            VALUES(?, ?, ?)', [
                $application['id'],
                Helpers::randomString(),
                'approve'
            ]);

        Database::insert('INSERT INTO vacation_application_links(vacation_application_id, `hash`, `type`) 
            VALUES(?, ?, ?)', [
                $application['id'],
                Helpers::randomString(),
                'decline'
            ]);

        $approveLink = Database::select('SELECT hash 
            FROM vacation_application_links 
            WHERE `type` = "approve" 
            ORDER BY id DESC');

        $declineLink = Database::select('SELECT hash 
            FROM vacation_application_links 
            WHERE `type` = "decline" 
            ORDER BY id DESC');

        Database::commit();
        
        // Fetch data and send email to supervisor
        $employee = Database::select('SELECT id, email, first_name, last_name 
            FROM users 
            WHERE users.id = ?', [
                $employee['id']
            ]);

        $supervisor = Database::select('SELECT supervisors.email, supervisors.first_name, supervisors.last_name FROM users
            JOIN supervisor_employee ON supervisor_employee.employee_id = users.id
            JOIN users AS supervisors ON supervisor_employee.supervisor_id = supervisors.id
            WHERE users.id = ?', [
                $employee['id']
            ]);

        $data->approveLink = $settings['BACKEND_URL'] . '/api/applications/process?hash=' . $approveLink['hash'];
        $data->declineLink = $settings['BACKEND_URL'] . '/api/applications/process?hash=' . $declineLink['hash'];

        $this->sendVacationRequestEmail($employee, $supervisor, $data);

        return $application;
    }

    /**
     * Processes a vacation application, either by approving it or declining it
     * 
     * @param $hash
     */
    public function process($hash)
    {
        Database::beginTransaction();

        $application = Database::select('SELECT users.id AS supervisor_id, 
            vacation_applications.id AS id, 
            vacation_application_links.type AS supervisor_decision,
            vacation_application_links.hash AS hash, vacation_applications.status AS status, 
            vacation_applications.employee_id AS employee_id,
            vacation_applications.from AS `from`,
            vacation_applications.to AS `to`,
            vacation_applications.created_at
            FROM vacation_applications
            JOIN vacation_application_links ON vacation_application_links.vacation_application_id = vacation_applications.id
            JOIN supervisor_employee ON supervisor_employee.employee_id = vacation_applications.employee_id
            JOIN users ON users.id = supervisor_employee.supervisor_id
            WHERE `hash` = ?', [
                $hash
            ]);

        if ($application['status'] !== 'pending') {
            Database::rollback();

            throw new FunctionalException('Already processed');
        }

        Database::update('UPDATE vacation_applications 
            SET status = ?,
            approved_by_supervisor_id = ?,
            updated_at = NOW()
            WHERE vacation_applications.id = ?', [
                $application['supervisor_decision'] . 'd',
                $application['supervisor_id'],
                $application['id']
            ]);

        if ($application['supervisor_decision'] === 'decline') {
            $totalDaysRequested = $this->totalDaysRequested($application['from'], $application['to']);

            Database::update('UPDATE pto 
                SET remaining_days = remaining_days + ? 
                WHERE employee_id = ?', [
                    $totalDaysRequested,
                    $application['employee_id']
                ]);
        }

        Database::commit();

        $employee = Database::select('SELECT email, first_name, last_name FROM users
            JOIN vacation_applications ON vacation_applications.employee_id = users.id
            WHERE vacation_applications.id = ?', [
                $application['id']
            ]);

        $this->sendProcessEmaiToEmployee($employee, $application, $application['supervisor_decision']);

        return true;
    }

    /**
     * Returns the total days requested by the employee
     * 
     * @param $from
     * @param $to
     */
    private function totalDaysRequested($from, $to)
    {
        $startDate = strtotime($from);
        $endDate = strtotime($to);

        $diffInDays = round(($endDate - $startDate) / (60 * 60 * 24));

        return $diffInDays + 1;
    }

    /**
     * Returns the remaining paid time off of the employee
     * 
     * @param $employeeId
     */
    private function remainingDays($employeeId)
    {
        $pto = Database::select('SELECT * FROM pto WHERE employee_id = ?', [
            $employeeId
        ]);

        return $pto['remaining_days'];
    }

    /**
     * Checks if dates overlap
     * 
     * @param $from
     * @param $to
     * @param $employeeId
     */
    private function datesOverlap($from, $to, $employeeId)
    {
        $datesOverlap = Database::select('SELECT * FROM vacation_applications
            WHERE employee_id = ?
            AND (
                `from` BETWEEN ? AND ? OR 
                `to` BETWEEN ? AND ? OR
                ? BETWEEN `from` AND `to`
            );'
        , [
            $employeeId,
            $from,
            $to,
            $from,
            $to,
            $from
        ]);

        return $datesOverlap != null;
    }

    /**
     * Sends a request to the employee supervisor to approve / decline their vacation request
     * 
     * @param $employee
     * @param $supervisor
     * @param $data 
     */
    private function sendVacationRequestEmail($employee, $supervisor, $data)
    {
        $mail = new Mailer();

        $mail->subject('Vacation request')
             ->from('no-reply@test.com', 'Test mail server')
             ->to($supervisor['email'], $supervisor['first_name'] . ' ' . $supervisor['last_name'])
             ->mergeTags([
                 'first_name' => $employee['first_name'],
                 'last_name' => $employee['last_name'],
                 'vacation_start' => $data->from,
                 'vacation_end' => $data->to,
                 'reason' => $data->reason,
                 'approveLink' => $data->approveLink,
                 'declineLink' => $data->declineLink
             ])
             ->view('mail.vacations.request')
             ->send();
    }

    /**
     * Sends an email informing the employee about the decision of the supervisor
     * either approving or declining their vacation request
     * 
     * @param $employee
     * @param $application
     * @param $status 
     */
    private function sendProcessEmaiToEmployee($employee, $application, $status)
    {
        $date = date_create($application['created_at']);

        $mail = new Mailer();

        $mail->subject('Vacation request ' . $status . 'd')
             ->from('no-reply@test.com', 'Test mail server')
             ->to($employee['email'], $employee['first_name'] . ' ' . $employee['last_name'])
             ->mergeTags([
                 'status' => $status . 'd',
                 'submission_date' =>  date_format($date, 'Y/m/d')
             ])
             ->view('mail.vacations.process')
             ->send();
    }

}