<?php
/**
 * Fichier de model pour competence_domain
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class CompetenceDomainModel extends Model {
    protected $table = 'competence_domain';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_course_plan', 'symbol', 'name', 'archive'];
    protected $useSoftDeletes = true;
    protected $deletedField = 'archive';
    private $coursePlanModel = null;
    private $operational_competenceModel = null;
    protected $validationRules;


    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules = array(
            'symbol'=>[
                'label' => 'plafor_lang.field_competence_domain_symbol',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH.']|is_symbol_unique[]'
            ],
            'name'=>[
                'label' => 'plafor_lang.field_competence_domain_name',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->COMPETENCE_DOMAIN_NAME_MAX_LENGTH.']'
            ],
        );
        parent::__construct($db, $validation);
    }

    /**
     * @param $fkCoursePlanId
     * @return array|null
     */
    public function getCoursePlan($fkCoursePlanId) {
        $coursePlanModel = model('CoursePlanModel');
        return $coursePlanModel->withDeleted()->find($fkCoursePlanId);
    }

    /**
     * @param $competenceDomainId
     * @return array|null
     */
    public function getOperationalCompetences($competenceDomainId,
        $withArchived = false)
    {
        $operational_competenceModel = model('OperationalCompetenceModel');
        return $operational_competenceModel
            ->withDeleted($withArchived)
            ->where('fk_competence_domain', $competenceDomainId)->findAll();
    }

    public function getCompetenceDomains($with_archived = false,
        $course_plan_id = 0)
    {
        $competenceDomainModel = model('CompetenceDomainModel');
        if($course_plan_id == 0) {
            return $competenceDomainModel->withDeleted($with_archived)
                                         ->findall();
        } else {
            return $competenceDomainModel
                ->where('fk_course_plan', $course_plan_id)
                ->withDeleted($with_archived)->findall();
        }
    }
}
?>
