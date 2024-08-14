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

class AcquisitionStatusModel extends Model {
    protected $table = 'acquisition_status';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_objective', 'fk_user_course',
        'fk_acquisition_level'];
    private $objectiveModel = null;
    private $userCourseModel = null;
    private $acquisitionLevelModel = null;
    protected $validationRules;

    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $acquisitionLevelModel = model('AcquisitionLevelModel');
        $this->validationRules = ['fk_acquisition_level' => [
            'label' => 'plafor_lang.field_acquisition_level',
            'rules' => 'required|in_list['.implode(',', $acquisitionLevelModel->findColumn('id')).']'
        ]];
        parent::__construct($db, $validation);
    }

    /**
     * @param $fkObjectiveId /the id of the fk_objective
     * @return array|null
     */
    public function getObjective($fkObjectiveId) {
        $objectiveModel = model('ObjectiveModel');
        return $objectiveModel->withDeleted()->find($fkObjectiveId);

    }

    /**
     * @param $fkUserCourseId /the id of the fk_user_course
     * @return array|null
     */
    public function getUserCourse($fkUserCourseId) {
        $userCourseModel = model('UserCourseModel');
        return $userCourseModel->find($fkUserCourseId);
    }

    /**
     * @param $fkAcquisitionLevelId /the id of the fk_aquisition_level
     * @return array|null
     */
    public function getAcquisitionLevel($fkAcquisitionLevelId) {
        $acquisitionLevelModel = model('AcquisitionLevelModel');
        return $acquisitionLevelModel->find($fkAcquisitionLevelId);
    }
}
?>
