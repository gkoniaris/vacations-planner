<?php

namespace App\Commands\RegisterUser;

use App\Core\Commands\BaseCommand;
use App\Core\Singletons\Database;
use App\Services\UserService;
use App\Services\CompanyService;
use App\Classes\Helpers;
use App\Libraries\Mailer;

class SendRegistrationEmailCommand
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function execute(Mailer $mailer)
    {
        $user = $this->data->user;

        $mailer->subject('Registration successfull')
             ->from('no-reply@test.com', 'Test mail server')
             ->to($user->email, $user->first_name . ' ' . $user->last_name)
             ->mergeTags([
                 'first_name' => $user->first_name,
                 'last_name' => $user->last_name
             ])
             ->view('mail.auth.registered')
             ->send();
    }
}