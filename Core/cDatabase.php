<?php
namespace Alexwebgr\SocialGraph\Core;

/**
 * Class cDatabase
 * @package Alexwebgr\SocialGraph\Core
 */
class cDatabase
{
    /**
     * @var \PDO $PDO
     */
    protected $PDO;

    /**
     * @var bool $dbError
     */
    private $dbError = false;

    /**
     * @var string $host
     */
    private $host = null;

    /**
     * @var string $dbName
     */
    private $dbName = null;

    /**
     * @var string $dbUser
     */
    private $dbUser = null;

    /**
     * @var string $dbPass
     */
    private $dbPass = null;

    public function connect()
    {
        try
        {
            $this->setDBCreds();

            $dsn = "mysql:host=" . $this->host;
            $this->PDO = new \PDO($dsn, $this->dbUser, $this->dbPass);
        }
        catch (\PDOException $e)
        {
            $this->dbError = array(
                "errorCode" => $e->getCode(),
                "message" => "Connection failed: " . $e->getMessage(),
                "hasError" => true
            );
        }

        return $this;
    }

    public function setDatabase()
    {
        $this->setDbName();
        $this->PDO->query("use " . $this->dbName);

        return $this;
    }

    /**
     * @return bool
     */
    public function getDBError()
    {
        return $this->dbError;
    }

    private function setDBCreds()
    {
        $webHost = $_SERVER["HTTP_HOST"];

        switch($webHost)
        {
            case "localhost":
                $this->host = "localhost";
                $this->dbUser = "root";
                $this->dbPass = "";
                break;

            case "alex-web.gr":
                $this->host = "localhost";
                $this->dbUser = "alexwebg_alex";
                $this->dbPass = "QixhWimiUtDH";
                break;
        }

        return $this;
    }

    private function setDbName()
    {
        $webHost = $_SERVER["HTTP_HOST"];

        switch($webHost)
        {
            case "localhost":
                $this->dbName = "social_graph";
                break;

            case "alex-web.gr":
                $this->dbName = "alexwebg_socialGraph";
                break;
        }

        return $this;
    }
}