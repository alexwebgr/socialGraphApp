<?php
namespace Alexwebgr\SocialGraph\Api\Controller;

/**
 * Class cController
 * @package Alexwebgr\SocialGraph\Api\Controller
 */
class cController
{
    /**
     * @var $Model
     */
    protected $Model;

    /**
     * @desc this is function works as a dependency container assigning the right dependencies to the controller
     *
     * @return $this
     */
    public function delegate()
    {
        $Controller = null;
        $ControllerModel = null;
        $field = $this->getUrlParam("field");
        $value = $this->getUrlParam("value");
        $controllerParam = $this->getUrlParam("controller");

        switch($controllerParam)
        {
            case "user":
                $Controller = new \Alexwebgr\SocialGraph\Api\Controller\cUserController();
                $ControllerModel = new \Alexwebgr\SocialGraph\Api\Model\cUserModel();
                break;

            case "db":
                $Controller = new \Alexwebgr\SocialGraph\Api\Controller\cDBController();
                $ControllerModel = new \Alexwebgr\SocialGraph\Api\Model\cDBModel();
                break;
        }

        if(!empty($Controller))
        {
            $Controller->Model = $ControllerModel;
            $Controller->Model->setField($field);
            $Controller->Model->setValue($value);

            $Controller->router();
        }

        return $this;
    }

    /**
     * @param $value
     * @desc get the url params and set the Controller::properties
     *
     * @return null|string
     */
    protected function getUrlParam($value)
    {
        $param = null;

        if(isset($_GET[$value]))
            $param = $_GET[$value];

        return $param;
    }

    /**
     * @param string $str
     * @param bool $debug
     * @desc this would be View or Template class but since there is no real output we just echo the json string
     * or the contents of the incoming array for debugging purposes
     *
     * @return $this
     */
    protected function output($str, $debug = false)
    {
        if($debug)
            var_dump($str);
        else
        {
            echo json_encode($str);
        }

        return $this;
    }
}
