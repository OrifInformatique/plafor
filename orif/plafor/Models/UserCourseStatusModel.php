<?php
/**
 * Fichier de model pour user_course_status
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;


class UserCourseStatusModel extends \CodeIgniter\Model
{
    private static $userCourseStatusModel=null;
    protected $table='user_course_status';
    protected $primaryKey='id';
    protected $allowedFields=['name'];

    /**
     * @return UserCourseStatusModel
     */
    public static function getInstance(){
        if (UserCourseStatusModel::$userCourseStatusModel==null)
            UserCourseStatusModel::$userCourseStatusModel=new UserCourseStatusModel();
        return UserCourseStatusModel::$userCourseStatusModel;
    }


    /**
     * Gets the name of a status.
     * 
     * @param int $id_user_course_status ID of the user course status
     * 
     * @return string
     * 
     */
    public function getUserCourseStatusName($id_user_course_status)
    {
        return $this->select('name')->where('id', $id_user_course_status)->first()['name'];
    }

    /**
     * @param $userCourseStatusId
     * @return array
     */
    public static function getUserCourses($userCourseStatusId){
        return UserCourseModel::getInstance()->where('fk_status',$userCourseStatusId)->findAll();
    }
}