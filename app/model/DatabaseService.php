<?php

namespace App\Model;

use App\Model\Interfaces\DatabaseServiceInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

/**
 * Description of DatabaseService
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 * 
 * Doctrine doc: http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/index.html 
 * Obs: Aconteceu esse erro ao tentar as substituições: An exception occurred while executing 
 * 'SELECT id, name FROM user WHERE id = ?': Parameter 2 to mysqli_stmt::bind_param() 
 * expected to be a reference, value given
 * 
 */
class DatabaseService implements DatabaseServiceInterface
{

    private $databaseName;
    private $driver;
    private $password;
    private $username;
    private $charset;
    private $collation;
    private $prefix;
    private $configuration;
    private $host;
    private $connection = '';
    private $queryBuilder;
    private $errorMessage = '';
    private $lastInsertId;

    public function __construct()
    {
        $this->configure();
    }

    private function configure()
    {
        $this->configuration = new Configuration();
        $this->setDatabaseName(DB_NAME);
        $this->setUserName(DB_USERNAME);
        $this->setDriver(DB_DRIVER);
        $this->setPassword(DB_PASSWORD);
        $this->setCharset(DB_CHARSET);
        $this->setCollation(DB_COLLATION);
        $this->setPrefix(DB_PREFIX);
        $this->setHost(DB_HOST);
    }

    public function getQueryBuilder()
    {
        $this->queryBuilder = $this->getConnection()->createQueryBuilder();
        return $this->queryBuilder;
    }

    public function executeQuery()
    {
        $query = $this->queryBuilder->getSQL();
        return $this->getConnection()->executeQuery($query);
    }

    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setUserName($username)
    {
        $this->username = $username;
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function setCollation($collation)
    {
        $this->collation = $collation;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function getConnection()
    {
        try {
            if (empty($this->connection)) {
                $this->connection = DriverManager::getConnection($this->getParams(), $this->configuration);
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        return $this->connection;
    }

    private function getParams()
    {
        return array(
            'dbname' => $this->databaseName,
            'user' => $this->username,
            'password' => $this->password,
            'host' => $this->host,
            'driver' => $this->driver,
        );
    }

    public function execute($query)
    {
        $stmt = $this->getConnection()->prepare($query);
        return $stmt->execute();
    }

    public function select($query)
    {
        return $this->getConnection()->fetchAll($query);
    }

    /**
     * 
     * @param String $table
     * @param Array $columns
     * @param String $where
     * @return Array
     */
    public function selectAll($table, $columns = [], $where = '', $limit = '', $orderBy = '', $groupBy = '')
    {
        $where = !empty($where) ? $where : "1=1";
        $columns = count($columns) > 0 ? implode(",", $columns) : '*';
        $limit = !empty($limit) ? " LIMIT {$limit} " : '';
        $orderBy = !empty($orderBy) ? " ORDER BY {$orderBy} " : '';
        $groupBy = !empty($groupBy) ? " GROUP BY {$groupBy} " : '';

        $query = "SELECT {$columns} FROM {$table} WHERE {$where} {$groupBy} {$orderBy} {$limit}";
        return $this->getConnection()->fetchAll($query);
    }

//    public function selectAll($table, $columns = [], $where = '', $limit = '')
//    {
//        $where = !empty($where) ? $where : "1=1";
//        $columns = count($columns) > 0 ? implode(",", $columns) : '*';
//        $limit = !empty($limit) ? " LIMIT {$limit} " : '';
//        
//        $query = "SELECT {$columns} FROM {$table} WHERE {$where} {$limit}";
//        return $this->getConnection()->fetchAll($query);
//    }

    public function executeSelect()
    {
        $query = $this->queryBuilder->getSql();
        return $this->getConnection()->fetchAll($query);
    }

    public function delete($table, $column, $value)
    {
        //$conn->delete('user', array('id' => 1));
        return $this->getConnection()->delete($table, array($column => $value));
    }

    /**
     * 
     * @param string $table
     * @param array $where
     * @return type
     */
    public function delete2($table, $where)
    {
        return $this->getConnection()->delete($table, $where);
    }

    public function insert($table, array $data)
    {
        $data = $this->stripNoTableCol($table, $data);
        //$conn->insert('user', array('username' => 'jwage'));
        $insert = $this->getConnection()->insert($table, $data);
        $this->lastInsertId = $this->getConnection()->lastInsertId();
        return $insert;
    }

    public function update($table, array $data, array $where)
    {
        $data['updated_at'] = $data['updatedAt'] = date('Y-m-d h:i:s');
        $data = $this->stripNoTableCol($table, $data);

        //$conn->update('user', array('username' => 'jwage'), array('id' => 1));  
        return $this->getConnection()->update($table, $data, $where);
    }

    public function testConnection()
    {
        $conn = $this->getConnection();
        try {
            $conn->connect();
        } catch (\Doctrine\DBAL\Exception\ConnectionException $exc) {
            $this->errorMessage = $exc->getMessage();
        }

        return $conn->isConnected();
    }

    private function stripNoTableCol($table, $data)
    {
        $columns = $this->getColumnNames($table);
        foreach ($data as $key => $value) {
            if (array_search($key, $columns) === false) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private function getColumnNames($table)
    {
        $query = "DESCRIBE {$table}";
        $st = $this->getConnection()->executeQuery($query);
        $columns = array();
        foreach ($st->fetchAll() as $key => $value) {
            array_push($columns, $value['Field']);
        }
        return $columns;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

}
