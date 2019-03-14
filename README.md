# Pending User

Craft plugin for simple user moderation. This plugin does the following:

## Update

The original developer of this plugin recently transferred this repository to me. I plan to port the plugin to Craft 3 and make it available in the Craft CMS Plugin Store for free.

## When a user signs up:

1. The user is set in a pending state if their email address isn't one of the approved domains.
1. The user receives an email saying that their account request has been received.
1. Optional: A moderator gets a notification email about the new signup.

## When an admin activates the user:

1. The user receives an email saying that their account has been activated.

## Installation

1. Copy the `pendinguser` folder to `craft/plugins`.
1. Navigate to the plugins page in the Craft control panel and install **Pending User**.
1. Navigate to the plugin settings to customize emails and domains.

## Settings

### Allowed Domains

If you want users with specific domain names to be automatically approved, enter those domain names with each one on a separate line.

### User Signup Email

This is the email that is sent to a user when they sign up.

#### User Signup Email Subject

*Default:* User Request Received

#### User Signup Email Body

You have access to a Twig `{{ user }}` variable, which is a <a href="http://buildwithcraft.com/docs/templating/usermodel">UserModel</a>.

*Default:* We received your request for a members account and we are currently reviewing it.  You will receive a verification email once the account has been approved.

### User Activation Email

This is the email that is sent to a user when they are activated.

#### User Activation Email Subject

*Default:* Account Activated

#### User Activation Email Body

You have access to a Twig `{{ user }}` variable, which is a <a href="http://buildwithcraft.com/docs/templating/usermodel">UserModel</a>.

*Default:* Your account has been activated.

### Moderator Notification Email

This is the email the moderator will get whenever a new user signs up.

#### Enable Moderator Email Notification

If this is enabled, the moderator will get an email whenever a new user signs up.

*Default:* Off

#### Moderator Email Address

The email address the notification will be sent to.

*Default:* The site administrator's email address.

#### Moderator Notification Email Subject

*Default:* A new user has signed up

#### Moderator Notification Email Body

You have access to a Twig `{{ user }}` variable, which is a <a href="http://buildwithcraft.com/docs/templating/usermodel">UserModel</a>. Use `{{ user.cpEditUrl }}` to insert a link to the user edit screen.

*Default:* A new user has signed up. Click here to review: `{{ user.cpEditUrl }}`

<hr>

**Visit [code.viget.com](http://code.viget.com/) to see more projects from [Viget](http://viget.com).**
