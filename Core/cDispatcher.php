<?php
namespace Alexwebgr\SocialGraph\Core;

/**
 * Class cDispatcher
 * @package Alexwebgr\SocialGraph\Core
 */
class cDispatcher
{
    /**
     * @var $aError
     */
    private $aError;

    /**
     * @param $aError
     * @return $this
     */
    public function setError($aError)
    {
        $this->aError = $aError;

        return $this;
    }

    /**
     * @desc switch incoming error codes and display error message
     * this function is a stub maybe it will be expanded in the future
     *
     * @return $this
     */
    public function render()
    {
        switch($this->aError["errorCode"])
        {
            default:
                $this->display();
                break;
        }

        return $this;
    }

    /**
     * @desc output the error
     */
    private function display()
    {
        echo json_encode($this->aError);
    }
}