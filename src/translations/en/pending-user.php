<?php
/**
 * Pending User plugin for Craft CMS 3.x
 *
 * A Craft Plugin that sets all new user accounts created via a front-end registration form to pending status.
 *
 * @link      https://andreademers.com
 * @copyright Copyright (c) 2020 Andrea DeMers
 */

/**
 * Pending User en Translation
 *
 * Returns an array with the string to be translated (as passed to `Craft::t('pendinguser', '...')`) as
 * the key, and the translation as the value.
 *
 * http://www.yiiframework.com/doc-2.0/guide-tutorial-i18n.html
 *
 * @author    Andrea DeMers
 * @package   PendingUser
 * @since     2.0.0
 */
return [
    'Pending User plugin installed' => 'Pending User plugin installed',
    'Pending User plugin loaded' => 'Pending User plugin loaded',

    // Messages
    // User registers and thereby creates a new user account
    'user_registered_heading' => 'Pending User: when a new user registers',
    'user_registered_subject' => 'User account created',
    'user_registered_body' => "Hi {{user.friendlyName}},\n\n" .
        "Your {{systemName}} user account {{user.username}} has been created and is pending activation.\n\n" .
        "You'll receive another email once we've reviewed and activated your account.\n\n",
    // Moderator email notification
    'moderator_notification_heading' => 'Pending User: when the Moderator Email Notification setting is enabled',
    'moderator_notification_subject' => 'User account activation request',
    'moderator_notification_body' => "Hi Moderator,\n\n" .
        "Please visit {{user.cpEditUrl}} to review and activate the {{user.username}} user account.\n\n",
    // User account activated by moderator
    'user_activated_heading' => 'Pending User: when a user account is activated',
    'user_activated_subject' => 'User account activated',
    'user_activated_body' => "Hi {{user.friendlyName}},\n\n" .
        "Your {{systemName}} user account {{user.username}} has been activated.\n\n"
];
