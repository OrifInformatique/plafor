<?php
/**
 * Fichier de model pour operational_competence
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class OperationalCompetenceModel extends \CodeIgniter\Model
{
    protected $table = 'operational_competence';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_competence_domain', 'name', 'symbol',
        'methodologic', 'social', 'personal', 'archive'];
    protected $useSoftDeletes = 'true';
    protected $deletedField = 'archive';
    protected $validationRules;
    private CompetenceDomainModel $competenceDomainModel;

    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules = array(
            'symbol' => [
                'label' => 'plafor_lang.field_operational_competence_symbol',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH.']|is_symbol_unique[]'
            ],
            'name' => [
                'label' => 'plafor_lang.field_operational_competence_name',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH.']'
            ],
            'methodologic' => [
                'label' => 'plafor_lang.field_operational_methodologic',
                'rules' => 'max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']'
            ],
            'social' => [
                'label' => 'plafor_lang.field_operational_social',
                'rules' => 'max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']'
            ],
            'personal' => [
                'label' => 'plafor_lang.field_operational_personal',
                'rules' => 'max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']'
            ],
        );
        parent::__construct($db, $validation);
    }

    /**
     * @param $fkCompetenceDomainId
     * @return array|object|null
     */
    public function getCompetenceDomain($fkCompetenceDomainId) {
        $competenceDomainModel = model('CompetenceDomainModel');
        return $competenceDomainModel->withDeleted()->find($fkCompetenceDomainId);
    }

    /**
     * @param $operational_competenceId
     * @return array
     */
    public function getObjectives($operational_competenceId, $with_archived = 0)
    {
        $objectiveModel = model('ObjectiveModel');
        return $objectiveModel->withDeleted($with_archived)
                              ->where('fk_operational_competence',
                                  $operational_competenceId)
                              ->findAll();
    }

    public function getOperationalCompetences($with_archived = false,
        $competence_domain_id = 0)
    {
        $operational_competenceModel = model('OperationalCompetenceModel');
        if($competence_domain_id == 0) {
            return $operational_competenceModel->withDeleted($with_archived)
                                              ->findall();
        } else {
            return $operational_competenceModel
                ->where('fk_competence_domain', $competence_domain_id)
                ->withDeleted($with_archived)->findall();
        }
    }
}
