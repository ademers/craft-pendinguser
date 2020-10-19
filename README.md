# Pending User plugin for Craft CMS 3.5.x

A Craft Plugin that enables user account moderation by setting all new user accounts created via a front-end [User Registration form](https://craftcms.com/docs/3.x/dev/examples/user-registration-form.html) to *Pending* status.

This plugin is a port of the Craft 2 Pending User plugin originally created by [Trevor Davis](https://github.com/davist11).

<!-- ![Screenshot](resources/img/plugin-logo.png) -->

## Requirements

This plugin requires [Craft CMS Pro](https://craftcms.com/pricing) 3.5.x or later.

Access to a Craft [Admin](https://craftcms.com/docs/3.x/user-management.html#admin-accounts) user account, or a non-Admin account with the [*Administrate users*](https://craftcms.com/docs/3.x/user-management.html#permissions) permission enabled.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require ademers/pendinguser

3. In the Control Panel, go to Settings → Plugins and click the *Install* button for Pending User.

## Pending User Overview

1. When a user creates a new account by registering via a front-end [User Registration form](https://craftcms.com/docs/3.x/dev/examples/user-registration-form.html) and their email isn't listed in the Allowed Domains setting field, the user account is set to *Pending* status.
2. The user receives an email stating that their new account has been created.
3. Optionally, a moderator receives an email stating that a new user account has been created.
4. Once the account has been approved and activated by a moderator, the user receives an email stating that their account has been approved and activated.

## Configuring Pending User

### Craft Settings

1. In the Craft Control Panel, go to Settings -> Users -> User Groups.
2. Create a new User Group called *New users*, for example.
3. In the Craft Control Panel, go to Settings -> Users -> Settings.
4. Uncheck *Verify email addresses* to disable it (enabled by default).
5. Check *Allow public registration* to enable it (disabled by default).
6. Uncheck *Suspend users by default* to disabled it (disabled by default).
7. From the *Default User Group* drop-down menu, select *New users*, or the User Group that you created in step 2.

### Pending User Plugin Settings

In the Craft Control Panel, go to Settings -> Plugins -> Pending User.

#### Allowed Domains
If you want users with specific domain names to be automatically approved, enter those domain names with each one on a separate line. Optional.

#### Registration Email

This is the email sent to users when they register via a front-end registration form and create a new user account.

##### Subject

*Default*: User account created

##### Body

You have access to a Twig `{{ user }}` variable, which is a User Element.

*Default*:

Hi `{{ user.username }}`,

Your new user account has been created and is pending moderation. You will receive an email once your account has been approved and activated.

Best Regards,\
Acme Inc.

#### Activation Email

This is the email that is sent to users when their user account is activated (i.e. a moderator changes user account status from *Pending* to *Active*).

##### Subject

*Default*: User account activated

##### Body

*Default*:

Hi `{{ user.username }}`,

Your user account has been activated.

Best Regards,\
Acme Inc.

#### Moderator Notification Email

This is the email that is sent to the moderator when a user registers via a front-end registration form thereby creating a new user account.

##### Moderator Email Notification

If enabled, the moderator will receive an email notification when a new user registers.

*Default*: off

##### Moderator Email Address

The moderator's email address.

*Default*: The site administrator's email address.

##### Subject

*Default*: User account activation request

##### Body

You have access to a Twig `{{ user }}` variable, which is a User Element. Use `{{ user.cpEditUrl }}` to insert a link to the User edit screen in the Craft Control Panel.

*Default*:
```Twig
Hi moderator,

Please review and activate <a href="{{ user.cpEditUrl }}">{{ user.username }}</a> user account.

Best Regards,\
Acme Inc.
```

## Using Pending User

See [Pending User Overview](#pendinguser-overview) above.

## Pending User Roadmap

Some things to do, and ideas for potential features:

- [ ] Release it

Brought to you by [Andrea DeMers](https://andreademers.com)
