<?php
/**
 * Pending User plugin for Craft CMS 3.x
 *
 * A Craft Plugin that sets all new user accounts created via a front-end registration form to pending status.
 *
 * @link      https://andreademers.com
 * @copyright Copyright (c) 2020 Andrea DeMers
 */

namespace ademers\pendinguser\services;

use ademers\pendinguser\PendingUser;

use Craft;
use craft\elements\User;
use yii\base\Component;
//use yii\base\Model;

/**
 * Email Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Andrea DeMers
 * @package   PendingUser
 * @since     2.0.0
 */
class Email extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin file, call it like this:
     *
     *     PendingUser::$plugin->email->exampleService()
     *
     * @param $user
     * @return mixed
     */
    /* public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (PendingUser::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    } */

//    public $settings;
//    public $systemEmailAddress;
//    public $mailer;

//    public function __construct() {
//        $this->settings = PendingUser::$plugin->getSettings();
//        $this->systemEmailAddress = \craft\helpers\App::mailSettings()->fromEmail;
//        $this->mailer = Craft::$app->getMailer();
//    }

    public function sendAccountCreationEmail(User $user) {
        // Get plugin settings
        // $settings = PendingUser::$plugin->getSettings();
        // Get system email address
        // $systemEmailAddress = \craft\helpers\App::mailSettings()->fromEmail;
        // Create the parameters for the email message
        // $params = [
        //     'user' => $user,
        // ];
        // Create message
        // $message = Craft::$app->getView()->renderString($this->settings->registrationEmailBody, $params);
//        $message = Craft::$app->getView()->renderString($this->settings->registrationEmailBody, ['user' => $user]);
//        $message = Craft::$app->systemMessages->getMessage('user_registered');
        // Create mailer
//         $mailer = Craft::$app->getMailer();
//        $this->mailer->compose()ß
//            ->setFrom($this->systemEmailAddress)
//            ->setTo($user->email)
//            ->setSubject($this->settings->registrationEmailSubject)
//            ->setHtmlBody(nl2br($message))
//            ->send();

//        self::send($user,'user_registered');
//        return true;

        Craft::$app->getMailer()
            ->composeFromKey('user_registered', ['user' => $user])
            ->setTo($user)
            ->send();
    }

    public function sendAccountModerationEmail(User $user) {
        // Get plugin settings
         $settings = PendingUser::$plugin->getSettings();
        // Get system email address
        // $systemEmailAddress = \craft\helpers\App::mailSettings()->fromEmail;
        // Create the parameters for the email message
/*        $params = [
            'user' => $user,
        ];
        // Create message
        $message = Craft::$app->getView()->renderString($this->settings->moderatorEmailBody, $params);
        // Create mailer
        $mailer = Craft::$app->getMailer();
        $mailer->compose()
            ->setFrom($this->systemEmailAddress)
            ->setTo($this->settings->moderatorEmailAddress)
            ->setSubject($this->settings->moderatorEmailSubject)
            ->setHtmlBody(nl2br($message))
            ->send();*/

        Craft::$app->getMailer()
            ->composeFromKey('moderator_notification', ['user' => $user])
            ->setTo($settings->moderatorEmailAddress)
            ->send();

    }

    public function sendAccountActivationEmail(User $user) {
        // Get plugin settings
        // $settings = PendingUser::$plugin->getSettings();
        // Get system email address
        // $systemEmailAddress = \craft\helpers\App::mailSettings()->fromEmail;

        // Create the parameters for the email message
/*        $params = [
            'user' => $user,
        ];
        // Create message
        $message = Craft::$app->getView()->renderString($this->settings->activationEmailBody, $params);
        // Create mailer
        $mailer = Craft::$app->getMailer();
        $mailer->compose()
            ->setFrom($this->systemEmailAddress)
            ->setTo($user->email)
            ->setSubject($this->settings->activationEmailSubject)
            ->setHtmlBody(nl2br($message))
            ->send();*/

        Craft::$app->getMailer()
            ->composeFromKey('user_activated', ['user' => $user])
            ->setTo($user)
            ->send();
    }

/*    public function send(User $user, $message)
    {
        Craft::$app->getMailer()
        ->composeFromKey($message)
        ->setTo($user)
        ->send();
        return true;
    }*/
}
