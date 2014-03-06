<?php
namespace Alexwebgr\SocialGraph\Api\Controller;

/**
 * Class cUserController
 * @package Alexwebgr\SocialGraph\Api\Controller
 */
class cUserController extends cController
{
    /**
     * @desc this is where the magic happens all incoming API calls are being routed to the appropriate function
     */
    public function router()
    {
        $action = $this->getUrlParam("action");

        switch($action)
        {
            case "getUser":
                $this->getUser();
                break;

            case "getUsers":
                $this->getUsers();
                break;

            case "getDirectFriends":
                $this->getDirectFriends();
                break;

            case "getSuggestedFriends":
                $this->getSuggestedFriends();
                break;

            case "insertUsers":
                $this->insertUsers();
                break;

            case "insertFriends":
                $this->insertFriends();
                break;

            case "getUserTree":
                $this->getUserTree();
                break;

            case "getUserList":
                $this->getUserList();
                break;
        }
    }
    /**
     * functions wrapping Model::functions
     * start
     */
    private function getUser()
    {
        $User = $this->Model->getUser();

        $this->output($User);
    }

    private function getUsers()
    {
        $Users = $this->Model->getUsers();

        $this->output($Users);
    }

    private function getUserList()
    {
        $List = $this->Model->getUserList();

        $this->output($List);
    }

    private function getUserTree()
    {
        $Tree = $this->Model->getUserTree();

        $this->output($Tree);
    }

    private function getDirectFriends()
    {
        $Friends = $this->Model->getFriends();
        $Friends["children"] = $this->Model->getUsersFromFriends($Friends["children"]);

        $this->output($Friends);
    }

    private function getSuggestedFriends()
    {
        $Friends = $this->Model->getSuggestedFriends();
        $Friends["children"] = $this->Model->getUsersFromFriends($Friends["children"]);

        $this->output($Friends);
    }

    private function insertUsers()
    {
        $Users = $this->Model->insertUsers();

        $this->output($Users);
    }

    private function insertFriends()
    {
        $Users = $this->Model->insertFriends();

        $this->output($Users);
    }

    /**
     * functions wrapping Model::functions
     * end
     */
}
