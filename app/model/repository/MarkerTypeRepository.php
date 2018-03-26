<?php
/**
 * Description of MarkerTypeRepository
 *
 * @author JMA
 */

namespace App\Model\Repository;


class MarkerTypeRepository extends AbstractRepository
{
    protected $table = 'marker_type';
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
        $queryBuilder->from($this->table, 'type');
       // $queryBuilder->join('ci', 'state', 'st', 'ci.state_id = st.state_id');
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