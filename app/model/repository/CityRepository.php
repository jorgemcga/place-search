<?php
/**
 * Description of UserRepository
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model\Repository;

use App\Model\Repository\AbstractRepository;

class CityRepository extends AbstractRepository
{
    protected $table = 'city';
    protected $primaryKey = 'city_id';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function find($columns = [], $where = '', $limit = '', $orderBy = '', $groupBy = '')
    {
        $select = count($columns) > 0 ? implode(',', $columns) : "*";
                
        $queryBuilder = $this->databaseService->getQueryBuilder();
        $queryBuilder->select($select);
        $queryBuilder->from($this->table, 'ci');
        $queryBuilder->join('ci', 'state', 'st', 'ci.state_id = st.state_id');
        $queryBuilder->where($where);

        if (!empty($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        return $this->databaseService->executeSelect();
    }
    
    public function findOne($columns = [], $where = '', $limit = '', $orderBy = '', $groupBy = '')
    {
        $city = $this->find($columns, $where, $limit, $orderBy, $groupBy);
        return isset($city[0]) ? $city[0] : [];
    }
}