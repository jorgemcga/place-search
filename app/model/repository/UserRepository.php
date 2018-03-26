<?php
/**
 * Description of UserRepository
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */

namespace App\Model\Repository;


class UserRepository extends AbstractRepository
{
    protected $table = 'user';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getByEmail($email)
    {
        $users = $this->find([], "email = '{$email}'");
        return isset($users[0]) ? $users[0] : false;
    }
    
    public function getByEmailPassword($email, $password)
    {
        $where = "email = '{$email}' AND password = '{$password}'";
        $users = $this->find([], $where);
        return isset($users[0]) ? $users[0] : false;
    }
    
    public function getByToken($token)
    {
        $users = $this->find([], "token = '{$token}'");
        return isset($users[0]) ? $users[0] : false;
    }
}