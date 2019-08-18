<?php
namespace Craft;

class PendingUserPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Pending User');
    }

    public function getDescription()
    {
        return 'Simple user moderation';
    }

    public function getVersion()
    {
        return '1.0.1';
    }

    public function getDeveloper()
    {
        return 'Trevor Davis';
    }

    public function getDeveloperUrl()
    {
        return 'http://viget.com';
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/vigetlabs/craft-pendinguser';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/vigetlabs/craft-pendinguser/master/releases.json';
    }

    protected function defineSettings()
    {
        return array(
            'allowedDomains' => array(AttributeType::Mixed, 'default' => null),
            'signupEmailSubject' => array(AttributeType::Mixed, 'default' => 'User Request Received'),
            'signupEmailBody' => array(AttributeType::Mixed, 'default' => 'We received your request for a members account and we are currently reviewing it.  You will receive a verification email once the account has been approved.'),
            'activationEmailSubject' => array(AttributeType::Mixed, 'default' => 'Account Activated'),
            'activationEmailBody' => array(AttributeType::Mixed, 'default' => 'Your account has been activated.'),
            'notifyModerator' => array(AttributeType::Bool, 'default' => false),
            'moderatorEmailAddress' => array(AttributeType::Email, 'default' => craft()->systemSettings->getSetting('email', 'emailAddress')),
            'moderatorEmailSubject' => array(AttributeType::Mixed, 'default' => 'A new user has signed up'),
            'moderatorEmailBody' => array(AttributeType::Mixed, 'default' => "A new user has signed up.\nClick here to review: {{ user.cpEditUrl }}"),
        );
    }

    public function getSettingsHtml()
    {
        if (craft()->request->getPath() === 'settings/plugins')
        {
            return true;
        }

        return craft()->templates->render('pendinguser/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    public function init()
    {
        craft()->on('users.onBeforeSaveUser', function(Event $event) {
            $settings = $this->getSettings();
            $user = $event->params['user'];
            $isNewUser = $event->params['isNewUser'];

            if ($isNewUser && !$this->isAllowedDomain($user->email))
            {
                $user->pending = true;
                craft()->pendingUser_email->signup($user);
            }
        });

        craft()->on('users.onSaveUser', function(Event $event) {
            $settings = $this->getSettings();
            $user = $event->params['user'];
            $isNewUser = $event->params['isNewUser'];

            if ($settings['notifyModerator'] && $isNewUser && !$this->isAllowedDomain($user->email))
            {
                craft()->pendingUser_email->notifyModerator($user);
            }
        });

        craft()->on('users.onActivateUser', function(Event $event) {
            $user = $event->params['user'];
            craft()->pendingUser_email->activate($user);
        });

        craft()->on('users.onBeforeActivateUser', function (Event $event) {
            // stop the user from being able to activate their account. Forces admins or users with sufficient privileges to do so.
            $loggedInUser = craft()->userSession->getUser();
            if (!$loggedInUser || (!craft()->userSession->isAdmin() && !craft()->userPermissions->doesUserHavePermission($loggedInUser->id, 'registerUsers'))) {
                $event->performAction = false;
            }
        });
    }

    private function isAllowedDomain($domain)
    {
        $settings = $this->getSettings();
        $allowedDomains = array_filter(explode("\r\n", $settings->allowedDomains));
        $emailDomain = strtolower(substr(strrchr($domain, '@'), 1));
        return in_array($emailDomain, $allowedDomains);
    }
}
