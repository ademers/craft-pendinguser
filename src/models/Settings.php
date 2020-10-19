<?php
/**
 * Pending User plugin for Craft CMS 3.x
 *
 * A Craft Plugin that sets all new user accounts created via a front-end registration form to pending status.
 *
 * @link      https://andreademers.com
 * @copyright Copyright (c) 2020 Andrea DeMers
 */

namespace ademers\pendinguser\models;

use ademers\pendinguser\PendingUser;

use Craft;
use craft\base\Model;

/**
 * PendingUser Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Andrea DeMers
 * @package   PendingUser
 * @since     2.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */

    /**
     * @var string Allowed domains
     */
    public $allowedDomains;

    /**
     * @var bool Notify moderator?
     */
    public $notifyModerator = false;

    /**
     * @var string Moderator email address
     */
    public $moderatorEmailAddress;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['allowedDomains', 'string'],

            ['moderatorEmailAddress', 'email'],

            ['moderatorEmailAddress', 'default', 'value' => \craft\helpers\App::mailSettings()->fromEmail],

            ['moderatorEmailAddress', 'required', 'when' => function($settings) {
                return $settings->notifyModerator == true;
            }],
        ];
    }
}
