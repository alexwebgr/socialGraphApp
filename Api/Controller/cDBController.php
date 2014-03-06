<?php
namespace Alexwebgr\SocialGraph\Api\Controller;

/**
 * Class cDBController
 * @package Alexwebgr\SocialGraph\Api\Controller
 */
class cDBController extends cController
{
    /**
     * @desc this is where the magic happens all incoming API calls are being routed to the appropriate function
     */
    public function router()
    {
        $action = $this->getUrlParam("action");

        switch($action)
        {
            case "createDB":
                $this->createDB();
                break;
        }
    }

    /**
     * functions wrapping Model::functions
     * start
     */
    private function createDB()
    {
        $DB = $this->Model->createDB();

        $this->output($DB);
    }
    /**
     * functions wrapping Model::functions
     * end
     */
}