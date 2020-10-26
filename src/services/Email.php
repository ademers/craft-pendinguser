<?php
/**
 * Pending User plugin for Craft CMS 3.x
 *
 * Sets new user accounts created via User Registration forms to pending status.
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

    // TODO: Make more DRY
    public function sendAccountCreationEmail(User $user) {
        Craft::$app->getMailer()
            ->composeFromKey('user_registered', ['user' => $user])
            ->setTo($user)
            ->send();
    }

    public function sendAccountModerationEmail(User $user) {
        $settings = PendingUser::$plugin->getSettings();

        Craft::$app->getMailer()
            ->composeFromKey('moderator_notification', ['user' => $user])
            ->setTo($settings->moderatorEmailAddress)
            ->send();
    }

    public function sendAccountActivationEmail(User $user) {
        Craft::$app->getMailer()
            ->composeFromKey('user_activated', ['user' => $user])
            ->setTo($user)
            ->send();
    }
}
