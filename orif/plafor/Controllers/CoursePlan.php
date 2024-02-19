<?php
/**
 * Controller pour la gestion des plan de formation non associés à un apprenti
 * Required level connected
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use CodeIgniter\I18n\Time;
use Plafor\Models\AcquisitionLevelModel;
use Plafor\Models\AcquisitionStatusModel;
use Plafor\Models\CompetenceDomainModel;
use Plafor\Models\CoursePlanModel;
use Plafor\Models\ObjectiveModel;
use Plafor\Models\OperationalCompetenceModel;
use Plafor\Models\TrainerApprenticeModel;
use Plafor\Models\UserCourseModel;
use Plafor\Models\UserCourseStatusModel;
use User\Models\User_model;

class CoursePlan extends \App\Controllers\BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        $this->access_level="@";
        parent::initController($request, $response, $logger);
    }

    /**
     * Adds or modify a course plan
     *
     * @param integer $course_plan_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_course_plan($course_plan_id = 0)
    {
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            $lastDatas = array();
            if (count($_POST) > 0) {
                $course_plan_id = empty($this->request->getPost('coursePlanId')) ? 0 : $this->request->getPost('coursePlanId');
                $course_plan = array(
                    'formation_number' => $this->request->getPost('formation_number'),
                    'official_name' => ' ' . $this->request->getPost('official_name'),
                    'date_begin' => $this->request->getPost('date_begin'),
                    'id' => $this->request->getPost('id'),
                );
                if ($course_plan_id > 0) {
                    CoursePlanModel::getInstance()->update($course_plan_id, $course_plan);
                } else {
                    CoursePlanModel::getInstance()->insert($course_plan);
                }
                if (CoursePlanModel::getInstance()->errors() == null) {
                    return redirect()->to(base_url('/plafor/courseplan/list_course_plan'));
                } else {//lastdatas takes the last datas if they arent't valid
                    $lastDatas = array(
                        'formation_number' => $this->request->getPost('formation_number'),
                        'official_name' => ' ' . $this->request->getPost('official_name'),
                        'date_begin' => $this->request->getPost('date_begin')
                    );
                }
            }
            if ($this->request->getPost('coursePlanId')) {
                $course_plan_id = $this->request->getPost('coursePlanId');
            }
            $formTitle = $course_plan_id <> 0 ? 'update' : 'new';
            $output = array(
                'title' => (lang('plafor_lang.title_course_plan_' . $formTitle)),
                'course_plan' => $lastDatas != null ? $lastDatas : CoursePlanModel::getInstance()->withDeleted()->find($course_plan_id),
                'errors' => CoursePlanModel::getInstance()->errors(),
            );

            $this->display_view('\Plafor\course_plan\save', $output);
        }
        else{
            return $this->display_view('\User\errors\403error');
        }
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
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            $competenceDomainIds = [];
            $objectiveIds = [];

            $course_plan = CoursePlanModel::getInstance()->withDeleted()->find($course_plan_id);
            if (is_null($course_plan)) {
                return redirect()->to('/plafor/courseplan/list_course_plan');
            }
            switch ($action) {
                case 0: // Display confirmation
                    $output = array(
                        'course_plan' => $course_plan,
                        'title' => lang('plafor_lang.title_delete_course_plan')
                    );
                    $this->display_view('\Plafor\course_plan\delete', $output);
                    break;
                case 1: // Deactivate (soft delete) course plan
                    //get linked competence domain

                    CoursePlanModel::getInstance()->delete($course_plan_id);
                    return redirect()->to('/plafor/courseplan/list_course_plan');
                    break;
                case 3:
                    //Reactiver le plan de formation et ses competence et objectifs associés
                    CoursePlanModel::getInstance()->withDeleted()->update($course_plan_id, ['archive' => null]);

                    return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
                    break;
                default:
                    // Do nothing
                    return redirect()->to('/plafor/courseplan/list_course_plan');
            }
        }else{
            return $this->display_view('\User\errors\403error');
        }
    }
    /**
     * Adds or modify a course plan
     *
     * @param integer $competence_domain_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_competence_domain($course_plan_id = 0,$competence_domain_id = 0)
    {
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

            if (count($_POST) > 0) {
                $competence_domain_id = $this->request->getPost('id');
                $competence_domain = array(
                    'symbol' => $this->request->getPost('symbol'),
                    'name' => $this->request->getPost('name'),
                    'fk_course_plan' => $this->request->getPost('course_plan'),
                    'id' => $competence_domain_id
                );
                if ($competence_domain_id > 0) {
                    CompetenceDomainModel::getInstance()->update($competence_domain_id, $competence_domain);
                } else {
                    CompetenceDomainModel::getInstance()->insert($competence_domain);
                }
                //if there aren't errors go here
                if (CompetenceDomainModel::getInstance()->errors() == null) {
                    return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . ($this->request->getPost('course_plan') == null ? '' : $this->request->getPost('course_plan'))));
                }
            }
            $course_plans = null;
            foreach (CoursePlanModel::getInstance()->findColumn('official_name') as $courseplanOfficialName)
                $course_plans[CoursePlanModel::getInstance()->where('official_name', $courseplanOfficialName)->first()['id']] = $courseplanOfficialName;
            $output = array(
                'title' => lang('plafor_lang.title_competence_domain_' . ((bool)$competence_domain_id ? 'update' : 'new')),
                'competence_domain' => CompetenceDomainModel::getInstance()->find($competence_domain_id),
                'course_plans' => $course_plans,
                'fk_course_plan_id' => $course_plan_id,
                'errors' => CompetenceDomainModel::getInstance()->errors(),
            );

            $this->display_view('\Plafor\competence_domain/save', $output);
        }else{
            return $this->display_view('\User\errors\403error');
        }
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
    public function delete_competence_domain($competence_domain_id = null, $action = 0)
    {
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

            if (!isset($competence_domain_id)) {
                // Back to course plans list
                return redirect()->to('plafor/courseplan/list_course_plan');
            } else {
                $competence_domain = CompetenceDomainModel::getInstance()->withDeleted()->find($competence_domain_id);
                if (is_null($competence_domain)) {
                    // Back to course plans list
                    return redirect()->to('plafor/courseplan/list_course_plan');
                }
            }

            switch ($action) {
                case 0: // Display confirmation
                    $output = array(
                        'competence_domain' => $competence_domain,
                        'title' => lang('plafor_lang.title_competence_domain_delete')
                    );

                    $this->display_view('\Plafor/competence_domain/delete', $output);
                    break;
                case 1: // Deactivate (soft delete) competence domain
                    $courseplanId = CompetenceDomainModel::getInstance()->find($competence_domain_id)['fk_course_plan'];
                    CompetenceDomainModel::getInstance()->delete($competence_domain_id);

                    return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . $courseplanId));
                    break;

                case 3:
                    //Reactiver le domaine de compétences
                    CompetenceDomainModel::getInstance()->withDeleted()->update($competence_domain_id, ['archive' => null]);
                    return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . $competence_domain['fk_course_plan']));
                    break;

                default: // Do nothing
                    return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . CompetenceDomainModel::getInstance()->find($competence_domain_id)['fk_course_plan']));
            }
        }else{
            return $this->display_view('\User\errors\403error');
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
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

            if (count($_POST) > 0) {
                $operational_competence_id = $this->request->getPost('id');

                $operational_competence = array(
                    'id' => $operational_competence_id != null ? $operational_competence_id : null,
                    'symbol' => $this->request->getPost('symbol'),
                    'name' => $this->request->getPost('name'),
                    'methodologic' => $this->request->getPost('methodologic'),
                    'social' => $this->request->getPost('social'),
                    'personal' => $this->request->getPost('personal'),
                    'fk_competence_domain' => $this->request->getPost('competence_domain')
                );
                if ($operational_competence_id > 0) {
                    //update
                    OperationalCompetenceModel::getInstance()->update($operational_competence_id, $operational_competence);
                } else {
                    //insert
                    OperationalCompetenceModel::getInstance()->insert($operational_competence);

                }


                if (OperationalCompetenceModel::getInstance()->errors() == null) {
                    //when it's ok
                    return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $operational_competence['fk_competence_domain']));
                }
            }
            $competenceDomains = [];
            foreach (CompetenceDomainModel::getInstance()->withDeleted()->findAll() as $competenceDomain) {
                $competenceDomains[CompetenceDomainModel::getInstance()->withDeleted()->where('id', $competenceDomain['id'])->first()['id']] = $competenceDomain['name'];

            }
            $output = array(
                'title' => lang('plafor_lang.title_operational_competence_' . ((bool)$operational_competence_id ? 'update' : 'new')),
                'operational_competence' => OperationalCompetenceModel::getInstance()->withDeleted()->find($operational_competence_id),
                'competence_domains' => $competenceDomains,
                'competence_domain_id' => $competence_domain_id,
                'errors' => OperationalCompetenceModel::getInstance()->errors(),
            );

            $this->display_view('\Plafor\operational_competence/save', $output);
        }else{
            return $this->display_view('\User\errors\403error');
        }
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
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

            $operational_competence = OperationalCompetenceModel::getInstance()->withDeleted()->find($operational_competence_id);
            if (is_null($operational_competence)) {
                return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $operational_competence['fk_competence_domain']));
            }
            switch ($action) {
                case 0: // Display confirmation
                    $output = array(
                        'operational_competence' => $operational_competence,
                        'title' => lang('plafor_lang.title_operational_competence_delete')
                    );
                    $this->display_view('\Plafor\operational_competence/delete', $output);
                    break;
                case 1: // Deactivate (soft delete) operational competence
                    OperationalCompetenceModel::getInstance()->delete($operational_competence_id, FALSE);
                    return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $operational_competence['fk_competence_domain']));
                    break;
                case 3:
                    //Reactiver la compétence opérationnelle
                    OperationalCompetenceModel::getInstance()->withDeleted()->update($operational_competence_id, ['archive' => null]);
                    return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $operational_competence['fk_competence_domain']));
                    break;
                default: // Do nothing
                    return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $operational_competence['fk_competence_domain']));
                    break;
            }
        }else{
            return $this->display_view('\User\errors\403error');
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
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

            $user_course = UserCourseModel::getInstance()->find($user_course_id);
            $course_plan = CoursePlanModel::getInstance()->find($user_course['fk_course_plan']);
            $apprentice = User_model::getInstance()->find($user_course['fk_user']);
            $status = UserCourseStatusModel::getInstance()->find($user_course['fk_status']);
            if (is_null($user_course)) {
                //à faire vue
                return redirect()->to(base_url('plafor/courseplan/user_course/list'));
            }

            switch ($action) {
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
                case 1: // Delete user course
                    /**@todo delete course plan
                     * **/
                    AcquisitionStatusModel::getInstance()->where('fk_user_course', $user_course_id)->delete();
                    UserCourseModel::getInstance()->delete($user_course_id, false);
                    return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
                default: // Do nothing
                    return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            }
        }else{
            return $this->display_view('\User\errors\403error');
        }
    }
    /**
     * Adds or modify a course plan
     *
     * @param integer $objective_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_objective($objective_id = 0, $operational_competence_id = 0)
    {
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

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
                    $objective_id=ObjectiveModel::getInstance()->insert($objective);
                    //when we add objective we have to update all students acquisition_status whene operationnal
                    $userCourses=CoursePlanModel::getUserCourses(
                        OperationalCompetenceModel::getCompetenceDomain(
                            ObjectiveModel::getOperationalCompetence(
                                $objective['fk_operational_competence']
                            )['fk_competence_domain']
                        )['fk_course_plan']
                    );
                    foreach($userCourses as $userCourse){
                     AcquisitionStatusModel::getInstance()->insert(['fk_objective'=>$objective_id,'fk_user_course'=>$userCourse['id'],'fk_acquisition_level'=>1]);

                    }
                }
                if (ObjectiveModel::getInstance()->errors() == null) {
                    //if ok
                    return redirect()->to(base_url('plafor/courseplan/view_operational_competence/' . $operational_competence_id));
                }
            }
            $operationalCompetences = [];
            foreach (OperationalCompetenceModel::getInstance()->findAll() as $operationalCompetence) {
                $operationalCompetences[$operationalCompetence['id']] = $operationalCompetence['name'];
            }
            $output = array(
                'title' => lang('plafor_lang.title_objective_' . ((bool)$objective_id ? 'update' : 'new')),
                'objective' => ObjectiveModel::getInstance()->withDeleted()->find($objective_id),
                'operational_competences' => $operationalCompetences,
                'operational_competence_id' => $operational_competence_id,
                'errors' => ObjectiveModel::getInstance()->errors(),
            );

            return $this->display_view('\Plafor\objective/save', $output);
        }else{
            return $this->display_view('\User\errors\403error');
        }
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
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {

            $objective = ObjectiveModel::getInstance()->withDeleted()->find($objective_id);
            if (is_null($objective)) {
                return redirect()->to('plafor/courseplan/objective/list');
            }

            switch ($action) {
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
                    return redirect()->to(base_url('plafor/courseplan/view_operational_competence/' . $objective['fk_operational_competence']));
                    break;
                case 2: // Hard delete
                    ObjectiveModel::getInstance()->delete($objective_id, TRUE);
                    return redirect()->to(base_url('plafor/courseplan/view_operational_competence/' . $objective['fk_operational_competence']));
                    break;

                case 3:
                    ObjectiveModel::getInstance()->withDeleted()->update($objective_id, ['archive' => null]);
                    return redirect()->to(base_url('plafor/courseplan/view_operational_competence/' . $objective['fk_operational_competence']));
                    break;
                default: // Do nothing
                    return redirect()->to('plafor/courseplan/view_operational_competence' . $objective['fk_operational_competence']);
            }
        }else{
            return $this->display_view('\User\errors\403error');
        }
    }
    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_course_plan($id_apprentice = null, $with_archived=false)
    {
        $this->request->getGet('wa')!=null?$with_archived=$this->request->getGet('wa'):null;
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
     * Show details of the selected course plan
     *
     * @param int (SQL PRIMARY KEY) $course_plan_id
     *
     */
    public function view_course_plan($course_plan_id = null)
    {
        $with_archived=$this->request->getGet('wa')!=null?$this->request->getGet('wa'):false;
        if(!isset($course_plan_id)) {
            // Back to course plans list
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        } else {
            $course_plan = CoursePlanModel::getInstance()->withDeleted(true)->find($course_plan_id);
            if(is_null($course_plan)) {
                // Back to course plans list
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }
        }

        $competence_domains=CoursePlanModel::getCompetenceDomains($course_plan_id,$with_archived);

        // Format date
        $date_begin = Time::createFromFormat('Y-m-d', $course_plan['date_begin']);
        $course_plan['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');

        $output = array(
            'title'=>lang('plafor_lang.title_course_plan_view'),
            'course_plan'=>$course_plan,
            'competence_domains'=>$competence_domains
        );

        return $this->display_view('\Plafor\course_plan\view',$output);
    }

    /**
     * Show details of the selected competence domain
     *
     * @param int (SQL PRIMARY KEY) $competence_domain_id
     *
     */
    public function view_competence_domain($competence_domain_id = null)
    {
        if(!isset($competence_domain_id)) {
            // Back to course plans list
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        } else {
            $competence_domain = CompetenceDomainModel::getInstance()->withDeleted()->find($competence_domain_id);

            if(is_null($competence_domain)) {
                // Back to course plans list
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }
        }
        $with_archived=$this->request->getGet('wa')!=null?$this->request->getGet('wa'):false;

        $course_plan = CompetenceDomainModel::getCoursePlan($competence_domain['fk_course_plan'], true);

        // Format date
        $date_begin = Time::createFromFormat('Y-m-d', $course_plan['date_begin']);
        $course_plan['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');

        $output = array(
            'title' =>lang('plafor_lang.title_view_competence_domain'),
            'course_plan' =>$course_plan,
            'competence_domain' => $competence_domain,
            'with_archived'=>$with_archived,
        );

        return $this->display_view('\Plafor/competence_domain/view',$output);
    }
    /**
     * Show details of the selected operational competence
     *
     * @param int $operational_competence_id = ID of the operational competence to view
     * @return void
     */
    public function view_operational_competence($operational_competence_id = null)
    {
        $operational_competence = OperationalCompetenceModel::getInstance()->withDeleted(true)->find($operational_competence_id);

        if($operational_competence == null){
            return redirect()->to(base_url('plafor/courseplan/list_course_plan/'));
        }
        $with_archived=$this->request->getGet('wa')!=null?$this->request->getGet('wa'):false;

        $competence_domain=null;
        $course_plan=null;
        try {
            $competence_domain = OperationalCompetenceModel::getCompetenceDomain($operational_competence['fk_competence_domain']);
            $course_plan = CompetenceDomainModel::getCoursePlan($competence_domain['fk_course_plan']);
        }catch (Exception $exception){

        }
        $objectives=OperationalCompetenceModel::getObjectives($operational_competence['id'],$with_archived);
        $output = array(
            'title'=>lang('plafor_lang.title_view_operational_competence'),
            'operational_competence' => $operational_competence,
            'competence_domain' => $competence_domain,
            'course_plan' => $course_plan,
            'objectives' => $objectives
        );

        return $this->display_view('\Plafor/operational_competence/view',$output);
    }
    /**
     * Show details of the selected objective
     * @param int $objective_id = ID of the objective to view
     * @return void
     */
    public function view_objective($objective_id = null)
    {
        $objective = ObjectiveModel::getInstance()->withDeleted()->find($objective_id);

        if($objective == null){
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }


        $operational_competence = ObjectiveModel::getOperationalCompetence($objective['fk_operational_competence'],true);

        $competence_domain = OperationalCompetenceModel::getCompetenceDomain($operational_competence['fk_competence_domain']);
        $course_plan=null;
        if (isset($competence_domain))
            $course_plan = CompetenceDomainModel::getCoursePlan($competence_domain['fk_course_plan']);

        $output = array(
            'title' => lang('plafor_lang.title_view_objective'),
            'objective' => $objective,
            'operational_competence' => $operational_competence,
            'competence_domain' => $competence_domain,
            'course_plan' => $course_plan
        );

        $this->display_view('Plafor\objective/view',$output);
    }
}