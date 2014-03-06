<?php
namespace Alexwebgr\SocialGraph\Core;

/**
 * Class cDatabaseQuery
 * @package Alexwebgr\SocialGraph\Core
 */
class cDatabaseQuery extends cDatabase
{
    /**
     * @var \PDOStatement $PDOStatement
     */
    private $PDOStatement;

    /**
     * @var string $sql
     */
    private $sql;

    /**
     * @var array $aParams
     */
    private $aParams = array();

    protected function __construct()
    {
        $this->connect();
        $this->setDatabase();
    }

    /**
     * @param $file
     * @return $this
     */
    protected function loadSQLFile($file)
    {
        $this->sql = file_get_contents($file);

        return $this;
    }

    /**
     * @param string $select
     * @return $this
     */
    protected function select($select = "*")
    {
        $this->sql = "SELECT " . $select;

        return $this;
    }

    /**
     * @return $this
     */
    protected function insert()
    {
        $this->sql = "INSERT ";

        return $this;
    }

    /**
     * @return $this
     */
    protected function update()
    {
        $this->sql = "UPDATE ";

        return $this;
    }

    /**
     * @param $tableName
     * @return $this
     */
    protected function from($tableName)
    {
        $this->sql .= " FROM " . $tableName;

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    protected function where($field, $value)
    {
        $this->aParams = array();
        $this->sql .= " WHERE " . $field . " = ?";

        $this->aParams[] = $value;

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return $this
     */
    protected function andWhere($field, $value)
    {
        $this->sql .= " AND " . $field . " = ?";

        $this->aParams[] = $value;

        return $this;
    }

    /**
     * @param $tableName
     * @return $this
     */
    protected function into($tableName)
    {
        $this->sql .= " INTO " . $tableName;

        return $this;
    }

    /**
     * @param $tableName
     * @return $this
     */
    protected function set($tableName)
    {
        $this->sql .= $tableName . " SET ";

        return $this;
    }

    /**
     * @param $aData
     * @param bool $isAssoc
     * @return $this
     */
    protected function setFields($aData, $isAssoc = true)
    {
        $fields = "";

        foreach($aData as $key => $item)
        {
            if($isAssoc)
            {
                if(!is_array($item))
                    $fields .= $key . ",";
            }
            else
            {
                $fields .= $item . ",";
            }
        }

        $fields = substr($fields, 0, -1);
        //var_dump($fields);
        $this->sql .= " (" . $fields . ") ";

        return $this;
    }

    /**
     * @param int $num
     * @return $this
     */
    protected function setValues($num = 0)
    {
        $qs = "";

        for($i = 0; $i < $num; $i++)
            $qs .= "?,";

        $qs = substr($qs, 0, -1);

        //var_dump($qs);
        $this->sql .= " VALUES (" . $qs . ")";

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    protected function setParams($params = array())
    {
        $this->aParams = array();

        foreach($params as $param)
            if(!is_array($param))
                $this->aParams[] = $param;

        return $this;
    }

    /**
     * @return $this
     */
    protected function prepare()
    {
        //var_dump($this->sql);
        $this->PDOStatement = $this->PDO->prepare($this->sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));

        return $this;
    }

    /**
     * @return bool
     */
    protected function execute()
    {
        //var_dump($this->aParams);
        return $this->PDOStatement->execute($this->aParams);
    }

    /**
     * @return mixed
     */
    protected function fetch()
    {
        return $this->PDOStatement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    protected function fetchAll()
    {
        return $this->PDOStatement->fetchAll(\PDO::FETCH_ASSOC);
    }
}