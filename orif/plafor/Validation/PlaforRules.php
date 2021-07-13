<?php


namespace Plafor\Validation;


use Plafor\Models\CoursePlanModel;
use Plafor\Models\CompetenceDomainModel;

class PlaforRules
{
    public function checkFormPlanNumber($number, &$error){
        if(count((new CoursePlanModel())->getWhere(['formation_number'=>$number])->getResultArray())>0){
            $error= lang('user_lang.form_number_not_unique');
            return false;
        }

        return true;
    }

    public function checkSameCompetenceDomain($text, $fields, $data, &$error) {
        $symbol = $data['symbol'];
        $name = $data['name'];
        $id = $data['course_plan'];
        $competence_domains = CoursePlanModel::getCompetenceDomains($id);
         foreach($competence_domains as $competence_domain) {
            $arraySymbol = $competence_domain['symbol'];
            $arrayName = $competence_domain['name'];
            if($arrayName == $name && $arraySymbol == $symbol) {
                $error= lang('plafor_lang.same_competence_domain');
                return false;
            }
         }
         
         return true;
    }

}