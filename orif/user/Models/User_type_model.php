<?php
/**
 * Model User_type_model represents the user_type table
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace User\Models;


use CodeIgniter\Model;

class User_type_model extends \CodeIgniter\Model
{
    private static $userTypeModel;
    protected $table='user_type';
    protected $primaryKey='id';

    /**
     * @return Model
     */
    public static function getInstance(){
        if (User_type_model::$userTypeModel==null)
            User_type_model::$userTypeModel=new User_type_model();
        return User_type_model::$userTypeModel;
    }

}