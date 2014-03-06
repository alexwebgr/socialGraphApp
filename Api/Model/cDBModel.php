<?php
namespace Alexwebgr\SocialGraph\Api\Model;

/**
 * Class cDBModel
 * @package Alexwebgr\SocialGraph\Api\Model
 */
class cDBModel extends \Alexwebgr\SocialGraph\Api\Model\cModel
{
    /**
     * @desc create the database and table structure for the app by loading and executing the supplied sql file
     *
     * @return array
     */
    public function createDB()
    {
        $aSuccess = array();
        $this->loadSQLFile("social_graph.sql");
        $this->prepare();

        $aSuccess["success"] = $this->execute();

        if($aSuccess["success"])
            $this->setDatabase();

        return $aSuccess;
    }
}