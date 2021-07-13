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
    private CompetenceDomainModel $competenceDomainModel;

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