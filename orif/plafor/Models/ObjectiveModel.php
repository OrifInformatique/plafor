<?php
/**
 * Fichier de model pour objective
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class ObjectiveModel extends \CodeIgniter\Model
{
    protected $table = 'objective';
    protected $primaryKey = 'id';
    protected $allowedFields = ['archive', 'fk_operational_competence',
        'symbol', 'taxonomy', 'name'];
    protected $useSoftDeletes = true;
    protected $deletedField = 'archive';
    private OperationalCompetenceModel $operational_competenceModel;
    protected $validationRules;

    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules = array(
            'symbol' => [
                'label' => 'plafor_lang.field_objective_symbol',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH.']|is_symbol_unique[]',
            ],
            'taxonomy' => [
                'label' => 'plafor_lang.field_objective_taxonomy',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->TAXONOMY_MAX_VALUE.']',
            ],
            'name' => [
                'label' => 'plafor_lang.field_objective_name',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->OBJECTIVE_NAME_MAX_LENGTH.']',
            ]
        );
        parent::__construct($db, $validation);
    }

    /**
     * @param $fkOperationalCompetenceId
     * @return array|object|null
     */
    public function getOperationalCompetence($fkOperationalCompetenceId) {
        $operational_competenceModel = model('OperationalCompetenceModel');
        return $operational_competenceModel->withDeleted()
                                          ->find($fkOperationalCompetenceId);
    }

    /**
     * @param $objectiveId
     * @return array
     */
    public function getAcquisitionStatus($objectiveId, $userCourseId = null) {
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        if ($userCourseId != null) {
            return $acquisitionStatusModel
                ->where('fk_objective', $objectiveId)
                ->where('fk_user_course', $userCourseId)
                ->first();
        }
        return $acquisitionStatusModel->where('fk_objective', $objectiveId)
                                      ->findAll();
    }

    public function getObjectives($with_archived = false,
        $operational_competence_id = 0)
    {
        $objectiveModel = model('ObjectiveModel');
        if($operational_competence_id == 0) {
            return $objectiveModel->withDeleted($with_archived)->findAll();
        } else {
            return $objectiveModel
                ->where('fk_operational_competence',
                    $operational_competence_id)
                ->withDeleted($with_archived)->findAll();
        }
    }
}
