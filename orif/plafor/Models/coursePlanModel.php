<?php
namespace Plafor\Models;

use CodeIgniter\Model;

class CoursePlanModel extends Model{
    private static $coursePlanModel=null;
    protected $table='course_plan';
    protected $primaryKey='id';
    protected $allowedFields=['id', 'formation_number','official_name','date_begin', 'archive'];
    protected $useSoftDeletes=true;
    protected $deletedField='archive';
    private $userCourseModel=null;
    private $competenceDomainModel=null;

    /**
     * @return CoursePlanModel
     */
    public static function getInstance(){
        if (CoursePlanModel::$coursePlanModel==null)
            CoursePlanModel::$coursePlanModel=new CoursePlanModel();
        return CoursePlanModel::$coursePlanModel;
    }

    /**
     * @param $coursePlanId
     * @return array
     */
    public static function getCompetenceDomains($coursePlanId){
        return CompetenceDomainModel::getInstance()->where('fk_course_plan',$coursePlanId)->findAll();
    }

    /**
     * @param $coursePlanId
     * @return array
     */
    public static function getUserCourses($coursePlanId){
        return UserCourseModel::getInstance()->where('fk_course_plan',$coursePlanId)->findAll();
    }

}


?>