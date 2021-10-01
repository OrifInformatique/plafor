<?php


namespace Plafor\Controllers;


use CodeIgniter\Validation\Validation;
use Plafor\Models\CompetenceDomainModel;
use Plafor\Models\CoursePlanModel;
use Plafor\Models\ObjectiveModel;
use Plafor\Models\OperationalCompetenceModel;
use Plafor\Models\UserCourseModel;
use Plafor\Models\UserCourseStatusModel;
use Plafor\Models\TrainerApprenticeModel;

use User\Models\User_type_model;
use User\Models\User_model;

class Admin extends \App\Controllers\BaseController
{
    private Validation $validation;
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        $this->access_level=config('\User\Config\UserConfig')->access_lvl_admin;
        parent::initController($request, $response, $logger);
        $this->validation =  \Config\Services::validation();
    }

    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_course_plan($id_apprentice = null, bool $with_archived=false)
    {
        $id_apprentice==0?$id_apprentice = null:null;
        $coursePlanModel=new CoursePlanModel();
        $userCourseModel=new UserCourseModel();
        if($id_apprentice == null){
            $course_plans = $coursePlanModel->withDeleted($with_archived)->findAll();
        }else{
            $userCourses = $userCourseModel->getWhere(['fk_user'=>$id_apprentice])->getResult();

            $coursesId = array();

            foreach ($userCourses as $userCourse){
                $coursesId[] = $userCourse->fk_course_plan;
            }

            //$course_plans = $this->course_plan_model->get_many($coursesId);
            $course_plans=$coursePlanModel->whereIn('id',count($coursesId)==0?[null]:$coursesId)->findAll();
        }

        $output = array(
            'title' =>  lang('plafor_lang.title_list_course_plan'),
            'course_plans' => $course_plans,
            'with_archived' => $with_archived
        );

        if(is_numeric($id_apprentice)){
            $output[] = ['course_plans' => $course_plans];
        }

        $this->display_view(['Plafor\course_plan\list'], $output);
    }
    /**
     * Adds or modify a course plan
     *
     * @param integer $course_plan_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_course_plan($course_plan_id = 0)
    {
        $lastDatas = array();
        if (count($_POST) > 0) {
           $course_plan_id = empty($this->request->getPost('coursePlanId'))?0:$this->request->getPost('coursePlanId');
            $course_plan = array(
                'formation_number' => $this->request->getPost('formation_number'),
                'official_name' => ' '.$this->request->getPost('official_name'),
                'date_begin' => $this->request->getPost('date_begin'),
                'id'        =>  $this->request->getPost('id'),
            );
            if ($course_plan_id > 0) {
                CoursePlanModel::getInstance()->update($course_plan_id, $course_plan);
            } else {
                CoursePlanModel::getInstance()->insert($course_plan);
            }
           if (CoursePlanModel::getInstance()->errors()==null) {
               return redirect()->to(base_url('/plafor/admin/list_course_plan'));
           }
            else {//lastdatas takes the last datas if they arent't valid
                $lastDatas = array(
                    'formation_number' => $this->request->getPost('formation_number'),
                    'official_name' => ' '.$this->request->getPost('official_name'),
                    'date_begin' => $this->request->getPost('date_begin')
                );
            }
        }
        if($this->request->getPost('coursePlanId')){
            $course_plan_id = $this->request->getPost('coursePlanId');
        }
        $formTitle = $course_plan_id<>0?'update' : 'new';
        $output = array(
            'title' => (lang('plafor_lang.title_course_plan_'.$formTitle)),
            'course_plan' => $lastDatas!=null?$lastDatas:CoursePlanModel::getInstance()->withDeleted()->find($course_plan_id),
            'errors'=>CoursePlanModel::getInstance()->errors(),
        );

        $this->display_view('\Plafor\course_plan\save', $output);
    }
    /**
     * Deletes a course plan depending on $action
     *
     * @param integer $course_plan_id = ID of the course_plan to affect
     * @param integer $action = Action to apply on the course plan:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function delete_course_plan($course_plan_id, $action = 0)
    {
        $competenceDomainIds=[];
        $objectiveIds=[];

        $course_plan = CoursePlanModel::getInstance()->withDeleted()->find($course_plan_id);
        if (is_null($course_plan)) {
            return redirect()->to('/plafor/admin/list_course_plan');
        }
        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'course_plan' => $course_plan,
                    'title' => lang('plafor_lang.title_delete_course_plan')
                );
                $this->display_view('\Plafor\course_plan\delete', $output);
                break;
            case 1: // Deactivate (soft delete) course plan
                //get linked competence domain

                $competenceDomains=CoursePlanModel::getCompetenceDomains($course_plan['id']);
                $operationalCompetences=[];
                $objectives=[];

                foreach ($competenceDomains as $competence_domain){
                    //get all operationnal competences in an array($operational_competences) which format is [[:competencedomainid]=>[operationalCompetence id, name, etc...],[:competencedomainid]=>[operationalCompetence id, name, etc...]
                    $operationalCompetences[$competence_domain['id']]=CompetenceDomainModel::getOperationalCompetences($competence_domain['id']);
                    //get all objectives assiociated with an operational_competence in an array($objectives) which format is [[operationalcompetenceid]=>[objectives id,fkop, symbol, etc...]
                    try {
                        foreach ($operationalCompetences as list($operationalCompetence)) {
                            $objectives[$operationalCompetence['id']] = OperationalCompetenceModel::getObjectives($operationalCompetence['id']);
                        }
                    }catch (\Exception $e){};

                }
                //get all ids
                $competenceDomainIds=array_column($competenceDomains,'id');
                foreach ($objectives as list($objective)) {
                    $objectiveIds[] = $objective['id'];

                }




                count($objectiveIds)>0?ObjectiveModel::getInstance()->whereIn('id',$objectiveIds)->delete():null;
                count($competenceDomainIds)>0?OperationalCompetenceModel::getInstance()->whereIn('fk_competence_domain',$competenceDomainIds)->delete():null;
                CompetenceDomainModel::getInstance()->where('fk_course_plan',$course_plan_id);
                CoursePlanModel::getInstance()->delete($course_plan_id, FALSE);
                return redirect()->to('/plafor/admin/list_course_plan');
                break;
                case 3:
                    //Reactiver le plan de formation
                    CoursePlanModel::getInstance()->withDeleted()->update($course_plan_id, ['archive' => null]);
                    return redirect()->to(base_url('plafor/admin/list_course_plan'));
                    break;
            default:
                // Do nothing
                return redirect()->to('/plafor/admin/list_course_plan');
        }
    }
    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_competence_domain($id_course_plan = null , $with_archived = 0)
    {
        if($id_course_plan == null or $id_course_plan == 0){
            $competence_domains = CompetenceDomainModel::getCompetenceDomains($with_archived);
        }else{
            $course_plan = CoursePlanModel::getInstance()->find($id_course_plan);
            $competence_domains = CompetenceDomainModel::getCompetenceDomains((bool)$with_archived, $id_course_plan);

        }


        $output = array(
            'title'=>lang('plafor_lang.title_competence_domain_list'),
            'competence_domains' => $competence_domains,
            'id_course_plan' => $id_course_plan,
            'with_archived' => $with_archived
        );
        return $this->display_view(['\Plafor\competence_domain\list'], $output);
    }
    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_operational_competence($id_competence_domain = null, $with_archived = 0)
    {
        if($id_competence_domain == null or $id_competence_domain == 0){
            $operational_competences = OperationalCompetenceModel::getOperationalCompetences($with_archived);
        }else{
            $competence_domain = CompetenceDomainModel::getInstance()->find($id_competence_domain);
            $operational_competences = OperationalCompetenceModel::getOperationalCompetences($with_archived, $id_competence_domain);
        }

        $output = array(
            'title'=>lang('plafor_lang.title_list_operational_competence'),
            'operational_competences' => $operational_competences,
            'with_archived' => $with_archived
        );

        if(is_numeric($id_competence_domain) && $id_competence_domain != 0){
            $output[] = ['competence_domain' => $competence_domain];
            $output['id_competence_domain'] = $id_competence_domain;
        }

        $this->display_view(['\Plafor/operational_competence/list'], $output);
        exit();
    }
    /**
     * Adds or modify a course plan
     *
     * @param integer $competence_domain_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_competence_domain($competence_domain_id = 0, $course_plan_id = 0)
    {
        if (count($_POST) > 0) {
            $competence_domain_id = $this->request->getPost('id');
            $competence_domain = array(
                'symbol' => $this->request->getPost('symbol'),
                'name' => $this->request->getPost('name'),
                'fk_course_plan' => $this->request->getPost('course_plan'),
                'id' =>$competence_domain_id
            );
            if ($competence_domain_id > 0) {
                CompetenceDomainModel::getInstance()->update($competence_domain_id, $competence_domain);
            } else {
                CompetenceDomainModel::getInstance()->insert($competence_domain);
            }
            //if there aren't errors go here
            if (CompetenceDomainModel::getInstance()->errors()==null) {
                return redirect()->to(base_url('plafor/admin/list_competence_domain/'.($this->request->getPost('course_plan')==null?'':$this->request->getPost('course_plan'))));
            }
        }
        $course_plans=null;
        foreach (CoursePlanModel::getInstance()->findColumn('official_name') as $courseplanOfficialName)
            $course_plans[CoursePlanModel::getInstance()->where('official_name',$courseplanOfficialName)->first()['id']]=$courseplanOfficialName;
        $output = array(
            'title' => lang('plafor_lang.title_competence_domain_'.((bool)$competence_domain_id ? 'update' : 'new')),
            'competence_domain' => CompetenceDomainModel::getInstance()->find($competence_domain_id),
            'course_plans' => $course_plans,
            'fk_course_plan_id' => $course_plan_id,
            'errors'=>CompetenceDomainModel::getInstance()->errors(),
            );

        $this->display_view('\Plafor\competence_domain/save', $output);
    }
    /**
     * Deletes a course plan depending on $action
     *
     * @param integer $competence_domain_id = ID of the competence_domain to affect
     * @param integer $action = Action to apply on the course plan:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function delete_competence_domain($competence_domain_id, $action = 0)
    {
        $competence_domain = CompetenceDomainModel::getInstance()->withDeleted()->find($competence_domain_id);
        if (is_null($competence_domain)) {
            return redirect()->to('plafor/admin/competence_domain/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'competence_domain' => $competence_domain,
                    'title' => lang('plafor_lang.title_competence_domain_delete')
                );
                $this->display_view('\Plafor/competence_domain/delete', $output);
                break;
            case 1: // Deactivate (soft delete) competence domain
                $operationalCompetenceIds=[];
                foreach (CompetenceDomainModel::getOperationalCompetences($competence_domain_id) as $operational_competence){
                    $operationalCompetenceIds[] = $operational_competence['id'];
                }

                if (count($operationalCompetenceIds))
                ObjectiveModel::getInstance()->whereIn('fk_operational_competence',$operationalCompetenceIds)->delete();
                OperationalCompetenceModel::getInstance()->where('fk_competence_domain',$competence_domain_id)->delete();
                CompetenceDomainModel::getInstance()->delete($competence_domain_id);
                return redirect()->to(base_url('plafor/admin/list_competence_domain'));
                break;

                case 3:
                    //Reactiver le domaine de compétences

                    CompetenceDomainModel::getInstance()->withDeleted()->update($competence_domain_id, ['archive' => null]);
                    return redirect()->to(base_url('plafor/admin/list_competence_domain/'.$competence_domain['fk_course_plan']));
                    break;

            default: // Do nothing
                return redirect()->to(base_url('plafor/admin/list_competence_domain'));
        }
    }
    /**
     * Adds or modify a course plan
     *
     * @param integer $operational_competence_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_operational_competence($operational_competence_id = 0, $competence_domain_id = 0)
    {
        if (count($_POST) > 0) {
            $operational_competence_id = $this->request->getPost('id');

            $operational_competence = array(
                'id'    => $operational_competence_id!=null?$operational_competence_id:null,
                'symbol' => $this->request->getPost('symbol'),
                'name' => $this->request->getPost('name'),
                'methodologic' => $this->request->getPost('methodologic'),
                'social' => $this->request->getPost('social'),
                'personal' => $this->request->getPost('personal'),
                'fk_competence_domain' => $this->request->getPost('competence_domain')
            );
            if ($operational_competence_id>0){
                //update
                OperationalCompetenceModel::getInstance()->update($operational_competence_id, $operational_competence);
            }
            else{
                //insert
                OperationalCompetenceModel::getInstance()->insert($operational_competence);

            }


            if (OperationalCompetenceModel::getInstance()->errors()==null) {
                //when it's ok
                return redirect()->to(base_url('plafor/admin/list_operational_competence/'.$competence_domain_id));
            }
        }
        $competenceDomains=[];
        foreach (CompetenceDomainModel::getInstance()->withDeleted()->findAll() as $competenceDomain){
            $competenceDomains[CompetenceDomainModel::getInstance()->withDeleted()->where('id',$competenceDomain['id'])->first()['id']]=$competenceDomain['name'];

        }
        $output = array(
            'title' => lang('plafor_lang.title_operational_competence_'.((bool)$operational_competence_id ? 'update' : 'new')),
            'operational_competence' => OperationalCompetenceModel::getInstance()->withDeleted()->find($operational_competence_id),
            'competence_domains' => $competenceDomains,
            'competence_domain_id' => $competence_domain_id,
            'errors'    => OperationalCompetenceModel::getInstance()->errors(),
        );

        $this->display_view('\Plafor\operational_competence/save', $output);
    }
    /**
     * Deletes a course plan depending on $action
     *
     * @param integer $operational_competence_id = ID of the operational_competence to affect
     * @param integer $action = Action to apply on the course plan:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function delete_operational_competence($operational_competence_id, $action = 0)
    {
        $operational_competence = OperationalCompetenceModel::getInstance()->withDeleted()->find($operational_competence_id);
        if (is_null($operational_competence)) {
            return redirect()->to(base_url('plafor/admin/list_operational_competence'));
        }
        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'operational_competence' => $operational_competence,
                    'title' => lang('plafor_lang.title_operational_competence_delete')
                );
                $this->display_view('\Plafor\operational_competence/delete', $output);
                break;
            case 1: // Deactivate (soft delete) operational competence
                ObjectiveModel::getInstance()->where('fk_operational_competence',$operational_competence_id)->delete();
                OperationalCompetenceModel::getInstance()->delete($operational_competence_id, FALSE);
                return redirect()->to(base_url('plafor/admin/list_operational_competence'));
                break;
            case 3:
                //Reactiver la compétence opérationnelle
                OperationalCompetenceModel::getInstance()->withDeleted()->update($operational_competence_id, ['archive' => null]);
                return redirect()->to(base_url('plafor/admin/list_operational_competence/'.$operational_competence['fk_competence_domain']));
                break;
            default: // Do nothing
                return redirect()->to(base_url('plafor/admin/list_operational_competence'));
                break;
        }
    }
    /**
     * Deletes a trainer_apprentice link depending on $action
     *
     * @param integer $link_id = ID of the trainer_apprentice_link to affect
     * @param integer $action = Action to apply on the trainer_apprentice link :
     *  - 0 for displaying the confirmation
     *  - 1 for deleting (hard delete)
     * @return void
     */
    public function delete_apprentice_link($link_id, $action = 0){
        $link = TrainerApprenticeModel::getInstance()->find($link_id);
        $apprentice = TrainerApprenticeModel::getApprentice($link['fk_apprentice']);
        $trainer = TrainerApprenticeModel::getTrainer($link['fk_trainer']);
        if (is_null($link)) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'link' => $link,
                    'apprentice' => $apprentice,
                    'trainer' => $trainer,
                    'title' => lang('plafor_lang.title_apprentice_link_delete')
                );
                $this->display_view('\Plafor\apprentice/delete', $output);
                break;
            case 1: // Delete apprentice link
                TrainerApprenticeModel::getInstance()->delete($link_id, TRUE);
                return redirect()->to(base_url('plafor/apprentice/list_apprentice/'.$apprentice['id']));
            default: // Do nothing
                return redirect()->to(base_url('plafor/apprentice/list_apprentice/'.$apprentice['id']));
        }
    }
    /**
     * Deletes a user_course depending on $action
     *
     * @param integer $user_course_id = ID of the user_course to affect
     * @param integer $action = Action to apply on the course plan:
     *  - 0 for displaying the confirmation
     *  - 1 for deleting (hard delete)
     * @return void
     */
    public function delete_user_course($user_course_id, $action = 0){
        $user_course = UserCourseModel::getInstance()->find($user_course_id);
        $course_plan = CoursePlanModel::getInstance()->find($user_course['fk_course_plan']);
        $apprentice = User_model::getInstance()->find($user_course['fk_user']);
        $status = UserCourseStatusModel::getInstance()->find($user_course['fk_status']);
        if (is_null($user_course)) {
            //à faire vue
            return redirect()->to(base_url('plafor/admin/user_course/list'));
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'user_course' => $user_course,
                    'course_plan' => $course_plan,
                    'apprentice' => $apprentice,
                    'status' => $status,
                    'title' => lang('plafor_lang.title_user_course_delete')
                );
                $this->display_view('Plafor\user_course/delete', $output);
                break;
            case 1: // Delete course plan
                /**@todo delete course plan
                 * **/
                UserCourseModel::getInstance()->delete($user_course_id, false);
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            default: // Do nothing
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }
    }
    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_objective($id_operational_competence = 0,bool $with_archived=false)
    {
        $competences_op[0] = lang('common_lang.all_f');


        $operational_competence=null;
        if($id_operational_competence == null ||$id_operational_competence==0 && !$with_archived){
            $objectives = ObjectiveModel::getInstance()->findAll();
        }
        elseif ($id_operational_competence == null || $id_operational_competence==0 && $with_archived)
            $objectives = ObjectiveModel::getObjectives($with_archived, $id_operational_competence);
        else{
            $operational_competence = OperationalCompetenceModel::getInstance()->find($id_operational_competence);
            $objectives = ObjectiveModel::getObjectives($with_archived, $id_operational_competence);
        }
        $output = array(
            'title' => lang('plafor_lang.title_list_objective'),
            'objectives' => $objectives,
            'with_archived' => $with_archived
        );

        if(is_numeric($id_operational_competence) && $id_operational_competence != 0){
            $output[] = ['operational_competence',$operational_competence];
            $output['operational_competence_id'] = $id_operational_competence;
        }

        $this->display_view(['Plafor\objective/list'], $output);
    }
    /**
     * Adds or modify a course plan
     *
     * @param integer $objective_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_objective($objective_id = 0, $operational_competence_id = 0)
    {
        if (count($_POST) > 0) {
            $objective_id = $this->request->getPost('id');
            $objective = array(
                'symbol' => $this->request->getPost('symbol'),
                'taxonomy' => $this->request->getPost('taxonomy'),
                'name' => $this->request->getPost('name'),
                'fk_operational_competence' => $this->request->getPost('operational_competence')
            );
            if ($objective_id > 0) {
                //update
                ObjectiveModel::getInstance()->update($objective_id, $objective);
            } else {
                //insert
                ObjectiveModel::getInstance()->insert($objective);
            }
            if (ObjectiveModel::getInstance()->errors()==null) {
                //if ok
                return redirect()->to(base_url('plafor/admin/list_objective/'.($operational_competence_id!=0?$operational_competence_id:'')));
            }
        }
        $operationalCompetences=[];
        foreach (OperationalCompetenceModel::getInstance()->findAll() as $operationalCompetence) {
            $operationalCompetences[$operationalCompetence['id']]=$operationalCompetence['name'];
        }
        $output = array(
            'title' => lang('plafor_lang.title_objective_'.((bool)$objective_id ? 'update' : 'new')),
            'objective' => ObjectiveModel::getInstance()->withDeleted()->find($objective_id),
            'operational_competences' => $operationalCompetences,
            'operational_competence_id' => $operational_competence_id,
            'errors'    => ObjectiveModel::getInstance()->errors(),
        );

        return $this->display_view('\Plafor\objective/save', $output);
    }
    /**
     * Deletes a course plan depending on $action
     *
     * @param integer $objective_id = ID of the objective to affect
     * @param integer $action = Action to apply on the course plan:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     *  - 3 for reactivating
     * @return void
     */
    public function delete_objective($objective_id, $action = 0)
    {
        $objective = ObjectiveModel::getInstance()->withDeleted()->find($objective_id);
        if (is_null($objective)) {
            return redirect()->to('plafor/admin/objective/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'objective' => $objective,
                    'title' => lang('plafor_lang.title_objective_delete'),
                    'deleted' => $objective['archive']
                );
                $this->display_view('\Plafor\objective/delete', $output);
                break;
            case 1: // Deactivate (soft delete) objective
                ObjectiveModel::getInstance()->delete($objective_id, FALSE);
                return redirect()->to(base_url('plafor/admin/list_objective/'.$objective['fk_operational_competence']));
                break;
            case 2: // Hard delete
                ObjectiveModel::getInstance()->delete($objective_id, TRUE);
                return redirect()->to(base_url('plafor/admin/list_objective'));
                break;

            case 3:
                ObjectiveModel::getInstance()->withDeleted()->update($objective_id,['archive'=>null]);
                return redirect()->to(base_url('plafor/admin/list_objective/'.$objective['fk_operational_competence']));
                break;
            default: // Do nothing
                return redirect()->to('plafor/admin/list_objective');
        }
    }

    /**
     * Form to create a link between a apprentice and a course plan
     *
     * @param int (SQL PRIMARY KEY) $id_user_course
     */

    /*public function save_user_course($id_apprentice = null,$id_user_course = 0){

        $apprentice = User_model::getInstance()->find($id_apprentice);
        $user_course = UserCourseModel::getInstance()->find($id_user_course);

        if($id_apprentice == null || $apprentice['fk_user_type'] != User_type_model::getInstance()->where('name',lang('plafor_lang.title_apprentice'))['id']){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        if(count($_POST) > 0){
            $rules = array(
                    'course_plan'=>[
                    'label' => 'plafor_lang.course_plan',
                    'rules' => 'required|numeric',
                    ],
                    'status'=>[
                    'label' => 'plafor_lang.status',
                    'rules' => 'required|numeric',
                    ],
                    'date_begin'=>[
                    'label' => 'plafor_lang.field_user_course_date_begin',
                    'rules' => 'required'
                    ]
                /*
                array(
                    'field' => 'date_end',
                    'label' => 'lang:field_user_course_date_end',
                    'rules' => 'required',
                ),
                */
    /*
            );

            $this->validation->setRules($rules);

            if($this->validation->withRequest($this->request)->run()){
                $user_course = array(
                    'fk_user' => $id_apprentice,
                    'fk_course_plan' => $this->request->getPost('course_plan'),
                    'fk_status' => $this->request->getPost('status'),
                    'date_begin' => $this->request->getPost('date_begin'),
                    'date_end' => $this->request->getPost('date_end'),
                );

                if($id_user_course > 0){
                    echo UserCourseModel::getInstance()->update($id_user_course, $user_course);
                }else{
                    UserCourseModel::getInstance()->insert($user_course);

                    $course_plan = UserCourseModel::getCoursePlan($user_course['fk_course_plan']);

                    $competenceDomainIds = array_column(CoursePlanModel::getCompetenceDomains($course_plan['id']), 'id');

                    $operational_competences = OperationalCompetenceModel::getInstance()->whereIn('fk_competence_domain',$competenceDomainIds)->findAll();

                    $objectiveIds = array_column(ObjectiveModel::getInstance()->whereIn('fk_operational_competence',array_column($operational_competences,'id'))->findAll(),'id');

                    foreach ($objectiveIds as $objectiveId){

                        $acquisition_status = array(
                            'fk_objective' => $objectiveId,
                            'fk_user_course' => $id_user_course,
                            'fk_acquisition_level' => 1
                        );

                        AcquisitionStatusModel::getInstance()->insert($acquisition_status);
                    }
                }

                return redirect()->to(base_url('plafor/apprentice/view_apprentice/'.$id_apprentice));
            }
        }

        $course_plans=[];
        foreach (CoursePlanModel::getInstance()->findAll() as $courseplan)
            $course_plans[$courseplan['id']]=$courseplan['official_name'];
        $status=[];
        foreach (UserCourseStatusModel::getInstance()->findAll() as $usercoursestatus)
            $status[$usercoursestatus['id']]=$usercoursestatus['name'];

        $output = array(
            'title' => lang('plafor_lang.title_course_plan_link'),
            'course_plans' => $course_plans,
            'user_course'   => $user_course,
            'status' => $status,
            'apprentice' => $apprentice
        );

        return $this->display_view('Plafor\user_course/save',$output);
    }
    */
    /**
     * Create a link between a apprentice and a trainer, or change the trainer
     * linked on the selected trainer_apprentice SQL entry
     *
     * @param INT (SQL PRIMARY KEY) $id_apprentice
     * @param INT (SQL PRIMARY KEY) $id_link
     */
    /*
    public function save_apprentice_link($id_apprentice = null, $id_link = 0){

        $apprentice = User_model::getInstance()->find($id_apprentice);

        if($_SESSION['user_access'] < config('\Plafor\Config\plaforConfig')->access_lvl_admin
            || $apprentice == null
            || $apprentice['fk_user_type'] != User_type_model::getInstance()->where('name',lang('plafor_lang.title_apprentice'))->first()['id']){
            return redirect()->to(base_url());
        }

        // It seems that the MY_model dropdown method can't return a filtered result
        // so here we get every users that are trainer, then we create a array
        // with the matching constitution

        if(count($_POST) > 0){
            $id_apprentice = $this->request->getPost('id');
            $rules = array(
                    'apprentice'=>[
                    'label' => 'field_apprentice_username',
                    'rules' => 'required|numeric'
                    ],
                    'trainer'=>[
                    'label' => 'field_trainer_link',
                    'rules' => 'required|numeric'
                    ]
            );

            $this->validation->setRules($rules);

            if($this->validation->withRequest($this->request)->run()){

                $apprentice_link = array(
                    'fk_trainer' => $this->request->getPost('trainer'),
                    'fk_apprentice' => $this->request->getPost('apprentice'),
                );

                if($id_link > 0){
                    echo TrainerApprenticeModel::getInstance()->update($id_apprentice,$apprentice_link);
                }else{
                    echo TrainerApprenticeModel::getInstance()->insert($apprentice_link);
                }

                return redirect()->to('plafor/apprentice/list_apprentice');
            }
        }

        $trainersRaw = User_model::getTrainers();

        $trainers = array();

        foreach ($trainersRaw as $trainer){
            $trainers[$trainer['id']] = $trainer['username'];
        }

        $link = TrainerApprenticeModel::getInstance()->find($id_link);

        $output = array(
            'apprentice' => $apprentice,
            'trainers' => $trainers,
            'link' => $link,
        );

        return $this->display_view('\Plafor\apprentice/link',$output);
    }
    */
}