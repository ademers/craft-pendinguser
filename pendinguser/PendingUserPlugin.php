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
        return '1.0.0';
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
            'activationEmailBody' => array(AttributeType::Mixed, 'default' => 'Your account has been activated.')
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
            $allowedDomains = array_filter(explode("\r\n", $settings->allowedDomains));
            $user = $event->params['user'];
            $isNewUser = $event->params['isNewUser'];
            $emailDomain = strtolower(substr(strrchr($user->email, '@'), 1));

            if ($isNewUser && !in_array($emailDomain, $allowedDomains))
            {
                $user->pending = true;
                craft()->pendingUser_email->signup($user);
            }
        });

        craft()->on('users.onActivateUser', function(Event $event) {
            $user = $event->params['user'];
            craft()->pendingUser_email->activate($user);
        });
    }
}
