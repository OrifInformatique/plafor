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

class CoursePlan extends \App\Controllers\BaseController
{
    /**
     * Method to initialize controller attributes
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        $this->access_level="@";
        parent::initController($request, $response, $logger);

        // Loads required models
        $this->acquisition_status_model = model('AcquisitionStatusModel');
        $this->comment_model = model('CommentModel');
        $this->comp_domain_model = model('CompetenceDomainModel');
        $this->course_plan_model = model('CoursePlanModel');
        $this->objective_model = model('ObjectiveModel');
        $this->operational_comp_model = model('OperationalCompetenceModel');
        $this->user_course_model = model('UserCourseModel');
        $this->user_course_status_model = model('UserCourseStatusModel');
        $this->trainer_apprentice_model = model('TrainerApprenticeModel');
        $this->user_model = model('User_model');
        $this->user_type_model = model('User_type_model');
        $this->m_teaching_domain_model = model("TeachingDomainModel");
        $this->m_teaching_subject_model = model("TeachingSubjectModel");
        $this->m_teaching_module_model = model("TeachingModuleModel");
        helper("AccessPermissions_helper");
    }



    /**
     * Adds or modifies a course plan
     *
     * @param integer $course_plan_id : ID of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_course_plan($course_plan_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the course plan if it exists
            $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);
            $lastDatas = array();

            // Actions upon form submission
            if (count($_POST) > 0) {
                // Data to insert or update
                $new_course_plan = array(
                    'formation_number'  => $this->request->getPost('formation_number'),
                    'official_name'     => $this->request->getPost('official_name'),
                    'date_begin'        => $this->request->getPost('date_begin'),
                );

                // Query to perform
                if (!is_null($course_plan)) {
                    // Course plan already exists - add id
                    $new_course_plan['id'] = $course_plan_id;
                }
                $this->course_plan_model->save($new_course_plan);


                // Error handling
                if ($this->course_plan_model->errors() == null) {
                    // No error - redirects to list of course plans
                    return redirect()->to(base_url('/plafor/courseplan/list_course_plan'));
                } else {
                    // Error - autofills form with pre-submitted values
                    $lastDatas = array(
                        'formation_number'  => $this->request->getPost('formation_number'),
                        'official_name'     => $this->request->getPost('official_name'),
                        'date_begin'        => $this->request->getPost('date_begin')
                    );
                }
            }

            // Data to send to the view
            $formTitle = !is_null($course_plan) ? 'update' : 'new';
            $output = array(
                'title'         => (lang('plafor_lang.title_course_plan_' . $formTitle)),
                'course_plan'   => !empty($lastDatas) ? $lastDatas : $course_plan,
                'errors'        => $this->course_plan_model->errors(),
            );
            return $this->display_view('\Plafor\course_plan\save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }



    /**
     * Alterate a course_plan depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param integer $course_plan_id ID of the course_plan to affect.
     *
     * @param integer $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_course_plan(int $action = null, int $course_plan_id = 0, bool $confirm = false)
    {
        if (!isCurrentUserAdmin())
            return $this->display_view('\User\errors\403error');

        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

        if (is_null($course_plan) || !isset($action))
            return redirect()->to('/plafor/courseplan/list_course_plan');

        if(!$confirm)
        {
            $apprentices = [];

            $courses = $this->course_plan_model->getUserCourses($course_plan['id']);

            foreach ($courses as $course)
                $apprentices[] = $this->user_course_model->getUser($course['fk_user']);

            $output = array
            (
                'entry' =>
                [
                    'type'    => lang('plafor_lang.course_plan'),
                    'name'    => $course_plan['official_name'],
                ],
                'cancel_btn_url' => base_url('plafor/courseplan/list_course_plan')
            );

            foreach($apprentices as $apprentice)
            {
                $output['linked_entries'][] =
                [
                    'type' => lang('plafor_lang.apprentice'),
                    'name' => $apprentice['username']
                ];
            }
        }

        switch($action)
        {
            // Deactivates (soft delete) course plan
            case 1:
                if(!$confirm)
                {
                    $output['type'] = 'disable';
                    $output['entry']['message'] = lang('plafor_lang.course_plan_disable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                // Soft deletion
                $this->course_plan_model->delete($course_plan_id);
                break;

            // Reactivates course plan
            case 3:
                if(!$confirm)
                {
                    $output['type'] = 'reactivate';
                    $output['entry']['message'] = lang('plafor_lang.course_plan_enable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->course_plan_model->withDeleted()->update($course_plan_id, ['archive' => null]);
                break;
        }

        return redirect()->to('/plafor/courseplan/list_course_plan');
    }



    /**
     * Adds or modifies a competence domain
     *
     * @param integer $course_plan_id       : ID of the course plan
     * @param integer $competence_domain_id : ID of the competence domain to modify, leave blank to create a new one
     * @return void
     */
    public function save_competence_domain($course_plan_id = 0, $competence_domain_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the course plan and the competence domain if they exist
            $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);
            $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

