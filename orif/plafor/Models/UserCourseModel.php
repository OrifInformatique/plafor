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
    protected $table = 'user_course';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_user', 'fk_course_plan', 'fk_status',
        'date_begin', 'date_end'];
    protected $validationRules;
    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules = array(
            'fk_course_plan' => [
                'label' => 'plafor_lang.course_plan',
                'rules' => 'required|numeric',
            ],
            'fk_status' => [
                'label' => 'plafor_lang.status',
                'rules' => 'required|numeric',
            ],
            'date_begin' => [
                'label' => 'plafor_lang.field_user_course_date_begin',
                'rules' => 'required',
            ]
        );
        parent::__construct($db, $validation);
    }

    /**
     * @param $fkUserId
     * @return array
     */
    public function getUser($fkUserId) {
        $user_model = model('User_model');
        return $user_model->withDeleted()->find($fkUserId);
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
     * @param $fkCoursePlanId
     * @return array
     */
    public function getCoursePlan($fkCoursePlanId, $with_archived = false)
    {
        $coursePlanModel = model('CoursePlanModel');
        return $coursePlanModel->withDeleted($with_archived)
                                             ->find($fkCoursePlanId);
    }

    /**
     * @param $fkUserCourseStatusId
     * @return array
     */
    public function getUserCourseStatus($fkUserCourseStatusId) {
        $userCourseStatusModel = model('UserCourseStatusModel');
        return $userCourseStatusModel
            ->find($fkUserCourseStatusId);
    }

    /**
     * @param $userCourseId
     * @return array
     */
    public function getAcquisitionStatus($userCourseId) {
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        return $acquisitionStatusModel
            ->where('fk_user_course', $userCourseId)
            ->findAll();
    }
}
