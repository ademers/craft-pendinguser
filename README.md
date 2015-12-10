# Pending User

Craft plugin for simple user moderation. This plugin does the following:

## When a user signs up:

1. The user is set in a pending state if their email address isn't one of the approved domains.
1. The user receives an email saying that their account request has been received.

## When an admin activates the user:

1. The user receives an email saying that their account has been activated.

## Installation

1. Copy the `pendinguser` folder to `craft/plugins`.
1. Navigate to the plugins page in the Craft control panel and install **Pending User**.
1. Navigate to the plugin settings to customize emails and domains.

## Settings

### Allowed Domains

If you want users with specific domain names to be automatically approved, enter those domain names with each one on a separate line.

### Sign Up Email

This is the email that is sent to a user when they sign up.

#### Subject

*Default:* User Request Received

#### Body

You have access to a Twig `{{ user }}` variable, which is a <a href="http://buildwithcraft.com/docs/templating/usermodel">UserModel</a>.

*Default:* We received your request for a members account and we are currently reviewing it.  You will receive a verification email once the account has been approved.

### Activation Email

This is the email that is sent to a user when they are activated.

#### Subject

*Default:* Account Activated

#### Body

You have access to a Twig `{{ user }}` variable, which is a <a href="http://buildwithcraft.com/docs/templating/usermodel">UserModel</a>.

*Default:* Your account has been activated.

<hr>

**Visit [code.viget.com](http://code.viget.com/) to see more projects from [Viget](http://viget.com).**