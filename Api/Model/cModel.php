<?php
namespace Alexwebgr\SocialGraph\Api\Model;

/**
 * Class cModel
 * @package Alexwebgr\SocialGraph\Api\Model
 */
class cModel extends \Alexwebgr\SocialGraph\Core\cDatabaseQuery
{
    /**
     * @var $field
     */
    protected $field;

    /**
     * @var $value
     */
    protected $value;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $field
     * @desc set the field for a db query it has to be set before every query otherwise the previous value will be used
     * originally set by set controller that retrieves it from the url after an API call
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @param $value
     * @desc set the value for a db query it has to be set before every query otherwise the previous value will be used
     * originally set by set controller that retrieves it from the url after an API call
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}