<?php
/**
 * Fichier de model pour acquisition_status
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class AcquisitionStatusModel extends Model{
    private static $acquisitionStatusModel=null;
    protected $table='acquisition_status';
    protected $primaryKey='id';
    protected $allowedFields=['fk_objective','fk_user_course','fk_acquisition_level'];
    private $objectiveModel=null;
    private $userCourseModel=null;
    private $acquisitionLevelModel=null;
    protected $validationRules;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules= ['fk_acquisition_level'=>[
            'label'=>'plafor_lang.field_acquisition_level',
            'rules'=>'required|in_list['.implode(',', AcquisitionLevelModel::getInstance()->findColumn('id')).']'
        ]];
        parent::__construct($db, $validation);
    }

    /**
     * @return AcquisitionStatusModel
     */
    public static function getInstance(){
        if (AcquisitionStatusModel::$acquisitionStatusModel==null) {
            AcquisitionStatusModel::$acquisitionStatusModel = new AcquisitionStatusModel();
        }
        return AcquisitionStatusModel::$acquisitionStatusModel;
    }
    /**
     * @param $fkObjectiveId /the id of the fk_objective
     * @return array|null
     */
    public static function getObjective($fkObjectiveId){
        return ObjectiveModel::getInstance()->withDeleted()->find($fkObjectiveId);

    }
    /**
     * @param $fkUserCourseId /the id of the fk_user_course
     * @return array|null
     */
    public static function getUserCourse($fkUserCourseId){
        return UserCourseModel::getInstance()->find($fkUserCourseId);

    }
    /**
     * @param $fkAcquisitionLevelId /the id of the fk_aquisition_level
     * @return array|null
     */
    public static function getAcquisitionLevel($fkAcquisitionLevelId){
        return AcquisitionLevelModel::getInstance()->find($fkAcquisitionLevelId);

    }
}






?>