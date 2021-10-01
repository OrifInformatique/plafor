<?php


namespace Plafor\Validation;


use Plafor\Models\CoursePlanModel;
use Plafor\Models\CompetenceDomainModel;

class PlaforRules
{
    /**
     * @param $number mixed value of field to verify
     * @param $course_plan_id string|numeric the course_plan id if 0 or str doesnt exists in db
     * @param null $datas is set to access error
     * @param $error  -> string  to set the error message
     * @return bool
     */
    public function checkFormPlanNumber($number, $course_plan_id,$datas,&$error){
        if ($course_plan_id==0)
        if(count((new CoursePlanModel())->getWhere(['formation_number'=>$number])->getResultArray())>0){
            $error= lang('plafor_lang.form_number_not_unique');
            return false;
        }

        return true;
    }

    public function checkCompetenceDomainAssociated($text, $fields, $data, &$error)
    {
        $symbol = $data['symbol'];
        $name = $data['name'];
        $course_plan_id = $data['fk_course_plan'];
        $competence_domain_id = $data['id'];
        $competenceDomain=CompetenceDomainModel::getInstance()->find($competence_domain_id);
        //if the record is modified and doesn't exists
        if (count(array_diff($data,$competenceDomain==null?[]:$competenceDomain)) > 0 || $competenceDomain==null) {
            $competence_domains = CoursePlanModel::getCompetenceDomains($course_plan_id);
            foreach ($competence_domains as $competence_domain) {
                $arraySymbol = $competence_domain['symbol'];
                $arrayName = $competence_domain['name'];
                if ($arrayName == $name && $arraySymbol == $symbol) {
                    $error = lang('plafor_lang.same_competence_domain');
                    return false;
                }

            }
            //The name isn't associated to the same symbole and courses' plan, but the symbol can be already associated
        }


        return true;
    }

}