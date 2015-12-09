<?php
namespace Craft;

class PendingUser_EmailService extends BaseApplicationComponent
{
    public $email;
    public $emailSettings;
    public $pluginSettings;

    public function __construct()
    {
        $this->email = new EmailModel();
        $this->emailSettings = craft()->email->getSettings();

        $this->email->fromEmail = $this->emailSettings['emailAddress'];
        $this->email->sender = $this->emailSettings['emailAddress'];
        $this->email->fromName = $this->emailSettings['senderName'];

        $this->pluginSettings = craft()->plugins->getPlugin('pendinguser')->getSettings();
    }

    public function signup(UserModel $user)
    {
        $this->email->toEmail = $user->email;
        $this->email->subject = $this->pluginSettings['signupEmailSubject'];
        $this->email->body = craft()->templates->renderString($this->pluginSettings['signupEmailBody'], array(
            'user' => $user,
        ));

        craft()->email->sendEmail($this->email);
    }

    public function activate(UserModel $user)
    {
        $this->email->toEmail = $user->email;
        $this->email->subject = $this->pluginSettings['activationEmailSubject'];
        $this->email->body = craft()->templates->renderString($this->pluginSettings['activationEmailBody'], array(
            'user' => $user,
        ));

        craft()->email->sendEmail($this->email);
    }
}
