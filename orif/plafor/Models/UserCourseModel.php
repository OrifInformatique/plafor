<?php
/**
 * Fichier de model pour user_course
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;
use User\Models\User_model;

class UserCourseModel extends \CodeIgniter\Model
{
    private static $userCourseModel=null;
    protected $table='user_course';
    protected $primaryKey='id';
    protected $allowedFields=['fk_user','fk_course_plan','fk_status','date_begin','date_end'];
    protected $validationRules;
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules=array(
            'fk_course_plan'=>[
                'label' => 'plafor_lang.course_plan',
                'rules' => 'required|numeric',
            ],
            'fk_status'=>[
                'label' => 'plafor_lang.status',
                'rules' => 'required|numeric',
            ],
            'date_begin'=>[
                'label' => 'plafor_lang.field_user_course_date_begin',
                'rules' => 'required',
            ]
        );
        parent::__construct($db, $validation);
    }

    /**
     * @return UserCourseModel
     */
    public static function getInstance(){
        if (UserCourseModel::$userCourseModel==null)
            UserCourseModel::$userCourseModel=new UserCourseModel();
        return UserCourseModel::$userCourseModel;
    }

    /**
     * @param $fkUserId
     * @return array
     */
    public static function getUser($fkUserId){
        return User_model::getInstance()->withDeleted()->find($fkUserId);
    }

    /**
     * Gets all courses from a user.
     * 
     * @param int $fk_user ID of the user
     * 
     * @return array
     * 
     */
    public static function getUserCourses($fk_user){
        return UserCourseModel::getInstance()
            ->join('course_plan','course_plan.id = user_course.fk_course_plan')
            ->where('fk_user', $fk_user)
            ->findAll();
    }


    /**
     * Gets a specific user_course entry-
     * 
     * @param int $id_user_course ID of the user_course entry
     * 
     * @return array
     * 
     */
    public static function getUserCourseById($id_user_course)
    {
        return UserCourseModel::getInstance()
            ->where('id', $id_user_course)
            ->first();
    }

    /**
     * Gets the user_course id from the user_course entry.
     * 
     * @param int $fk_user ID of the user
     * @param int $fk_course_plan ID of the course plan
     * @param bool $onlyId If true, the function only returns the ID of the entry
     * 
     * @return array
     * 
     */
    public static function getUserCourseByUserAndCoursePlan($fk_user, $fk_course_plan, $onlyId = false)
    {
        $user_course = UserCourseModel::getInstance()
            ->where(
            [
                'fk_user' => $fk_user,
                'fk_course_plan' => $fk_course_plan
            ])
            ->first();
            
        if($onlyId)
            return $user_course['id'];

        else
            return $user_course;
    }

    /**
     * @param $fkCoursePlanId
     * @return array
     */
    public static function getCoursePlan($fkCoursePlanId,
        $with_archived = false)
    {
        return CoursePlanModel::getInstance()->withDeleted($with_archived)
                                             ->find($fkCoursePlanId);
    }

    /**
     * @param $fkUserCourseStatusId
     * @return array
     */
    public static function getUserCourseStatus($fkUserCourseStatusId){
        return UserCourseStatusModel::getInstance()->find($fkUserCourseStatusId);
    }

    /**
     * @param $userCourseId
     * @return array
     */
    public static function getAcquisitionStatus($userCourseId){
        return AcquisitionStatusModel::getInstance()->where('fk_user_course',$userCourseId)->findAll();
    }
}
