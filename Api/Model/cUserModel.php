<?php
namespace Alexwebgr\SocialGraph\Api\Model;

/**
 * Class cUserModel
 * @package Alexwebgr\SocialGraph\Api\Model
 */
class cUserModel extends \Alexwebgr\SocialGraph\Api\Model\cModel
{
    /**
     * @desc get one user model by any field available in the user table
     * it will always return one model
     *
     * @return mixed
     */
    public function getUser()
    {
        $this
            ->select()
            ->from("user")
            ->where($this->field, $this->value)
            ->prepare()
            ->execute()
        ;

        return $this->fetch();
    }

    /**
     * @desc get all users from the table
     *
     * @return mixed
     */
    public function getUsers()
    {
        $this
            ->select()
            ->from("user")
            ->prepare()
            ->execute()
        ;

        return $this->fetchAll();
    }

    /**
     * @desc get a friend for a specified user this function is used internally to check if a user-friend relation
     * was already added to the has table
     *
     * @param array $array
     * @return mixed
     */
    private function getFriend($array = array())
    {
        $this
            ->select()
            ->from("user_has_friends")
            ->where($array[0]["key"], $array[0]["value"])
            ->andWhere($array[1]["key"], $array[1]["value"])
            ->prepare()
            ->execute()
        ;

        return $this->fetchAll();
    }

    /**
     * @desc get all friends from the table
     *
     * @return mixed
     */
    private function getAllFriends()
    {
        $this
            ->select()
            ->from("user_has_friends")
            ->prepare()
            ->execute()
        ;

        return $this->fetchAll();
    }

    /**
     * @desc get all friends for a specified user
     *
     * @return mixed
     */
    public function getFriends()
    {
        $this
            ->select()
            ->from("user_has_friends")
            ->where($this->field, $this->value)
            ->prepare()
            ->execute()
        ;

        $result = $this->fetchAll();

        $Friends["children"] = $result;
        $Friends["count"] = count($result);

        return $Friends;
    }

    /**
     * @desc get suggested friends basically retrieves the friends of the friends
     *
     * @return array
     */
    public function getSuggestedFriends()
    {
        $aFriends = array();
        $Friends = $this->getFriends();

        if(!empty($Friends))
            foreach($Friends["children"] as $Friend)
            {
                $this->field = "id";
                $this->value = $Friend["friend_id"];

                $SuggestedFriends = $this->getFriends();

                if(!empty($SuggestedFriends))
                    foreach($SuggestedFriends["children"] as $SuggestedFriend)
                    {
                        //don't add the same person
                        if($SuggestedFriend["friend_id"] != $Friend["id"])
                        {
                            // make sure they are not already friends
                            $aFields[0]["key"] = "id";
                            $aFields[0]["value"] = $Friend["id"];
                            $aFields[1]["key"] = "friend_id";
                            $aFields[1]["value"] = $SuggestedFriend["friend_id"];

                            $SingleFriend = $this->getFriend($aFields);

                            if(empty($SingleFriend))
                            {
                                //overwrite existing relations
                                $aFriends[$SuggestedFriend["friend_id"]] = $SuggestedFriend;
                            }
                        }
                    }
            }

        $array["children"] = $aFriends;
        $array["count"] = count($aFriends);

        return $array;
    }

    /**
     * @desc parse the json file create user models in the db
     *
     * @return array
     */
    public function insertUsers()
    {
        $aSuccess = array();
        $json = json_decode(file_get_contents("data.json"), true);

        foreach($json as $key => $item)
        {
            $this->field = "id";
            $this->value = $item["id"];

            $User = $this->getUser();

            if(empty($User))
            {
                $this
                    ->insert()
                    ->into("user")
                    ->setFields($json[0])
                    ->setValues(count($json[0])-1)
                    ->prepare()
                ;

                $this->setParams($item);
                $aSuccess[$key]["success"] = $this->execute();
                $aSuccess[$key]["id"] = $item["id"];
            }
        }

        return $aSuccess;
    }

    /**
     * @desc create the _has_ relations for users and friends in the db
     *
     * @return array
     */
    public function insertFriends()
    {
        $aSuccess = array();
        $json = json_decode(file_get_contents("data.json"), true);

        foreach($json as $key => $item)
        {
            $this->field = "id";
            $this->value = $item["id"];

            $User = $this->getUser();

            if(!empty($User))
            {
                foreach($item["friends"] as $keyI => $friend)
                {
                    $array[0]["key"] = "id";
                    $array[0]["value"] = $User["id"];
                    $array[1]["key"] = "friend_id";
                    $array[1]["value"] = $friend;

                    $Friend = $this->getFriend($array);

                    if(empty($Friend))
                    {
                        $this
                            ->insert()
                            ->into("user_has_friends")
                            ->setFields(array("id", "friend_id"), false)
                            ->setValues(2)
                            ->prepare()
                        ;

                        $this->setParams(array($User["id"], $friend));

                        $aSuccess[$key][$keyI]["success"] = $this->execute();
                        $aSuccess[$key][$keyI]["id"] = $friend;
                    }
                }
            }
        }

        return $aSuccess;
    }

    /**
     * @desc create a tree structured array that contains users and their friends as child nodes
     *
     * @return mixed
     */
    public function getUserTree()
    {
        $Users = $this->getUsers();

        if(!empty($Users))
            foreach($Users as $key => $User)
            {
                $this->field = "id";
                $this->value = $User["id"];

                $Friends = $this->getFriends();

                if(!empty($Friends))
                    foreach($Friends["children"] as $Friend)
                    {
                        $this->field = "id";
                        $this->value = $Friend["friend_id"];

                        $Users[$key]["children"][] = $this->getUser();
                    }
            }

        return $Users;
    }

    /**
     * @desc create an array with two two-dimensional for creating the force layout graph
     * array nodes contains all users and array links contains all the relations between them
     *
     * @return array
     */
    public function getUserList()
    {
        $Tree = array();
        $aFriends = array();
        $Users = $this->getUsers();
        $Friends = $this->getAllFriends();

        if(!empty($Users))
            foreach($Users as $key => $User)
            {
                $this->field = "id";
                $this->value = $User["id"];

                $UserFriends = $this->getFriends();
                $count = $UserFriends["count"];

                $Users[$key]["size"] = ($count + 6) * 4;
            }

        if(!empty($Friends))
            foreach($Friends as $key => $Friend) {
                $aFriends[$key] = array(
                    "source" => (int) $Friend["id"],
                    "target" => (int) $Friend["friend_id"],
                    "bond" => 1
                );
            }

        // pre-pending one element to push all elements one position down since we dont have a user with id = 0
        array_unshift($Users,array("id" => "", "firstName" => "", "surname" => "", "size" => 0));

        $Tree["nodes"] = $Users;
        $Tree["links"] = $aFriends;

        return $Tree;
    }

    /**
     * @param $Friends
     * @desc utility function for getting user models from relations
     *
     * @return array
     */
    public function getUsersFromFriends($Friends)
    {
        $Users = array();
        if(!empty($Friends))
            foreach($Friends as $Friend)
            {
                $this->field = "id";
                $this->value = $Friend["friend_id"];

                $User = $this->getUser();

                if(!empty($User))
                    $Users[] = $User;
            }

        return $Users;
    }
}