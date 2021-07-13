<?php 

namespace Plafor\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class CompetenceDomainModel extends Model{
    private static $competenceDomainModel=null;
    protected $table='competence_domain';
    protected $primaryKey='id';
    protected $allowedFields=['fk_course_plan','symbol','name','archive'];
    protected $useSoftDeletes=true;
    protected $deletedField='archive';
    private $coursePlanModel=null;
    private $operationalCompetenceModel=null;

    /**
     * @return CompetenceDomainModel
     */
    public static function getInstance(){
        if (CompetenceDomainModel::$competenceDomainModel==null)
            CompetenceDomainModel::$competenceDomainModel=new CompetenceDomainModel();
        return CompetenceDomainModel::$competenceDomainModel;
    }

    /**
     * @param $fkCoursePlanId
     * @return array|null
     */
    public static function getCoursePlan($fkCoursePlanId,$withArchived=false){

        return CoursePlanModel::getInstance()->withDeleted($withArchived)->find($fkCoursePlanId);

    }

    /**
     * @param $competenceDomainId
     * @return array|null
     */
    public static function getOperationalCompetences($competenceDomainId){
        return OperationalCompetenceModel::getInstance()->where('fk_competence_domain',$competenceDomainId)->findAll();
    }

    public static function getCompetenceDomains($with_archived = false, $course_plan_id = 0) {
        if($course_plan_id==0) {
            return CompetenceDomainModel::getInstance()->withDeleted($with_archived)->findall();
        } else {
            return CompetenceDomainModel::getInstance()->where('fk_course_plan', $course_plan_id)->withDeleted($with_archived)->findall();
        }
    }
}






?>