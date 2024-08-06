<?php
/**
 * Fichier de validation pour plafor
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Validation;


use Plafor\Models\CoursePlanModel;
use Plafor\Models\CompetenceDomainModel;
use Plafor\Models\OperationalCompetenceModel;
use Plafor\Models\ObjectiveModel;
use Plafor\Models\TrainerApprenticeModel;

class PlaforRules
{
    /**
     * @param $number           : mixed value of field to verify
     * @param $course_plan_id   : string|numeric the course_plan id if 0 or str doesnt exists in db
     * @param null $datas       : is set to access error
     * @param $error            : string to set the error message
     * @return bool
     */
    public function checkFormPlanNumber($number, $course_plan_id, $datas,
        &$error)
    {
        $model = model('CoursePlanModel');
        $coursePlans = $model->getWhere(['formation_number'=>$number])
            ->getResultArray();
        // to update not to insert new
        $withoutItself = array_filter($coursePlans,
            fn($plan) => $course_plan_id != $plan['id']);
        $isExisted = count($withoutItself) > 0;
        if ($isExisted) {
            $error = lang('plafor_lang.form_number_not_unique');
        }
        return !$isExisted;
    }

     /**
     * Check if the given apprentice ID and the given trainer ID 
     *  exist in the same row of the table "trainer_apprentice"
     *
     * @param  int $fkApprenticeId  : ID of the apprentice, required
     * @param  int $fkTrainerId     : ID of the trainer, required
     * @param  null $datas          : Is set to access error
     * @param  string &$error       : Error message
     * @return bool
     */
    public function AreApprenticeAndTrainerNotLinked($fkTrainerId, $fkApprenticeId, $datas, &$error) : bool{
        $array_where = array('fk_trainer' => $fkTrainerId, 'fk_apprentice' => $fkApprenticeId);

        if (empty(TrainerApprenticeModel::getInstance()->getWhere($array_where)->getResultArray())){
            return true;
        }
        $error = lang('plafor_lang.apprentice_trainer_already_linked');
        return false;
    }
    
    /**
     * Check if the symbol of the competence domain already exists in database
     * 
     * @param $symbol   : The symbol to check
     * @param $params   : Optional parameters
     * @param $data     : Datas sent to the form
     * @param $error    : String to set the error message
     * @return bool     : True if symbol is unique, false otherwise
     */
    public function is_symbol_unique($symbol, string $params, array $data, &$error): bool {
        // Initializing variables depending on the type of data to validate
        switch ($_POST['type']) {
            case 'competence_domain':
                // For a competence domain
                $parent = 'course_plan';
                $model = CompetenceDomainModel::getInstance();
                $error_str = 'same_competence_domain';
                break;
            case 'operational_competence':
                // For an operational competence
                $parent = 'competence_domain';
                $model = OperationalCompetenceModel::getInstance();
                $error_str = 'same_operational_competence';
                break;
            case 'objective':
                // For an objective
                $parent = 'operational_competence';
                $model = ObjectiveModel::getInstance();
                $error_str = 'same_objective';
                break;
        }

        // Retrieves the IDs of the data to validate
        $id = (int)$_POST['id'];
        $parent_id = (int)$data['fk_'.$parent];

        // Selects all datas under the same parent
        $datas = $model->where('fk_'.$parent, $parent_id)->withDeleted()->find();

        foreach ($datas as $data) {
            // Checks if the symbol is already attributed
            if ($data['symbol'] == $symbol && (int)$data['id'] !== $id) {
                $error = lang('plafor_lang.'.$error_str);
                return false;
            }
        }
        return true;
    }
}
