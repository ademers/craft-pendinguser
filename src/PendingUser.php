<?php
/**
 * Pending User plugin for Craft CMS 3.x
 *
 * A Craft Plugin that sets all new user accounts created via a front-end registration form to pending status.
 *
 * @link      https://andreademers.com
 * @copyright Copyright (c) 2020 Andrea DeMers
 */

namespace ademers\pendinguser;

use ademers\pendinguser\services\Email as EmailService;
use ademers\pendinguser\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\events\ModelEvent;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\events\UserEvent;
use craft\services\SystemMessages;
use craft\services\Users;
use craft\elements\User;
use craft\events\RegisterEmailMessagesEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Andrea DeMers
 * @package   PendingUser
 * @since     2.0.0
 *
 * @property  EmailService $email
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class PendingUser extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * PendingUser::$plugin
     *
     * @var PendingUser
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '2.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * PendingUser::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        // Set new users created via front-end registration form to pending status
        Event::on(
            Users::class,
            Users::EVENT_BEFORE_ACTIVATE_USER,
            function (UserEvent $event) {
                /** @var User $user */
                $user = $event->user;

                // Ensure that new user account was created on front-end via registration form
                if (!Craft::$app->request->getIsSiteRequest()) {
                    return;
                }

                if (!$this->isAllowedDomains($user->email)) {
                    // Set user to Pending status
                    $event->isValid = false;
                    // Send email to user that account created
                    $this->email->sendAccountCreationEmail($user);
                } else {
                    // Send email to new user that account activated
                    $this->email->sendAccountActivationEmail($user);
                }
            }
        );

        // Send email to moderator after new user account is created and saved
        // See: https://craftcms.stackexchange.com/questions/26797/user-events-in-craft-3
        Event::on(
            User::class,
            User::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                /** @var User $user */
                $user = $event->sender;

                // Ensure that is a new user account
                if (!$event->isNew) {
                    return;
                }

                // Ensure that Moderator Email Notification is enabled (plugin settings)
                if (!$this->getSettings()->notifyModerator) {
                    return;
                }

                // Ensure that new user account was created on front-end via registration form
                if (!Craft::$app->request->getIsSiteRequest()) {
                    return;
                }

                // Ensure that new user account email is not in Allowed Domains (plugin settings)
                if ($this->isAllowedDomains($user->email)) {
                    return;
                }

                // Send email to moderator stating that a new user account was created via front-end registration form
                $this->email->sendAccountModerationEmail($user);
            }
        );

        // Send email to user when new user account is activated from the Control Panel by and admin
        Event::on(
            Users::class,
            Users::EVENT_AFTER_ACTIVATE_USER,
            function(UserEvent $event) {
                /** @var User $user */
                $user = $event->user;

                // Ensure that request is from Control Panel
                if (!Craft::$app->request->getIsCpRequest()) {
                    return;
                }

                // Ensure that user currently logged into the CP has sufficient permissions
                if (!Craft::$app->getUser()->getIdentity()->can('administrateUsers')) {
                    return;
                }

                // Send email to new user that account activated
                $this->email->sendAccountActivationEmail($user);
            }
        );

        // Email message templates
        Event::on(
            SystemMessages::class,
            SystemMessages::EVENT_REGISTER_MESSAGES,
            function(RegisterEmailMessagesEvent $event) {
                $event->messages[] = [
                    'key' => 'user_registered',
                    'heading' => Craft::t('pending-user', 'user_registered_heading'),
                    'subject' => Craft::t('pending-user', 'user_registered_subject'),
                    'body' => Craft::t('pending-user', 'user_registered_body')
                ];

                $event->messages[] = [
                    'key' => 'moderator_notification',
                    'heading' => Craft::t('pending-user', 'moderator_notification_heading'),
                    'subject' => Craft::t('pending-user', 'moderator_notification_subject'),
                    'body' => Craft::t('pending-user', 'moderator_notification_body')
                ];

                $event->messages[] = [
                    'key' => 'user_activated',
                    'heading' => Craft::t('pending-user', 'user_activated_heading'),
                    'subject' => Craft::t('pending-user', 'user_activated_subject'),
                    'body' => Craft::t('pending-user', 'user_activated_body')
                ];
            }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'pending-user',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Private Methods
    // =========================================================================

    private function isAllowedDomains($domain)
    {
        $allowedDomains = array_filter(explode("\r\n", $this->getSettings()->allowedDomains));
        $emailDomain = strtolower(substr(strrchr($domain, '@'), 1));
        return in_array($emailDomain, $allowedDomains);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'pending-user/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
