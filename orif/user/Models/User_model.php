<?php
/**
 * Model User_model this represents the user table
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace User\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class User_model extends \CodeIgniter\Model{
    private static $userModel;
    protected $table='user';
    protected $primaryKey='id';
    protected $allowedFields=['archive','date_creation','username','password','fk_user_type'];
    protected $useSoftDeletes=true;
    protected $deletedField="archive";

    /**
     * @return User_model
     */
    public static function getInstance(){
        if (User_model::$userModel==null)
            User_model::$userModel=new User_model();
        return User_model::$userModel;
    }

    /**
     * Check username and password for login
     *
     * @param string $username
     * @param string $password
     * @return boolean true on success false otherwise
     */
    public static function check_password_name($username, $password){
        $user=User_model::getInstance()->where("username",$username)->first();
        //If a user is found we can verify his password because if his archive is not empty, he is not in the array
        if (!is_null($user)){
            return password_verify($password,$user['password']);
        }
        else{
            return false;

        }


    }

    /**
     * @param string $email
     * @param string $password
     * @return bool true on success false otherwise
     */
   public static function check_password_email($email,$password){
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return false;
        }
        $user = User_model::getInstance()->where('email',$email)->first();
        if (!is_null($user)){
            return password_verify($password,$user['password']);
        }
        else{
            return false;
        }
    }

    /**
     * return the access level of an user
     * @param $fkUserTypeId
     * @return mixed
     */
    public static function get_access_level($userID){
        return User_type_model::getInstance()->getWhere(['id'=>User_model::getInstance()->find($userID)['fk_user_type']])->getRow()->access_level;

    }

    /**
     * @return array the list of apprentices
     */
    public static function getApprentices(bool $withDeleted=false){

        if ($withDeleted)
            return User_model::getInstance()->where('fk_user_type',User_type_model::getInstance()->where('access_level', config("\User\Config\UserConfig")->access_level_apprentice)->first()['id'])->withDeleted()->findAll();
        return User_model::getInstance()->where('fk_user_type',User_type_model::getInstance()->where('name','Apprenti')->first()['id'])->findAll();

    }

    /**
     * @return array the list of trainers
     */
    public static function getTrainers(bool $withDelted=false){
        $indexedTrainers = array();
        if ($withDelted) {
            $trainers = User_model::getInstance()->where('fk_user_type',User_type_model::getInstance()->where('name','Formateur')->first()['id'])->withDeleted()->findAll();
            foreach ($trainers as $trainer) {
                $indexedTrainers[$trainer['id']] = $trainer;
            }
            return $indexedTrainers;
        }
        $trainers = User_model::getInstance()->where('fk_user_type',User_type_model::getInstance()->where('name','Formateur')->first()['id'])->findAll();
        foreach ($trainers as $trainer) {
            $indexedTrainers[$trainer['id']] = $trainer;
        }
        return $indexedTrainers;

    }


}