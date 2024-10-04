<?php
/**
 * Course plans management controller
 *
 * Access level required : logged.
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

namespace Plafor\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use \Psr\Log\LoggerInterface;

use CodeIgniter\HTTP\RedirectResponse;

use CodeIgniter\I18n\Time;

class CoursePlan extends \App\Controllers\BaseController
{
    // Class Constant
    const m_ERROR_MISSING_PERMISSIONS = "\User/errors/403error";

    /**
     * Initializes controller attributes.
     *
     * @param RequestInterface $request
     *
     * @param ResponseInterface $response
     *
     * @param LoggerInterface $logger
     *
     * @return void
     *
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        $this->access_level = "@";

        parent::initController($request, $response, $logger);

        $this->acquisition_status_model = model('AcquisitionStatusModel');
        $this->comment_model            = model('CommentModel');
        $this->comp_domain_model        = model('CompetenceDomainModel');
        $this->course_plan_model        = model('CoursePlanModel');
        $this->objective_model          = model('ObjectiveModel');
        $this->operational_comp_model   = model('OperationalCompetenceModel');
        $this->m_teaching_domain_model  = model("TeachingDomainModel");
        $this->m_teaching_module_model  = model("TeachingModuleModel");
        $this->m_teaching_subject_model = model("TeachingSubjectModel");
        $this->trainer_apprentice_model = model('TrainerApprenticeModel');
        $this->user_model               = model('User_model');
        $this->user_type_model          = model('User_type_model');
        $this->user_course_model        = model('UserCourseModel');
        $this->user_course_status_model = model('UserCourseStatusModel');

        helper("AccessPermissions_helper");
    }



    /**
     * Displays the list of all course plans.
     *
     * @return string
     *
     */
    public function list_course_plan(): string
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $with_archived = $this->request->getGet('wa') ?? false;

        $course_plans = $this->course_plan_model->withDeleted($with_archived)->findAll();

        foreach($course_plans as &$course_plan)
        {
            $course_plan =
            [
                'id'         => $course_plan['id'],
                'formNumber' => $course_plan['formation_number'],
                'coursePlan' => $course_plan['official_name'],
                'begin_date' => Time::createFromFormat('Y-m-d', $course_plan['date_begin'])->toLocalizedString('dd.MM.Y'),
                'archive'    => $course_plan['archive']
            ];
        }

        $output = array
        (
            'course_plans'  => $course_plans,
            'with_archived' => $with_archived
        );

        return $this->display_view('\Plafor\course_plan\list', $output);
    }



    /**
     * Shows the details of a course plan, its linked
     * competences domains and teaching domains, and also subjects
     * and modules linked to each teaching domain.
     *
     * @param int $course_plan_id ID of the course plan.
     *
     * @return string|RedirectResponse
     *
     */
    public function view_course_plan(int $course_plan_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $with_archived = $this->request->getGet('wa') ?? false;

        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

        if(is_null($course_plan))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));

        $course_plan['date_begin'] = Time::createFromFormat('Y-m-d', $course_plan['date_begin'])
            ->toLocalizedString('dd.MM.Y');

        $competence_domains = $this->course_plan_model->getCompetenceDomains($course_plan_id, $with_archived);

        $teaching_domains = [];

        // Get teaching domains, subjects and modules
        foreach ($this->m_teaching_domain_model->where("fk_course_plan", $course_plan_id)->findAll() as $domain)
        {
            $teaching_subjects = [];
            $teaching_modules = [];

            // Get teaching subjects of the domain
            foreach ($this->m_teaching_subject_model->where("fk_teaching_domain", $domain["id"])->findAll() as $subject)
            {
                $teaching_subjects[] =
                [
                    "id"        => $subject["id"],
                    "name"      => $subject["name"],
                    "weighting" => $subject["subject_weight"],
                ];
            }

            // Get teaching modules of the domain
            foreach ($this->m_teaching_module_model->getByTeachingDomainId($domain["id"]) as $module)
            {
                $teaching_modules[] =
                [
                    "id"     => $module["id"],
                    "number" => $module["module_number"],
                    "title"  => $module["official_name"],
                ];
            }

            // Sort the array by modules numbers
            array_multisort(array_column($teaching_modules, "number"), SORT_ASC, $teaching_modules);

            $teaching_domains[] =
            [
                "id"             => $domain["id"],
                "name"           => $domain["title"],
                "weighting"      => $domain["domain_weight"],
                "is_eliminatory" => $domain["is_eliminatory"],
                "subjects"       => $teaching_subjects,
                "modules"        => $teaching_modules,
            ];
        }

        // Data to send to the view
        $output = array
        (
            "course_plan"        => $course_plan,
            "competence_domains" => $competence_domains,
            "teaching_domains"   => $teaching_domains
        );

        return $this->display_view('\Plafor\course_plan\view', $output);
    }



    /**
     * Adds or updates a course plan.
     *
     * @param int $course_plan_id ID of the course plan.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_course_plan(int $course_plan_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

        if(is_null($course_plan))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));

        if(count($_POST) > 0)
        {
            $new_course_plan = array
            (
                'id'                => $course_plan_id,
                'formation_number'  => $this->request->getPost('formation_number'),
                'official_name'     => $this->request->getPost('official_name'),
                'date_begin'        => $this->request->getPost('date_begin'),
            );

            $this->course_plan_model->save($new_course_plan);

            if(empty($this->course_plan_model->errors()))
                return redirect()->to(base_url('/plafor/courseplan/list_course_plan'));
        }

        $output = array
        (
            'title'         => lang('plafor_lang.title_course_plan_'.(!is_null($course_plan) ? 'update' : 'new')),
            'course_plan'   => $new_course_plan ?? $course_plan,
            'errors'        => $this->course_plan_model->errors(),
        );

        return $this->display_view('\Plafor\course_plan\save', $output);
    }



    /**
     * Alterate a course plan depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @param int $course_plan_id ID of the course_plan to affect.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_course_plan(int|null $action = null, int $course_plan_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

        if(is_null($course_plan) || !isset($action))
            return redirect()->to('/plafor/courseplan/list_course_plan');

        if(!$confirm)
        {
            $courses = $this->course_plan_model->getUserCourses($course_plan['id']);
            $apprentices = [];

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
     * Shows the details of a competence domain.
     *
     * @param int $competence_domain_id ID of the competence domain.
     *
     * @return string|RedirectResponse
     *
     */
    public function view_competence_domain(int $competence_domain_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

        if(is_null($competence_domain))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));

        $course_plan = $this->comp_domain_model->getCoursePlan($competence_domain['fk_course_plan'], true);

        $course_plan['date_begin'] = Time::createFromFormat('Y-m-d', $course_plan['date_begin'])
            ->toLocalizedString('dd.MM.Y');

        $with_archived = $this->request->getGet('wa') ?? false;

        $operational_competences = $this->comp_domain_model->getOperationalCompetences($competence_domain['id'], $with_archived);

        $output = array
        (
            'course_plan'             => $course_plan,
            'competence_domain'       => $competence_domain,
            'operational_competences' => $operational_competences,
            'with_archived'           => $with_archived,
        );

        return $this->display_view('\Plafor/competence_domain/view',$output);
    }



    /**
     * Adds or updates a competence domain.
     *
     * @param int $course_plan_id ID of the parent course plan.
     *
     * @param int $competence_domain_id ID of the competence domain.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_competence_domain(int $course_plan_id = 0, int $competence_domain_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);
        $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

        if(is_null($course_plan)
            || !is_null($competence_domain)
            && $competence_domain['fk_course_plan'] != $course_plan_id)
        {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        if(count($_POST) > 0)
        {
            $new_competence_domain = array
            (
                'id'                => $competence_domain_id,
                'fk_course_plan'    => $this->request->getPost('course_plan'),
                'symbol'            => $this->request->getPost('symbol'),
                'name'              => $this->request->getPost('name')
            );

            $this->comp_domain_model->save($new_competence_domain);

            if(empty($this->comp_domain_model->errors()))
                return redirect()->to(base_url('plafor/courseplan/view_course_plan/'.$course_plan_id));
        }

        $course_plans = [];

        foreach ($this->course_plan_model->withDeleted()->findAll() as $course_plan)
            $course_plans[$course_plan["id"]] = $course_plan['official_name'];

        $output = array
        (
            'title'                 => lang('plafor_lang.title_competence_domain_'.
                (!is_null($competence_domain) ? 'update' : 'new')),
            'competence_domain'     => $competence_domain,
            'course_plans'          => $course_plans,
            'parent_course_plan_id' => $course_plan_id,
            'errors'                => $this->comp_domain_model->errors(),
        );

        return $this->display_view('\Plafor\competence_domain/save', $output);
    }



    /**
     * Alterate a competence domain depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @param int $competence_domain_id ID of the competence domain to affect.
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_competence_domain(int|null $action = null, int $competence_domain_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

        if(is_null($competence_domain) || !isset($action))
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
                'cancel_btn_url' => base_url('plafor/courseplan/view_course_plan/'.$competence_domain["fk_course_plan"])
            );
        }

        switch($action)
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

        return redirect()->to(base_url('plafor/courseplan/view_course_plan/'.$competence_domain['fk_course_plan']));
    }



    /**
     * Shows the details of a operational competence.
     *
     * @param int $operational_competence_id ID of the operational competence.
     *
     * @return string|RedirectResponse
     *
     */
    public function view_operational_competence(int $operational_competence_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $operational_competence = $this->operational_comp_model->withDeleted()->find($operational_competence_id);

        if(is_null($operational_competence))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan/'));

        $competence_domain = $this->comp_domain_model->withDeleted()->find($operational_competence['fk_competence_domain']);
        $course_plan       = $this->course_plan_model->withDeleted()->find($competence_domain['fk_course_plan']);

        $with_archived = $this->request->getGet('wa') ?? false;
        $objectives    = $this->operational_comp_model->getObjectives($operational_competence['id'], $with_archived);

        $output = array
        (
            'course_plan'               => $course_plan,
            'competence_domain'         => $competence_domain,
            'operational_competence'    => $operational_competence,
            'objectives'                => $objectives
        );

        return $this->display_view('\Plafor/operational_competence/view', $output);
    }



    /**
     * Adds or updates an operational competence.
     *
     * @param int $competence_domain_id ID of the competence domain.
     *
     * @param int $operational_competence_id ID of the operational competence.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_operational_competence(int $competence_domain_id = 0, int $operational_competence_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);
        $operational_competence = $this->operational_comp_model->withDeleted()->find($operational_competence_id);

        if(is_null($competence_domain)
            || !is_null($operational_competence)
            && $operational_competence['fk_competence_domain'] != $competence_domain_id)
        {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        if(count($_POST) > 0)
        {
            $new_operational_comp = array
            (
                'id'                    => $operational_competence_id,
                'fk_competence_domain'  => $this->request->getPost('competence_domain'),
                'name'                  => $this->request->getPost('name'),
                'symbol'                => $this->request->getPost('symbol'),
                'methodologic'          => $this->request->getPost('methodologic'),
                'social'                => $this->request->getPost('social'),
                'personal'              => $this->request->getPost('personal')
            );

            $this->operational_comp_model->save($new_operational_comp);

            if(empty($this->operational_comp_model->errors()))
                return redirect()->to(base_url('plafor/courseplan/view_competence_domain/'.$competence_domain_id));
        }

        $competence_domains = [];

        foreach ($this->comp_domain_model->withDeleted()->findAll() as $competence_domain)
            $competence_domains[$competence_domain['id']] = $competence_domain['name'];

        $output = array
        (
            'title'                  => lang('plafor_lang.title_operational_competence_'.
                (!is_null($operational_competence) ? 'update': 'new')),
            'operational_competence' => $operational_competence,
            'competence_domains'     => $competence_domains,
            'competence_domain_id'   => $competence_domain_id,
            'errors'                 =>  $this->operational_comp_model->errors(),
        );

        return $this->display_view('\Plafor\operational_competence/save', $output);
    }



    /**
     * Alterate a operational competence depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @param int $operational_competence_id ID of the operational competence to affect.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_operational_competence(int|null $action = null, int $operational_competence_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $operational_competence = $this->operational_comp_model->withDeleted()->find($operational_competence_id);

        if(is_null($operational_competence) || !isset($action))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));

        if(!$confirm)
        {
            $output = array
            (
                'entry' =>
                [
                    'type'    => lang('plafor_lang.operational_competence'),
                    'name'    => $operational_competence['name'],
                ],
                'cancel_btn_url' => base_url('plafor/courseplan/view_competence_domain/'.
                    $operational_competence['fk_competence_domain'])
            );
        }

        switch($action)
        {
            // Deactivates (soft delete) operational competence
            case 1:
                if(!$confirm)
                {
                    $output['type'] = 'disable';
                    $output['entry']['message'] = lang('plafor_lang.operational_competence_disable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }
                $this->operational_comp_model->delete($operational_competence_id, FALSE);
                break;

            // Reactivates operational competence
            case 3:
                if(!$confirm)
                {
                    $output['type'] = 'reactivate';
                    $output['entry']['message'] = lang('plafor_lang.operational_competence_enable_explanation');

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->operational_comp_model->withDeleted()->update($operational_competence_id, ['archive' => null]);
                break;
        }

        return redirect()->to(base_url('plafor/courseplan/view_competence_domain/'.
            $operational_competence['fk_competence_domain']));
    }



    /**
     * Shows the details of an objective.
     *
     * @param int $objective_id ID of the objective.
     *
     * @return string|RedirectResponse
     *
     */
    public function view_objective(int $objective_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $objective              = $this->objective_model->withDeleted()->find($objective_id);
        $operational_competence = $this->objective_model->getOperationalCompetence($objective['fk_operational_competence']);
        $competence_domain      = $this->operational_comp_model->getCompetenceDomain($operational_competence['fk_competence_domain']);
        $course_plan            = $this->comp_domain_model->getCoursePlan($competence_domain['fk_course_plan']);;

        if(is_null($objective)
            || is_null($operational_competence)
            || is_null($competence_domain)
            || is_null($course_plan))
        {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        $output = array
        (
            'course_plan'               => $course_plan,
            'competence_domain'         => $competence_domain,
            'operational_competence'    => $operational_competence,
            'objective'                 => $objective
        );

        return $this->display_view('Plafor\objective/view', $output);
    }



    /**
     * Adds or updates an objective.
     *
     * @param int $operational_competence_id ID of the operational competence.
     *
     * @param int $objective_id ID of the objective.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_objective(int $operational_competence_id = 0, int $objective_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $objective = $this->objective_model->withDeleted()->find($objective_id);
        $operational_competence = $this->operational_comp_model->withDeleted()->find($operational_competence_id);

        if(is_null($operational_competence))
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));

        if(count($_POST) > 0)
        {
            $new_objective = array
            (
                'id'                        => $objective_id,
                'symbol'                    => $this->request->getPost('symbol'),
                'taxonomy'                  => $this->request->getPost('taxonomy'),
                'name'                      => $this->request->getPost('name'),
                'fk_operational_competence' => $this->request->getPost('operational_competence')
            );

            $this->objective_model->save($new_objective);

            if(empty($this->objective_model->errors()))
            {
                $user_courses = $this->course_plan_model->getUserCourses(
                    $this->operational_comp_model->getCompetenceDomain(
                        $this->objective_model->getOperationalCompetence(
                            $new_objective['fk_operational_competence']
                        )['fk_competence_domain']
                    )['fk_course_plan']
                );

                foreach ($user_courses as $user_course)
                    $this->acquisition_status_model->insert(
                    [
                        'fk_objective'         => $objective_id,
                        'fk_user_course'       => $user_course['id'],
                        'fk_acquisition_level' => 1
                    ]);
            }

            return redirect()->to(base_url('plafor/courseplan/view_operational_competence/'.$operational_competence_id));
        }

        $operational_competences = [];

        foreach ($this->operational_comp_model->withDeleted()->findAll() as $operational_competence)
            $operational_competences[$operational_competence['id']] = $operational_competence['name'];

        $output = array
        (
            'title'                     => lang('plafor_lang.title_objective_' . (is_null($objective) ? 'new' : 'update')),
            'objective'                 => $objective,
            'operational_competences'   => $operational_competences,
            'operational_competence_id' => $operational_competence_id,
            'errors'                    => $this->objective_model->errors(),
        );

        return $this->display_view('\Plafor\objective/save', $output);
    }



    /**
     * Alterate a objective depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the course plan.
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     *
     * @param int $objective_id ID of the objective to affect.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_objective(int|null $action = null, int $objective_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $objective = $this->objective_model->withDeleted()->find($objective_id);

        if(is_null($objective) || !isset($action))
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

        switch($action)
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
}