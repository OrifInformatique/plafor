<?php
/**
 * Fichier de model pour course_plan
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use function Plafor\Controllers\getCompetenceDomains;
use function Plafor\Controllers\getCoursePlans;
use function Plafor\Controllers\getObjectives;
use function Plafor\Controllers\getOperationalCompetences;

class CoursePlanModel extends Model{
    private static $coursePlanModel=null;
    protected $table='course_plan';
    protected $primaryKey='id';
    protected $allowedFields=['formation_number','official_name','date_begin', 'archive'];
    protected $useSoftDeletes=true;
    protected $deletedField='archive';
    private $userCourseModel=null;
    private $competenceDomainModel=null;
    protected $validationRules;

    /** should be public but don't know if
     *  it will be used so stay public
     */
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules=
            [
                'formation_number'=>[
                    'label' => 'plafor_lang.field_course_plan_formation_number',
                    'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->FORMATION_NUMBER_MAX_LENGTH.']|numeric'."|checkFormPlanNumber[{id}]",
                ],
                'official_name'=>[
                    'label' => 'plafor_lang.field_course_plan_official_name',
                    'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->OFFICIAL_NAME_MAX_LENGTH.']',
                ],
                'date_begin'=>[
                'label' => 'plafor_lang.field_course_plan_date_begin',
                'rules' => 'required',
            ]
        ];

        parent::__construct($db, $validation);
    }

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
    public static function getCompetenceDomains($coursePlanId,$with_archived=0){
        return CompetenceDomainModel::getInstance()->where('fk_course_plan',$coursePlanId)->withDeleted($with_archived)->findAll();
    }

    /**
     * @param $coursePlanId
     * @return array
     */
    public static function getUserCourses($coursePlanId){
        return UserCourseModel::getInstance()->where('fk_course_plan',$coursePlanId)->withDeleted(true)->findAll();
    }
    /**
     * @param $userId //is the apprentice id
     * @return null|string|array // return jsonobjects list organized by course
     *                               Plan contained compdom and opcomp
     */
    public function getCoursePlanProgress($userId){
        $competenceDomainsAssociated=[];
        $operationalCompetencesassociated=[];
        $coursePlanAssociated=[];
        function getCoursePlansDatas($userid){
            $coursplans=[];
            foreach (UserCourseModel::getInstance()->where('fk_user',$userid)->withDeleted(true)->findAll() as $userCourse){
                $coursplans[$userCourse['fk_course_plan']]=UserCourseModel::getCoursePlan($userCourse['fk_course_plan'],true);
                $coursplans[$userCourse['fk_course_plan']]['fk_acquisition_status']=$userCourse['fk_status'];
            }
            return $coursplans;
        }

        function getCompetenceDomainsDatas($coursePlanId){
                $indexedCompetenceDomains=[];
                $competenceDomains=CompetenceDomainModel::getCompetenceDomains(false,$coursePlanId);
                foreach ($competenceDomains as $competenceDomain) {
                    $indexedCompetenceDomains[$competenceDomain['id']]=$competenceDomain;
                }
            return $indexedCompetenceDomains;
        }
        function getOperationalCompetencesDatas($competenceDomainId){
            $operationalCompetences=[];
            $indexedOperationalCompetences=[];
            $operationalCompetences=OperationalCompetenceModel::getOperationalCompetences(false,$competenceDomainId);
            foreach ($operationalCompetences as $operationalCompetence){
                $indexedOperationalCompetences[$operationalCompetence['id']]=$operationalCompetence;
            }
            return $indexedOperationalCompetences;
        }
        function getObjectivesDatas($opCompId,$userCourse){
            $intermediateArray=[];

                    foreach (ObjectiveModel::getObjectives(false,$opCompId) as $objective){

                        ObjectiveModel::getAcquisitionStatus($objective['id'],$userCourse['id'])['fk_acquisition_level'];
                        $objective['fk_acquisition_level']=ObjectiveModel::getAcquisitionStatus($objective['id'],$userCourse['id'])['fk_acquisition_level'];
                        $intermediateArray[]=$objective;
                    }
                    $objectives=$intermediateArray;

            return $objectives;
        }
        if (!isset($userId)) {
            return null;
        }
        $coursePlans=getCoursePlansDatas($userId);
        foreach ($coursePlans as $coursePlan){
            $coursePlan['competenceDomains']=getCompetenceDomainsDatas($coursePlan['id']);

            foreach ($coursePlan['competenceDomains'] as $competenceDomain){
                $operationalCompetences=getOperationalCompetencesDatas($competenceDomain['id']);
                $competenceDomain['operationalCompetences']=$operationalCompetences;

                foreach ($competenceDomain['operationalCompetences'] as $operationalCompetence){
                    UserCourseModel::getInstance()->where(['fk_user'=>$userId,'fk_course_plan'=>$coursePlan['id']])->first();
                    $objectives=getObjectivesDatas($operationalCompetence['id'],UserCourseModel::getInstance()->where('fk_user',$userId)->where('fk_course_plan',$coursePlan['id'])->first());
                    $operationalCompetence['objectives']=$objectives;
                    $competenceDomain['operationalCompetences'][$operationalCompetence['id']]=$operationalCompetence;

                }
                $coursePlan['competenceDomains'][$competenceDomain['id']]= $competenceDomain;
            }
            $coursePlans[$coursePlan['id']]=$coursePlan;
            }

        return $coursePlans;







    }
}


?>