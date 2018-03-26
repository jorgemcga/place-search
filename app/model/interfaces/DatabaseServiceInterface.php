<?php

namespace App\Model\Interfaces;

/**
 * Description of DatabaseInterface
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
interface DatabaseServiceInterface
{

    public function setUserName($username);

    public function setDatabaseName($databaseName);

    public function setPassword($password);

    public function setDriver($driver);

    public function setCollation($collation);

    public function setPrefix($prefix);

    public function setCharset($charset);

    public function execute($query);

    public function select($query);

    /**
     *  Return a insert id
     */
    public function insert($table, array $data);

    public function update($table, array $data, array $where);

    public function delete($table, $column, $value);

    public function getQueryBuilder();

    public function testConnection();

    public function getErrorMessage();

    public function getLastInsertId();

    public function executeQuery();
}
