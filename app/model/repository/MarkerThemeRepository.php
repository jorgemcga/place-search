<?php
/**
 * Created by PhpStorm.
 * User: Jorge
 * Date: 27/01/2018
 * Time: 16:44
 */

namespace App\Model\Repository;


class MarkerThemeRepository extends AbstractRepository
{
    protected $table = 'marker_theme';
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
        $queryBuilder->from($this->table, 'theme');
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