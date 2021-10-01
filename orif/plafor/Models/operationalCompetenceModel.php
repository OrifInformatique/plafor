<?php


namespace Plafor\Models;


use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class OperationalCompetenceModel extends \CodeIgniter\Model
{
    private static $operationalCompetenceModel=null;
    protected $table='operational_competence';
    protected $primaryKey='id';
    protected $allowedFields=['fk_competence_domain','name','symbol','methodologic','social','personal', 'archive'];
    protected $useSoftDeletes='true';
    protected $deletedField='archive';
    protected $validationRules;
    private CompetenceDomainModel $competenceDomainModel;

    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        $this->validationRules=$rules = array(
            'symbol'=>[
                'label' => 'plafor_lang.field_operational_competence_symbol',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH.']'
            ],
            'name'=>[
                'label' => 'plafor_lang.field_operational_competence_name',
                'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH.']'
            ],
            'methodologic'=>[
                'label' => 'plafor_lang.field_operational_methodologic',
                'rules' => 'max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']'
            ],
            'social'=>[
                'label' => 'plafor_lang.field_operational_social',
                'rules' => 'max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']'
            ],
            'personal'=>[
                'label' => 'plafor_lang.field_operational_personal',
                'rules' => 'max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']'
            ],
        );
        parent::__construct($db, $validation);
    }

    /**
     * @return OperationalCompetenceModel
     */
    public static function getInstance(){
        if (OperationalCompetenceModel::$operationalCompetenceModel==null)
            OperationalCompetenceModel::$operationalCompetenceModel=new OperationalCompetenceModel();
        return OperationalCompetenceModel::$operationalCompetenceModel;
    }

    /**
     * @param $fkCompetenceDomainId
     * @return array|object|null
     */
    public static function getCompetenceDomain($fkCompetenceDomainId){
        return CompetenceDomainModel::getInstance()->find($fkCompetenceDomainId);
    }

    /**
     * @param $operationalCompetenceId
     * @return array
     */
    public static function getObjectives($operationalCompetenceId){
        return ObjectiveModel::getInstance()->where('fk_operational_competence',$operationalCompetenceId)->findAll();
    }

    public static function getOperationalCompetences($with_archived = false, $competence_domain_id = 0) { 
        if($competence_domain_id == 0) {
            return OperationalCompetenceModel::getInstance()->withDeleted($with_archived)->findall();
        } else {
            return OperationalCompetenceModel::getInstance()->where('fk_competence_domain', $competence_domain_id)->withDeleted($with_archived)->findall();
        }
    }

}