<?php
namespace Plafor\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

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