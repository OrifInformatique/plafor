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
    protected $table = 'user_course_status';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    /**
     * @param $userCourseStatusId
     * @return array
     */
    public function getUserCourses($userCourseStatusId) {
        $userCourseModel = model('UserCourseModel');
        return $userCourseModel
            ->where('fk_status', $userCourseStatusId)
            ->findAll();
    }
}
