<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 13/01/2018
 * Time: 18:21
 */

namespace App\Model\Repository;


class MarkerRepository extends AbstractRepository
{
    protected $table = 'marker';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function find($columns = [], $where = '', $limit = '', $orderBy = '', $groupBy = '')
    {
        $select = count($columns) > 0 ? implode(',', $columns) : "*";

        $queryBuilder = $this->databaseService->getQueryBuilder();
        $queryBuilder->select($select);
        $queryBuilder->from($this->table, 'mark');
        $queryBuilder->join('mark', 'user', 'user', 'mark.user_id = user.id');
        $queryBuilder->join('mark', 'marker_type', 'type', 'mark.marker_type_id = type.id');
        $queryBuilder->where($where);

        if (!empty($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        return $this->databaseService->executeSelect();
    }

    public function findOne($columns = [], $where = '', $limit = '', $orderBy = '', $groupBy = '')
    {
        $type = $this->find($columns, $where, $limit, $orderBy, $groupBy);
        return isset($type[0]) ? $type[0] : [];
    }
}