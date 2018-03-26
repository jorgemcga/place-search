<?php

/**
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model\Repository;

use App\Model\LogService;
use App\Model\DatabaseService;

abstract class AbstractRepository
{

    protected $databaseService;
    protected $table = '';
    protected $primaryKey = 'id';
    protected $errorMessage = '';
    protected $lastInsertUpdateId;
    protected $logService;
    protected $columnUpdatedAt = 'updated_at';
    
    public function __construct()
    {
        $this->databaseService = new DatabaseService();
        $this->logService = new LogService();
    }

    public function get($id = 0, array $columns = [], $limit = 0)
    {
        $id = (int)$id;
        $select = count($columns) > 0 ? implode(',', $columns) : "*";
        $where = $id > 0 ? "{$this->primaryKey} = '{$id}'" : "1";
        
        $queryBuilder = $this->databaseService->getQueryBuilder();
        $queryBuilder->select($select);
        $queryBuilder->from($this->table);
        $queryBuilder->where($where);

        if ($limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        return $this->databaseService->executeSelect();
    }
    
    public function find($columns = [], $where = '', $limit = '', $orderBy = '', $groupBy = '')
    {
        return $this->databaseService->selectAll($this->table, $columns, $where, $limit, $orderBy, $groupBy);
    }

    public function getOne($id = 0, array $columns = [])
    {
        $id = (int)$id;
        $collection = $this->get($id, $columns, 1);
        return isset($collection[0]) ? $collection[0] : [];
    }

    public function write($data)
    {
        if ($data[$this->primaryKey] == 0) {
            $this->insert($data);
        } else {
            $where = [$this->primaryKey => $data[$this->primaryKey]];
            $this->update($data, $where);
        }
        return $this;
    }

    public function insert($data)
    {
        $this->databaseService->insert($this->table, $data);
        $lastInsertUpdateId = (int) $this->databaseService->getLastInsertId();
        if ($lastInsertUpdateId) {
            $this->setLastInsertUpdateId($lastInsertUpdateId);
            return true;
        }
        return false;
    }

    public function update($data, $where)
    {
        $data[$this->columnUpdatedAt] = date('Y-m-d h:i:s');
        if ($this->databaseService->update($this->table, $data, $where)) {
            $this->setLastInsertUpdateId($data[$this->primaryKey]);
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        return $this->databaseService->delete($this->table, $this->primaryKey, $id);
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getLastInsertUpdateId()
    {
        return $this->lastInsertUpdateId;
    }

    public function setLastInsertUpdateId($lastInsertUpdateId)
    {
        $this->lastInsertUpdateId = $lastInsertUpdateId;
        return $this;
    }

}