            // Redirection
            if (is_null($course_plan) ||
                !is_null($competence_domain) && $competence_domain['fk_course_plan'] != $course_plan_id) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                $new_competence_domain = array(
                    'fk_course_plan'    => $this->request->getPost('course_plan'),
                    'symbol'            => $this->request->getPost('symbol'),
                    'name'              => $this->request->getPost('name')
                );

                // Query to perform
                if (!is_null($competence_domain)) {
                    // Competence domain already exists - updates it
                    $this->comp_domain_model->update($competence_domain_id, $new_competence_domain);
                } else {
                    // No competence domain found in database - creates a new one
                    $this->comp_domain_model->insert($new_competence_domain);
                }
                // Error handling
                if ($this->comp_domain_model->errors() == null) {
                    // No error - redirects to course plan
                    return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . ($new_competence_domain['fk_course_plan']??'')));
                }
            }

            // Data to send to the view
            $course_plans = null;
            foreach ($this->course_plan_model->findColumn('official_name') as $courseplanOfficialName)
                $course_plans[$this->course_plan_model->where('official_name', $courseplanOfficialName)->first()['id']] = $courseplanOfficialName;

            $output = array(
                'title'                 => lang('plafor_lang.title_competence_domain_'.(!is_null($competence_domain) ? 'update' : 'new')),
                'competence_domain_id'  => $competence_domain_id,
                'competence_domain'     => $competence_domain,
                'course_plans'          => $course_plans,
                'fk_course_plan_id'     => $course_plan_id,
                'errors'                => $this->comp_domain_model->errors(),
            );
            return $this->display_view('\Plafor\competence_domain/save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }



    /**
     * Alterate a competence domain depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param integer $competence_domain_id ID of the competence domain to affect.
     *
     * @param integer $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_competence_domain(int $action = null, int $competence_domain_id = 0, bool $confirm = false)
    {
        if (!isCurrentUserAdmin())
            return $this->display_view('\User\errors\403error');

        $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

        if (is_null($competence_domain) || !isset($action))
            return redirect()->to('plafor/courseplan/list_course_plan');

        if(!$confirm)
        {
            $output = array
            (
                'entry' =>
                [
                    'type'    => lang('plafor_lang.competence_domain'),
                    'name'    => $competence_domain['name']
                ],
                'cancel_btn_url' => base_url('plafor/courseplan/view_course_plan/'.$competence_domain_id)
            );
        }

        switch ($action)
        {
            // Deactivates (soft delete) competence domain
            case 1:
                if(!$confirm)
                {
                    $output['type'] = 'disable';
                    $output['entry']['message'] = lang('plafor_lang.competence_domain_disable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->comp_domain_model->delete($competence_domain_id);
                break;

            // Reactivates competence domain
            case 3:
                if(!$confirm)
                {
                    $output['type'] = 'reactivate';
                    $output['entry']['message'] = lang('plafor_lang.competence_domain_enable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->comp_domain_model->withDeleted()->update($competence_domain_id, ['archive' => null]);
                break;
        }

        return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . $competence_domain['fk_course_plan']));
    }



    /**
     * Adds or modifies an operational competence
     *
     * @param integer $operational_competence_id : ID of the operational competence to modify,
     *                                             leave blank to create a new one
     * @param integer $competence_domain_id      : ID of the competence domain
     * @return void
     */
    public function save_operational_competence($comp_domain_id = 0, $operational_comp_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the competence domain and the operational competence if they exist
            $comp_domain = $this->comp_domain_model->withDeleted()->find($comp_domain_id);
            $operational_comp = $this->operational_comp_model->withDeleted()->find($operational_comp_id);

            // Redirection
            if (is_null($comp_domain) ||
                !is_null($operational_comp) && $operational_comp['fk_competence_domain'] != $comp_domain_id) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                $new_operational_comp = array(
                    'symbol'                => $this->request->getPost('symbol'),
                    'name'                  => $this->request->getPost('name'),
                    'methodologic'          => $this->request->getPost('methodologic'),
                    'social'                => $this->request->getPost('social'),
                    'personal'              => $this->request->getPost('personal'),
                    'fk_competence_domain'  => $this->request->getPost('competence_domain')
                );

                // Query to perform
                if (!is_null($operational_comp)) {
                    // Operational competence already exists - updates it
                    $this->operational_comp_model->update($operational_comp_id, $new_operational_comp);
                } else {
                    // No operational competence was found in database - creates a new one
                    $this->operational_comp_model->insert($new_operational_comp);
                }
                // Error handling
                if ($this->operational_comp_model->errors() == null) {
                    // No error - redirects to the competence domain
                    return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $new_operational_comp['fk_competence_domain']));
                }
            }

            // Data to send to the view
            $competenceDomains = [];
            foreach ($this->comp_domain_model->withDeleted()->findAll() as $competenceDomain) {
                $competenceDomains[$this->comp_domain_model->withDeleted()->where('id', $competenceDomain['id'])->first()['id']] = $competenceDomain['name'];
            }

            $output = array(
                'title'                     => lang('plafor_lang.title_operational_competence_' . ((bool)$operational_comp_id ? 'update' : 'new')),
                'operational_competence'    => $operational_comp,
                'competence_domains'        => $competenceDomains,
                'competence_domain'         => $comp_domain,
                'errors'                    => $this->operational_comp_model->errors(),
            );

            return $this->display_view('\Plafor\operational_competence/save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }



    /**
     * Alterate a operational competence depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param integer $operational_competence_id ID of the operational competence to affect.
     *
     * @param integer $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_operational_competence(int $action = null, int $operational_comp_id = 0, bool $confirm = false)
    {
        if (!isCurrentUserAdmin())
            return $this->display_view('\User\errors\403error');

        $operational_comp = $this->operational_comp_model->withDeleted()->find($operational_comp_id);

        if (is_null($operational_comp) || !isset($action))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));

        if(!$confirm)
        {
            $output = array
            (
                'entry' =>
                [
                    'type'    => lang('plafor_lang.operational_competence'),
                    'name'    => $operational_comp['name'],
                ],
                'cancel_btn_url' => base_url('plafor/courseplan/view_competence_domain/'.$operational_comp['fk_competence_domain'])
            );
        }

        switch ($action)
        {
            // Deactivates (soft delete) operational competence
            case 1:
                if(!$confirm)
                {
                    $output['type'] = 'disable';
                    $output['entry']['message'] = lang('plafor_lang.operational_competence_disable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }
                $this->operational_comp_model->delete($operational_comp_id, FALSE);
                break;

            // Reactivates operational competence
            case 3:
                if(!$confirm)
                {
                    $output['type'] = 'reactivate';
                    $output['entry']['message'] = lang('plafor_lang.operational_competence_enable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->operational_comp_model->withDeleted()->update($operational_comp_id, ['archive' => null]);
                break;
        }

        return redirect()->to(base_url('plafor/courseplan/view_competence_domain/'.$operational_comp['fk_competence_domain']));
    }



    /**
     * Alterate an user course depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param integer $user_course_id ID of the user_course to affect.
     *
     * @param integer $action Action to apply on the course plan.
     *      - 2 for deleting (hard delete)
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_user_course(int $action = null, int $user_course_id = 0, bool $confirm = false)
    {
        $user_course = $this->user_course_model->find($user_course_id);
        $apprentice = $this->user_model->withDeleted()->find($user_course['fk_user']);

        if(!isCurrentUserTrainerOfApprentice($apprentice['id']))
            return $this->display_view('\User\errors\403error');

        if (is_null($user_course) || !isset($action))
            return redirect()->to(base_url());

        $course_plan = $this->course_plan_model->withDeleted()->find($user_course['fk_course_plan']);
        $status = $this->user_course_status_model->find($user_course['fk_status']);

        if(!$confirm)
        {
            $output = array
            (
                'entry' =>
                [
                    'type' => sprintf(lang('plafor_lang.course_plan_of'), $apprentice['username']),
                    'name' => $course_plan['official_name'],
                    'data' =>
                    [
                        'user_course_status' =>
                        [
                            'name' => lang('plafor_lang.status'),
                            'value' => $status['name']
                        ]
                    ]
                ],
                'cancel_btn_url' => base_url('plafor/apprentice/list_user_courses/'.$apprentice['id'])
            );
        }

        switch ($action)
        {
            // Deletes user's course and the corresponding comments and acquisition status
            case 2:
                if(!$confirm)
                {
                    $output['type'] = 'delete';
                    $output['entry']['message'] = lang('plafor_lang.user_course_delete_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                // Deletes comments
                foreach($this->acquisition_status_model->where('fk_user_course', $user_course_id)->find() as $acquisition_status)
                    $this->comment_model->where('fk_acquisition_status', $acquisition_status['id'])->delete();

                $this->acquisition_status_model->where('fk_user_course', $user_course_id)->delete();

                // Deletes user's course
                $this->user_course_model->delete($user_course_id);
        }

        return redirect()->to(base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']));
    }



    /**
     * Adds or modifies an objective
     *
     * @param integer $objective_id              : ID of the objective to modify, leave blank to create a new one
     * @param integer $operational_competence_id : ID of the operational competence
     * @return void
     */
    public function save_objective(int $operational_comp_id, int $objective_id = 0)  {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Get datas of given objective and operational competence
            $objective = $this->objective_model->withDeleted()->find($objective_id);
            if (!is_null($objective)) {
                // Given objective has to be modified. Get the operational competence corresponding to it.
                $operational_comp = $this->operational_comp_model->withDeleted()->find($objective['fk_operational_competence']);
            } else {
                // No objective is given, add a new objective for the operational competence given in second parameter
                $operational_comp = $this->operational_comp_model->withDeleted()->find($operational_comp_id);
            }

            // If no objective and no operational competence is given, redirect to courseplan list
            if (is_null($operational_comp)) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                $new_objective = array(
                    'symbol'                    => $this->request->getPost('symbol'),
                    'taxonomy'                  => $this->request->getPost('taxonomy'),
                    'name'                      => $this->request->getPost('name'),
                    'fk_operational_competence' => $this->request->getPost('operational_competence')
                );

                // Query to perform
                if (!is_null($objective)) {
                    // Objective already exists - updates it
                    $this->objective_model->update($objective_id, $new_objective);
                } else {
                    // No objective found in database - inserts a new one
                    $objective_id = $this->objective_model->insert($new_objective);
                    // When we add objective we have to update all students' acquisition status when operational
                    if ($this->objective_model->errors() == null) {
                        $userCourses = $this->course_plan_model->getUserCourses(
                            $this->operational_comp_model->getCompetenceDomain(
                                $this->objective_model->getOperationalCompetence(
                                    $new_objective['fk_operational_competence']
                                )['fk_competence_domain']
                            )['fk_course_plan']
                        );
                        foreach ($userCourses as $userCourse) {
                            $this->acquisition_status_model->insert(['fk_objective'=>$objective_id,'fk_user_course'=>$userCourse['id'],'fk_acquisition_level'=>1]);
                        }
                    } else {
                        $objective_id = 0;
                    }
                }
                // Error handling
                if ($this->objective_model->errors() == null) {
                    // No error - redirects to the operational competence
                    return redirect()->to(base_url('plafor/courseplan/view_operational_competence/' . $operational_comp_id));
                }
            }

            // Data to send to the view
            $operationalCompetences = [];
            foreach ($this->operational_comp_model->findAll() as $operationalCompetence) {
                $operationalCompetences[$operationalCompetence['id']] = $operationalCompetence['name'];
            }
            $output = array(
                'title'                     => lang('plafor_lang.title_objective_' . (is_null($objective) ? 'new' : 'update')),
                'objective'                 => $objective,
                'operational_competences'   => $operationalCompetences,
                'operational_competence_id' => $operational_comp['id'],
                'errors'                    => $this->objective_model->errors(),
            );

            return $this->display_view('\Plafor\objective/save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }



    /**
     * Alterate a objective depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param integer $objective_id ID of the objective to affect.
     *
     * @param integer $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_objective(int $action = null, int $objective_id = 0, bool $confirm = false)
    {
        if (!isCurrentUserAdmin())
            return $this->display_view('\User\errors\403error');

        $objective = $this->objective_model->withDeleted()->find($objective_id);

        if (is_null($objective) || !isset($action))
            return redirect()->to('plafor/courseplan/list_course_plan');

        if(!$confirm)
        {
            $output = array
            (
                'entry' =>
                [
                    'type'    => lang('plafor_lang.objective'),
                    'name'    => $objective['name']
                ],
                'cancel_btn_url' => base_url('plafor/courseplan/view_operational_competence/'.$objective['fk_operational_competence'])
            );
        }

        switch ($action)
        {
            // Deactivates (soft delete) objective
            case 1:
                if(!$confirm)
                {
                    $output['type'] = 'disable';
                    $output['entry']['message'] = lang('plafor_lang.objective_disable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->objective_model->delete($objective_id, FALSE);
                break;

            // Reactivates objective
            case 3:
                if(!$confirm)
                {
                    $output['type'] = 'reactivate';
                    $output['entry']['message'] = lang('plafor_lang.objective_enable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->objective_model->withDeleted()->update($objective_id, ['archive' => null]);
                break;
        }

        return redirect()->to(base_url('plafor/courseplan/view_operational_competence/'.$objective['fk_operational_competence']));
    }



    /**
     * Displays the list of course plans
     *
     * @param int $id_apprentice : ID of the apprentice
     * @param boolean $with_archived : Whether or not to include archived course plans
     * @return void
     */
    public function list_course_plan($id_apprentice = 0, $with_archived = false) {
        // Checks if the soft deleted course plans should be displayed
        $with_archived = $this->request->getGet('wa') ?? false;

        // Gets data of the user if it exists and is an apprentice
        $user_type_id = $this->user_type_model->
            where('access_level', config('\User\Config\UserConfig')->access_level_apprentice)->first()['id'];
        $apprentice = $this->user_model->where('fk_user_type', $user_type_id)->withDeleted()->find($id_apprentice);

        // Gets data of the course plans depending on whether an apprentice is selected or not
        if (is_null($apprentice)) {
            // Apprentice is selected
            $course_plans = $this->course_plan_model->withDeleted($with_archived)->findAll();
        } else {
            // No apprentice is selected
            $userCourses = $this->user_course_model->getWhere(['fk_user'=>$id_apprentice])->getResult();
            $coursesId = array();

            foreach ($userCourses as $userCourse){
                $coursesId[] = $userCourse->fk_course_plan;
            }

            // $course_plans = $this->course_plan_model->get_many($coursesId);
            $course_plans = $this->course_plan_model->whereIn('id',count($coursesId)==0?[null]:$coursesId)->findAll();
        }

        // Data to send to the view
        $output = array(
            'title'         => lang('plafor_lang.title_list_course_plan'),
            'course_plans'  => $course_plans,
            'with_archived' => $with_archived
        );

        if (is_numeric($id_apprentice)) {
            $output[] = ['course_plans' => $course_plans];
        }

        return $this->display_view(['Plafor\course_plan\list'], $output);
    }



    /**
     * Shows details of the selected course plan
     *
     * @param int (SQL PRIMARY KEY) $course_plan_id : ID of the selected course plan
     * @return void
     */
    public function view_course_plan($course_plan_id = 0) {
        $with_archived = $this->request->getGet('wa') ?? false;

        // Gets data of the course plan if it exists
        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

        // Redirection
        if (is_null($course_plan)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        $competence_domains = $this->course_plan_model->getCompetenceDomains($course_plan_id, $with_archived);

        // Format date
        $date_begin = Time::createFromFormat('Y-m-d', $course_plan['date_begin']);
        $course_plan['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');

        $teaching_domains = [];

        // Get teaching domains, subjects and modules
        foreach ($this->m_teaching_domain_model->where("fk_course_plan", $course_plan_id)->findAll() as $domain) {
            $teaching_subject = [];

            // Get teaching subjects of the domain
            foreach ($this->m_teaching_subject_model->where("fk_teaching_domain", $domain["id"])->findAll() as $subject){
                $teaching_subject[] = [
                    "id"            => $subject["id"],               // ID of the subject. Required.
                    "name"          => $subject["name"],             // Name of the subject. Required.
                    "weighting"     => $subject["subject_weight"],   // Weighing of the subject (in the domain average). Required.
                ];
            }

            $teaching_module = [];
            // Get teaching modules of the domain
                foreach ($this->m_teaching_module_model->getByTeachingDomainId($domain["id"]) as $module){
                $teaching_module [] = [
                    "id"            => $module["id"],
                    "number"        => $module["module_number"],
                    "title"         => $module["official_name"],
                ];
            }

            // Sort the array by modules numbers
            array_multisort(array_column($teaching_module, "number"), SORT_ASC, $teaching_module);

            $teaching_domains[] = [
                "id"                => $domain["id"],               // ID of the domain. Required.
                "name"              => $domain["title"],            // Name of the domain. Required.
                "weighting"         => $domain["domain_weight"],    // Weighting of the domain (in CFC average). Required.
                "is_eliminatory"    => $domain["is_eliminatory"],   // Determines if a domain is eliminatory. Required.
                "subjects"          => $teaching_subject,
                "modules"           => $teaching_module,
            ];
        }

        // Data to send to the view
        $output = array(
            'title'                 => lang('plafor_lang.title_view_course_plan'),
            'course_plan'           => $course_plan,
            'competence_domains'    => $competence_domains,
            "teaching_domains"      => $teaching_domains,
            "parent_course_plan_id" => $course_plan_id,
        );

        return $this->display_view('\Plafor\course_plan\view', $output);
    }



    /**
     * Shows details of the selected competence domain
     *
     * @param int (SQL PRIMARY KEY) $competence_domain_id : ID of the selected competence domain
     * @return void
     */
    public function view_competence_domain($comp_domain_id = 0) {
        // Gets data of the competence domain if it exists
        $comp_domain = $this->comp_domain_model->withDeleted()->find($comp_domain_id);

        // Redirection
        if (is_null($comp_domain)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        $with_archived = $this->request->getGet('wa') ?? false;
        $course_plan = $this->comp_domain_model->getCoursePlan($comp_domain['fk_course_plan'], true);

        // Format date
        $date_begin = Time::createFromFormat('Y-m-d', $course_plan['date_begin']);
        $course_plan['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');

        // Data to send to the view
        $output = array(
            'title'             => lang('plafor_lang.title_view_competence_domain'),
            'course_plan'       => $course_plan,
            'competence_domain' => $comp_domain,
            'with_archived'     => $with_archived,
        );

        return $this->display_view('\Plafor/competence_domain/view',$output);
    }



    /**
     * Shows details of the selected operational competence
     *
     * @param int $operational_competence_id : ID of the selected operational competence
     * @return void
     */
    public function view_operational_competence($operational_comp_id = 0) {
        // Gets data of the operational competence if it exists
        $operational_comp = $this->operational_comp_model->withDeleted(true)->find($operational_comp_id);

        // Redirection
        if (is_null($operational_comp)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan/'));
        }

        $with_archived = $this->request->getGet('wa') ?? false;
        $comp_domain = $this->comp_domain_model->withDeleted()->find($operational_comp['fk_competence_domain']);
        $course_plan = $this->course_plan_model->withDeleted()->find($comp_domain['fk_course_plan']);

        //try {
        //    $comp_domain = $this->operational_comp_model->getCompetenceDomain($operational_comp['fk_competence_domain']);
        //    $course_plan = $this->comp_domain_model->getCoursePlan($comp_domain['fk_course_plan']);
        //} catch (Exception $exception) {
        //    // ?
        //}

        $objectives = $this->operational_comp_model->getObjectives($operational_comp['id'], $with_archived);

        // Data to send to the view
        $output = array(
            'title'                     => lang('plafor_lang.title_view_operational_competence'),
            'operational_competence'    => $operational_comp,
            'competence_domain'         => $comp_domain,
            'course_plan'               => $course_plan,
            'objectives'                => $objectives
        );

        return $this->display_view('\Plafor/operational_competence/view',$output);
    }



    /**
     * Shows details of the selected objective
     * @param int $objective_id : ID of the selected objective
     * @return void
     */
    public function view_objective($objective_id = 0) {
        // Gets data of the objective if it exists
        $objective = $this->objective_model->withDeleted()->find($objective_id);

        // Redirection
        if (is_null($objective)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        // Gets data of operational competence and competence domain
        $operational_comp = $this->objective_model->getOperationalCompetence($objective['fk_operational_competence'], true);
        $comp_domain = $this->operational_comp_model->getCompetenceDomain($operational_comp['fk_competence_domain']);
        $course_plan = null;

        if (!is_null($comp_domain)) {
            $course_plan = $this->comp_domain_model->getCoursePlan($comp_domain['fk_course_plan']);
        }

        // Data to send to the view
        $output = array(
            'title'                     => lang('plafor_lang.title_view_objective'),
            'objective'                 => $objective,
            'operational_competence'    => $operational_comp,
            'competence_domain'         => $comp_domain,
            'course_plan'               => $course_plan
        );

        return $this->display_view('Plafor\objective/view',$output);
    }
}
