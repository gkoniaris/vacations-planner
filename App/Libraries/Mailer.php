<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Mailer allows email sending through an SMTP server
 * 
 * It uses the fluent interface design pattern, so you can build
 * emails by chaining functions and when finished building the email,
 * you just call the send function
 * 
 * Example usage:
 * 
 * $mailer = new Mailer();
 * $mailer->from('myemail@test.com')
 *        ->to('anotheremail@test.com')
 *        ->subject('Sending report')
 *        ->text('Please check the report I sent you')
 *        ->send();
 */
class Mailer {

    protected $mail;
    protected $mergeTags;
    
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mergeTags = [];
        $this->setSmtpConfiguration();
    }

    /**
     * Inject smtp settings for mailer
     */
    private function setSmtpConfiguration()
    {
        global $settings;

        $this->mail->SMTPDebug = 0;
        $this->mail->isSMTP();
        $this->mail->Host = $settings['MAIL']['HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $settings['MAIL']['USERNAME'];
        $this->mail->Password = $settings['MAIL']['PASSWORD'];
        $this->mail->SMTPSecure = "tls";
        $this->mail->Port = $settings['MAIL']['PORT'];
    }

    /**
     * Sets the email sender
     * 
     * @param $email
     * @param $name
     */
    public function from($email, $name)
    {
        if (!$email || !$name) throw new \Exception('Please provide a valid email and name for the sender');

        $this->mail->From = $email;
        $this->mail->FromName = $name;

        return $this;
    }

    /**
     * Sets an email recipient. Can be called multiple times
     * 
     * @param $email
     * @param $name
     */
    public function to($email, $name)
    {
        if (!$email) throw new \Exception('Please provide a valid email and name for the recipient');
        
        $this->mail->addAddress($email, $name);

        return $this;
    }

    /**
     * Sets the email subject
     * 
     * @param $text
     */
    public function subject($text)
    {
        $this->mail->Subject = $text;

        return $this;
    }

    /**
     * Sets a list of merge tags that will be replaced in the
     * original content and the alternative content of the email.
     * mergeTags function should be called before the view, text or
     * altText functions.
     * 
     * @param $tags
     */
    public function mergeTags($tags)
    {
        $this->mergeTags = $tags;

        return $this;
    }

    /**
     * Sets the view that will be used for the email body.
     * Pass the view in the following format: 'mail.reports.report1'
     * where mail/reports/report1.php is a file located inside
     * the views folder
     * 
     * @params $view
     */
    public function view($view)
    {
        $this->mail->isHTML(true);

        $content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/views/' . str_replace('.', '/', $view) . '.php', 'r');

        foreach ($this->mergeTags as $key => $mergeTag) {
            $content = str_replace('{' . $key . '}', $mergeTag, $content);
        }

        $this->mail->Body = $content;

        return $this;
    }

    /**
     * Sets the email body when using plain text
     * 
     * @params $text
     */
    public function text($text)
    {
        $this->mail->isHTML(false);

        foreach ($this->mergeTags as $key => $mergeTag) {
            $text = str_replace('{' . $key . '}', $mergeTag, $text);
        }

        $this->mail->Body = $text;

        return $this;
    }

    /**
     * Sets an alternative body for the email when using a view
     * 
     * @params $text
     */
    public function altText($text)
    {
        $this->mail->AltBody = $text;

        return $this;
    }

    /**
     * Sends the email
     */
    public function send()
    {
        try {
            $this->mail->send();
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}