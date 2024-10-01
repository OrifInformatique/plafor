<?php

/**
 * Apprentices management controller.
 *
 * Access level required : none.
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

use Exception;

class Apprentice extends \App\Controllers\BaseController
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
        $this->access_level = "*";

        parent::initController($request, $response, $logger);

        $this->acquisition_lvl_model    = model('AcquisitionLevelModel');
        $this->acquisition_status_model = model('AcquisitionStatusModel');
        $this->comment_model            = model('CommentModel');
        $this->comp_domain_model        = model('CompetenceDomainModel');
        $this->course_plan_model        = model('CoursePlanModel');
        $this->m_grade_model            = model("GradeModel");
        $this->objective_model          = model('ObjectiveModel');
        $this->operational_comp_model   = model('OperationalCompetenceModel');
        $this->trainer_apprentice_model = model('TrainerApprenticeModel');
        $this->user_course_model        = model('UserCourseModel');
        $this->user_course_status_model = model('UserCourseStatusModel');
        $this->user_model               = model('User_model');
        $this->user_type_model          = model('User_type_model');

        helper("AccessPermissions_helper");
    }







    /**
     * Redirects to a homepage depending on the type of user
     *
     * @return RedirectResponse
     *
     */
    public function index(): RedirectResponse
    {
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
        {
            // Session is set
            // Redirect to a specific page, depending on the type of user
            if(isCurrentUserAdmin())
                // User is a adminstrator
                return redirect()->to(base_url('user/admin/list_user'));

            elseif(isCurrentUserTrainer())
                // User is a trainer
                return redirect()->to(base_url('plafor/apprentice/list_apprentice?trainer_id='.$_SESSION['user_id']));

            else
                // User is an apprentice
                return redirect()->to(base_url('plafor/apprentice/view_apprentice/'.$_SESSION['user_id']));
        }

        else
            // No session is set
            return redirect()->to(base_url('user/auth/login'));
    }



    /**
     * Displays the list of all apprentices.
     *
     * @param bool $with_archived Defines whether to show deleted apprentices.
     *
     * @return string
     *
     */
    public function list_apprentice(bool $with_archived = false): string
    {
        // Gets trainer information if they are connected
        $trainer_id = $this->request->getGet('trainer_id');

        if(isCurrentUserTrainer() && $trainer_id == null)
            $trainer_id = $this->session->get('user_id');

        // Gets username of all trainers for the dropdown menu
        $trainersList = array();
        $trainersList[0] = lang('common_lang.all_m');
        $trainersList[1] = lang('plafor_lang.unassigned');

        foreach ($this->user_model->getTrainers() as $trainer)
            $trainersList[$trainer['id']] = $trainer['username'];

        $apprentices = array();

        // Get data of apprentices, depending on the logged-in user
        if($trainer_id == null || $trainer_id == 0)
        {
            // User is not a trainer - lists all apprentices
            $apprentices = $this->user_model->getApprentices($with_archived);
        }

        else if($trainer_id == 1)
        {
            $apprentices = $this->trainer_apprentice_model->getUnassignedApprentices();
        }

        else
        {
            // User is a trainer - lists their linked apprentices
            $trainer_apprentice = $this->trainer_apprentice_model->where('fk_trainer', $trainer_id)->findAll();

            if(!empty($trainer_apprentice))
                $apprentices = $this->user_model
                    ->whereIn('id', array_column($trainer_apprentice, 'fk_apprentice'))
                    ->orderBy('username', 'ASC')
                    ->findAll();
        }

        $coursesList = array();

        foreach ($this->course_plan_model->withDeleted()->findAll() as $courseplan)
            $coursesList[$courseplan['id']]=$courseplan;

        $courses = $this->user_course_model->withDeleted()->findAll();

        $output = array
        (
            'trainer_id'    => $trainer_id,
            'trainers'      => $trainersList,
            'apprentices'   => $apprentices,
            'coursesList'   => $coursesList,
            'courses'       => $courses,
            'with_archived' => $with_archived
        );

        return $this->display_view('\Plafor\apprentice\list', $output);
    }



    /**
     * Displays the details of an apprentice.
     *
     * @param int $apprentice_id ID of the apprentice (default = 0)
     * // TODO: Get the user_course inside the URL
     * // TODO: Change the user_course ID whith the drop down menu (JS)
     *
     * @return string
     *
     */
    public function view_apprentice(int $apprentice_id = 0): string
    {
        if(!isCurrentUserTrainerOfApprentice($apprentice_id)
            && !isCurrentUserSelfApprentice($apprentice_id))
        {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $apprentice = $this->user_model->find($apprentice_id);

        $list_user_courses = [];
        foreach ($this->user_course_model->where('fk_user', $apprentice_id)->findAll() as $user_course) {

            $date_begin = Time::createFromFormat('Y-m-d', $user_course['date_begin']);
            $date_end   = Time::createFromFormat('Y-m-d', $user_course['date_end']);

            $user_course['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');
            $user_course['date_end'] !== '0000-00-00' ? $user_course['date_end'] = $date_end->toLocalizedString('dd.MM.Y') : null;

            $list_user_courses[$user_course['id']] = $user_course;
        }

        $list_user_course_status = [];
        foreach ($this->user_course_status_model->withDeleted()->findAll() as $user_course_status)
            $list_user_course_status[$user_course_status['id']] = $user_course_status;

        $list_course_plans = [];
        foreach ($this->course_plan_model->withDeleted()->findAll() as $courseplan)
            $list_course_plans[$courseplan['id']] = $courseplan;

        $trainers = [];
        foreach ($this->user_model
            ->where('fk_user_type', $this->user_type_model->where('name', lang('plafor_lang.title_trainer'))->first()['id'])
            ->withDeleted()
            ->findAll() as $trainer)
        {
            $trainers[$trainer['id']] = $trainer;
        }

        $links = [];
        foreach ($this->trainer_apprentice_model->where('fk_apprentice', $apprentice_id)->findAll() as $link)
            $links[$link['id']] = $link;

        // TODO: add school_report here
        $school_report_data = [];

        $cfc_average;                       // Average of all domains of the apprentice, rounded by '0.1'.
        $modules = [];                      // All modules teached to the apprentice.
        // d($this->m_grade_model->getApprenticeModulesGrades($list_user_courses[$user_course['id']], null));
        // foreach ($this->m_grade_model->where("fk_user_course", $list_user_courses[$user_course['id']])->findAll() as $grade_module)
        // $modules = [
        //     "school" => [                   // All school modules teached to the apprentice. Required.
        //         "modules" => [              // List of school modules teached to the apprentice. Required.
        //             // "number" => int,        // Number of the module. Required.
        //             // "name"   => string,     // Name of the module. Required.
        //             // "grade"  =>  [          // Grade obtained by the apprentice to the module. Can be empty.
        //             //     "id"    => int,     // ID of the grade. Required.
        //             //     "value" => float,   // Value of the grade. Required.
        //             // ]
        //         ],
        //         // "weighting" => float,       // Weighting of school modules. Required.
        //         // "average"   => float,       // Average of school modules. Can be empty.
        //     ],
        //     "non-school" => [               // All non-school modules teached to the apprentice. Required.
        //         "modules" => [              // List of school modules teached to the apprentice. Required.
        //             // "number" => int,        // Number of the module. Required.
        //             // "name"   => string,     // Name of the module. Required.
        //             // "grade"  =>  [          // Grade obtained by the apprentice to the module. Can be empty.
        //             //     "id"    => int,     // ID of the grade. Required.
        //             //     "value" => float,   // Value of the grade. Required.
        //             // ]
        //         ],                          // List of non-school module teached to an apprentice.
        //         // "weighting" => float,       // Weighting of non-school modules. Required.
        //         // "average"   => float,       // Average of non-school modules. Can be empty.
        //     ],
        //     //  "weighting" => float,          // Weighting of modules (in CFC average). Required.
        //     //  "average" => float,            // Average of school (80%) and non-school (20%) averages. Can be empty.
        // ];

        $data_to_view = [
            "title"                 => lang("plafor_lang.title_view_apprentice"),
            "apprentice"            => $apprentice,
            "trainers"              => $trainers,
            "links"                 => $links,
            "user_courses"          => $list_user_courses,
            "user_course_status"    => $list_user_course_status,
            "course_plans"          => $list_course_plans,
            "school_report_data"    => $school_report_data, // TODO: Add the arrays below inside this one

            // "cfc_average"           => $cfc_average,// TODO
            // "modules"               => $modules,    // TODO
            // "tpi_grade"             => $tpi_grade,  // TODO
            // "cbe"                   => $cbe,        // TODO
            // "ecg"                   => $ecg,        // TODO
        ];

        return $this->display_view("Plafor\apprentice/view", $data_to_view);
    }



    /**
     * Display the list of all course plans followed by the apprentice
     * (all user courses linked to the apprentice).
     *
     * @param int $apprentice_id ID of the apprentice.
     *
     * @return string
     *
     */
    public function list_user_courses(int $apprentice_id = null): string
    {
        if(is_numeric($apprentice_id))
            $apprentice = $this->user_model->where('id', $apprentice_id)->first();

        if(!isset($apprentice) || empty($apprentice))
            return redirect()->to('plafor/apprentice/list_apprentice');

        $user_courses = $this->user_course_model->where('fk_user', $apprentice_id)->findAll();

        // Get the course plan informations for each user course
        foreach($user_courses as &$user_course)
        {
            $user_course['course_plan'] = $this->course_plan_model
                ->withDeleted()
                ->find($user_course['fk_course_plan']);

            $user_course['status'] = $this->user_course_status_model
                ->getUserCourseStatusName($user_course['fk_status']);

            if($user_course['date_end'] === '0000-00-00')
                $user_course['date_end'] = null;
        }

        $output = array
        (
            'title'         => sprintf(lang('plafor_lang.title_user_course_plan_list'), $apprentice['username']),
            'user_courses'  => $user_courses,
            'id_apprentice' => $apprentice_id
        );

        return $this->display_view('Plafor\user_course\list', $output);
    }



    /**
     * Shows the details of a user's course :
     * shows the details of the followed course plan, and
     * the acquisition statuses of all objectives in a specific operational competence.
     *
     * @param int $user_course_id ID of the user course.
     *
     * @return string|RedirectResponse
     *
     */
    public function view_user_course(int $user_course_id = 0): string|RedirectResponse
    {
        $user_course = $this->user_course_model->find($user_course_id);

        if($user_course == null)
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        $apprentice = $this->user_model->find($user_course['fk_user']);

        if(!isCurrentUserSelfApprentice($apprentice["id"])
            && !hasCurrentUserTrainerAccess())
            return redirect()->to(base_url('plafor/apprentice/view_apprentice/'.$apprentice["id"]));

        $user_course_status  = $this->user_course_model->getUserCourseStatus($user_course['fk_status']);
        $course_plan         = $this->user_course_model->getCoursePlan($user_course['fk_course_plan'], true);
        $trainers_apprentice = $this->trainer_apprentice_model->where('fk_apprentice',$apprentice['id'])->findAll();

        $operational_competence_id = $this->request->getGet('operationalCompetenceId');

        if($operational_competence_id != null)
        {
            $objectives = [];
            $acquisition_statuses = [];

            foreach ($this->course_plan_model
                ->getCompetenceDomains($this->user_course_model->find($user_course_id)['fk_course_plan']) as $competence_domain)
            {
                foreach ($this->comp_domain_model->getOperationalCompetences($competence_domain['id']) as $operationalCompetence)
                {
                    if($operationalCompetence['id'] == $operational_competence_id)
                    {
                        foreach ($this->operational_comp_model->getObjectives($operationalCompetence['id']) as $objective)
                            $objectives[$objective['id']] = $objective;
                    }
                }
            }

            foreach ($this->user_course_model->getAcquisitionStatus($user_course_id) as $acquisition_status)
            {
                foreach ($objectives as $objective)
                {
                    if($acquisition_status['fk_objective'] == $objective['id'])
                        $acquisition_statuses[] = $acquisition_status;
                }
            }
        }

        else
        {
            $acquisition_statuses = $this->user_course_model->getAcquisitionStatus($user_course_id);

            foreach ($acquisition_statuses as $acquisition_status)
            {
                $objectives[$acquisition_status['fk_objective']] = $this->acquisition_status_model
                    ->getObjective($acquisition_status['fk_objective']);
            }
        }

        $acquisition_levels = [];

        foreach ($this->acquisition_lvl_model->findAll() as $acquisition_level)
            $acquisition_levels[$acquisition_level['id']] = $acquisition_level;

        // Data to send to the view
        $output = array
        (
            'user_course'           => $user_course,
            'apprentice'            => $apprentice,
            'user_course_status'    => $user_course_status,
            'course_plan'           => $course_plan,
            'trainers_apprentice'   => $trainers_apprentice,
            'acquisition_statuses'  => $acquisition_statuses,
            'acquisition_levels'    => $acquisition_levels,
            'objectives'            => $objectives
        );

        return $this->display_view('\Plafor\user_course/view',$output);
    }



    /**
     * Displays a form to create a link between an apprentice and a course plan.
     *
     * @param int $apprentice_id ID of the apprentice.
     *
     * @param int $user_course_id ID of the user's course.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_user_course(int $apprentice_id = 0, int $user_course_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view('\User\errors\403error');

        $apprentice = $this->user_model->find($apprentice_id);

        $user_course = $this->user_course_model->find($user_course_id);

        if(is_null($apprentice))
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        if(count($_POST) > 0)
        {
            $new_user_course = array
            (
                'fk_user'           => $apprentice_id,
                'fk_course_plan'    => $this->request->getPost('course_plan'),
                'fk_status'         => $this->request->getPost('status'),
                'date_begin'        => $this->request->getPost('date_begin'),
                'date_end'          => $this->request->getPost('date_end'),
            );

            if(!is_null($user_course))
                $this->user_course_model->update($user_course_id, $new_user_course);

            else
            {
                $user_has_course = $this->user_course_model
                    ->where(['fk_user' => $apprentice_id, 'fk_course_plan' => $fk_course_plan])
                    ->findAll() ? true : false;

                // If the apprentice already follows the course plan submitted, prevent the creation of the entry.
                if(!$user_has_course)
                {
                    $user_course_id = $this->user_course_model->insert($new_user_course);

                    $course_plan = $this->user_course_model->getCoursePlan($new_user_course['fk_course_plan']);

                    $competence_domains_ids = [];
                    foreach ($this->course_plan_model->getCompetenceDomains($course_plan['id']) as $competence_domain)
                        $competence_domains_ids[] = $competence_domain['id'];

                    $operational_competences = [];

                    try
                    {
                        $operational_competences = $this->operational_comp_model
                            ->whereIn('fk_competence_domain', $competence_domains_ids)
                            ->withDeleted()
                            ->findAll();
                    }

                    catch (\Exception $e)
                    {
                        // No operational competence associated
                    }

                    $objectives_ids = array();
                    foreach ($operational_competences as $operational_competence)
                    {
                        foreach ($this->operational_comp_model->getObjectives($operational_competence['id']) as $objective)
                            $objectives_ids[] = $objective['id'];
                    }

                    // Adds an acquisition status of level 1 for each objective
                    foreach ($objectives_ids as $objective_id)
                    {
                        $acquisition_status = array(
                            'fk_objective'          => $objective_id,
                            'fk_user_course'        => $user_course_id,
                            'fk_acquisition_level'  => 1
                        );

                        $this->acquisition_status_model->insert($acquisition_status);
                    }
                }
            }

            if($this->user_course_model->errors() == null)
                return redirect()->to(base_url('plafor/apprentice/list_user_courses/' . $apprentice_id));
        }

        $course_plans = [];
        $user_course_statuses = [];

        if($user_course_id == 0)
        {
            // New user courses can only refer to an active course plan
            foreach ($this->course_plan_model->findAll() as $course_plan)
                $course_plans[$course_plan['id']] = $course_plan['official_name'];
        }

        else
        {
            // Existing user courses can refer to an active or a soft deleted course plan
            foreach ($this->course_plan_model->withDeleted()->findAll() as $course_plan)
                $course_plans[$course_plan['id']] = $course_plan['official_name'];
        }

        foreach ($this->user_course_status_model->findAll() as $user_course_status)
            $user_course_statuses[$user_course_status['id']] = $user_course_status['name'];

        $output = array
        (
            'title'                => $apprentice['username'].' - '.
                lang('plafor_lang.title_user_course_'.(!empty($user_course) ? 'update': 'new')),
            'course_plans'         => $course_plans,
            'user_course'          => $user_course,
            'user_course_statuses' => $user_course_statuses,
            'apprentice_id'        => $apprentice["id"],
            'errors'               => $this->user_course_model->errors()
        );

        return $this->display_view('Plafor\user_course/save', $output);
    }



    /**
     * Alterate an user course depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $user_course_id ID of the user_course to affect.
     *
     * @param int $action Action to apply on the course plan.
     *      - 2 for deleting (hard delete)
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_user_course(int $action = null, int $user_course_id = 0, bool $confirm = false): string|RedirectResponse
    {
        $user_course = $this->user_course_model->find($user_course_id);
        $apprentice = $this->user_model->withDeleted()->find($user_course['fk_user']);

        if(!isCurrentUserTrainerOfApprentice($apprentice['id']))
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        if(is_null($user_course) || !isset($action))
            return redirect()->to(base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']));

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

                $this->user_course_model->delete($user_course_id);
        }

        return redirect()->to(base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']));
    }



    /**
     * Creates a link between an apprentice and a trainer, or changes the trainer
     * linked on the selected trainer_apprentice SQL entry
     *
     * @param int $apprentice_id ID of the apprentice.
     *
     * @param int $link_id ID of the link.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_apprentice_link(int $apprentice_id = 0, int $link_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(m_ERROR_MISSING_PERMISSIONS);

        $apprentice = $this->user_model->find($apprentice_id);
        $link = $this->trainer_apprentice_model->find($link_id);

        if(is_null($apprentice)
            || !is_null($link)
            && $link['fk_apprentice'] != $apprentice_id)
        {
            return redirect()->to(base_url("plafor/apprentice/view_apprentice/".$apprentice_id));
        }

        if(count($_POST) > 0)
        {
            $new_link = array
            (
                'id'            => $link_id,
                'fk_trainer'    => $this->request->getPost('trainer'),
                'fk_apprentice' => $this->request->getPost('apprentice'),
            );

            $this->trainer_apprentice_model->save($new_link);


            if($this->trainer_apprentice_model->errors() == null)
                return redirect()->to(base_url("plafor/apprentice/view_apprentice/".$apprentice_id));
        }

        // Gets data of trainers for the dropdown menu BUT ignore the trainers who are
        // already linked to the selected apprentice
        $trainers = $this->user_model->getTrainers();
        $trainers_unlinked_to_apprentice = array();
        $linked_apprentices = array();

        foreach ($trainers as $trainer)
        {
            $linked_apprentices = $this->trainer_apprentice_model->getApprenticeIdsFromTrainer($trainer['id']);

            if(is_null($linked_apprentices) || !in_array($apprentice_id, $linked_apprentices))
                $trainers_unlinked_to_apprentice[$trainer['id']] = $trainer['username'];
        }

        $output = array
        (
            'title'         => lang('plafor_lang.title_apprentice_link_'.(isset($link) ? 'update' : 'new')),
            'apprentice'    => $apprentice,
            'trainers'      => $trainers_unlinked_to_apprentice,
            'link'          => $link,
            'errors'        => $this->trainer_apprentice_model->errors()
        );

        return $this->display_view('Plafor\apprentice/link',$output);
    }





    /**
     * Alterate a trainer_apprentice link depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $link_id ID of the trainer_apprentice_link to affect.
     *
     * @param int $action Action to apply on the trainer_apprentice link.
     *      - 2 for deleting (hard delete)
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_apprentice_link(int $action = null, int $link_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $link = $this->trainer_apprentice_model->find($link_id);

        if(is_null($link) || !isset($action))
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        $apprentice = $this->trainer_apprentice_model->getApprentice($link['fk_apprentice']);
        $trainer    = $this->trainer_apprentice_model->getTrainer($link['fk_trainer']);

        if(!$confirm)
        {
            $output = array
            (
                'entry' =>
                [
                    'type'    => lang('plafor_lang.apprentice_link'),
                    'name'    => '',
                    'message' => lang('plafor_lang.apprentice_link_delete_explanation'),
                    'data'    =>
                    [
                        [
                            'name' => lang('plafor_lang.apprentice'),
                            'value' => $apprentice['username']
                        ],
                        [
                            'name' => lang('plafor_lang.trainer'),
                            'value' => $trainer['username']
                        ]
                    ]
                ],
                'cancel_btn_url' => base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']),
            );
        }

        switch($action)
        {
            case 2:
                if(!$confirm)
                {
                    $output['type'] = 'delete';
                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->trainer_apprentice_model->delete($link_id, TRUE);
                break;
        }

        return redirect()->to(base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']));
    }





    /**
     * Shows the details of the selected acquisition status.
     *
     * @param int $acquisition_status_id ID of the acquisition status to view.
     *
     * @return string|RedirectResponse
     *
     */
    public function view_acquisition_status(int $acquisition_status_id = 0): string|RedirectResponse
    {
        $acquisition_status = $this->acquisition_status_model->find($acquisition_status_id);
        $user_course        = $this->user_course_model->find($acquisition_status['fk_user_course']);
        $apprentice_id      = $this->user_model->find($user_course['fk_user'])["id"];

        if(!isCurrentUserSelfApprentice($apprentice_id)
            && !hasCurrentUserTrainerAccess())
        {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        if(is_null($acquisition_status))
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        $objective         = $this->acquisition_status_model->getObjective($acquisition_status['fk_objective']);
        $acquisition_level = $this->acquisition_status_model->getAcquisitionLevel($acquisition_status['fk_acquisition_level']);
        $comments          = $this->comment_model->where('fk_acquisition_status',$acquisition_status_id)->findAll();
        $trainers          = $this->user_model->getTrainers();

        $output = array
        (
            'acquisition_status'    => $acquisition_status,
            'acquisition_level'     => $acquisition_level,
            'objective'             => $objective,
            'comments'              => $comments,
            'trainers'              => $trainers,
        );

        return $this->display_view('Plafor\acquisition_status/view',$output);
    }



    /**
     * Updates an objective acquisition status for an apprentice.
     *
     * @param int $acquisition_status_id ID of the acquisition status.
     *
     * @return Response|ResponseInterface
     *
     */
    public function save_acquisition_status(int $acquisition_status_id = 0): Response|ResponseInterface
    {
        $acquisition_status = $this->acquisition_status_model->find($acquisition_status_id);

        if(is_null($acquisition_status))
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        if(!empty($_POST))
        {
            $acquisition_level  = $this->request->getPost('field_acquisition_level');
            $acquisition_status = $this->acquisition_status_model->find($acquisition_status_id);

            $acquisition_status['fk_acquisition_level'] = $acquisition_level;

            $objective             = $this->objective_model->find($acquisition_status['fk_objective']);
            $opeational_competence = $this->objective_model->getOperationalCompetence($objective['fk_operational_competence']);

            if(is_null($opeational_competence) || !is_null($opeational_competence['archive']))
            {
                return $this->response
                    ->setContentType('application/json')
                    ->setStatusCode(409)
                    ->setBody(json_encode(['error' => lang('plafor_lang.associated_op_comp_disabled')]));
            }

            else
            {
                $competence_domain = $this->operational_comp_model->getCompetenceDomain($opeational_competence['fk_competence_domain']);

                if(is_null($competence_domain) || !is_null($competence_domain['archive']))
                {
                    return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(409)
                        ->setBody(json_encode(['error' => lang('plafor_lang.associated_comp_dom_disabled')]));
                }
            }

            $this->acquisition_status_model->update($acquisition_status_id, $acquisition_status);

            if(is_null($this->acquisition_status_model->errors()))
                $this->response->setStatusCode(200);
        }
    }



    /**
     * Saves a acquisition status comment.
     *
     * @param int $acquisition_status_id ID of the acquisition status.
     *
     * @param int $comment_id ID of the comment.
     *
     * @return string|RedirectResponse
     *
     */
    public function save_comment(int $acquisition_status_id = 0, int $comment_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $acquisition_status = $this->acquisition_status_model->find($acquisition_status_id);
        $comment            = $this->comment_model->find($comment_id);

        if(is_null($acquisition_status))
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        if(count($_POST) > 0)
        {
            $new_comment = array
            (
                'id'                    => $comment_id,
                'fk_trainer'            => $_SESSION['user_id'],
                'fk_acquisition_status' => $acquisition_status_id,
                'comment'               => $this->request->getPost('comment'),
                'date_creation'         => date('Y-m-d H:i:s'),
            );

            $this->comment_model->save($new_comment);

            if(is_null($this->comment_model->errors()))
                return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status['id']));
        }

        $output = array(
            'title'                 => lang('plafor_lang.title_comment_'.($comment_id > 0 ? 'update' : 'new')),
            'acquisition_status_id' => $acquisition_status["id"],
            'comment_id'            => $comment_id,
            'commentValue'          => $comment['comment'] ?? null,
            'errors'                => $this->comment_model->errors()
        );

        return $this->display_view('\Plafor\comment/save',$output);
    }


    // TODO : Use the common delete view and logic for comments.
    /**
     * Deletes a comment from an acquisition status.
     *
     * @param int $comment_id ID of the comment.
     *
     * @return string|RedirectResponse
     *
     */
    public function delete_comment(int $comment_id = 0): string|RedirectResponse
    {
        if(!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $comment = $this->comment_model->find($comment_id);
        $acquisition_status_id = $comment['fk_acquisition_status'];

        if(is_null($comment))
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

        $this->comment_model->delete($comment_id);

        return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status_id));

    }

    /**
     * Gets the course plan progress of an apprentice.
     *
     * @param int $apprentice_id ID of the apprentice.
     *
     * @param int $course_plan_id ID of the course plan.
     *
     * @return ResponseInterface
     *
     */
    public function getCoursePlanProgress(int $apprentice_id = 0, int $course_plan_id = 0): ResponseInterface
    {
        $data = $this->course_plan_model->getCoursePlanProgress($apprentice_id);

        if($course_plan_id > 0)
        {
            return $this->response->setContentType('application/json')
                ->setBody(json_encode([$data[$course_plan_id]]));
        }

        return $this->response->setContentType('application/json')
            ->setBody(json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    }



    // BUG : This function isn't used. Should we use/keep it ?
    /**
     * Deletes or deactivates a user depending on $action
     *
     * @param integer $user_id ID of the user to affect
     * @param integer $action  Action to apply on the user
     *      - 0 for displaying the confirmation
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *
     * @return void
     *
     */
    public function delete_user($user_id = 0, $action = 0)
    {
        if($_SESSION['user_access'] < config('\User\Config\UserConfig')->access_lvl_admin)
            return $this->display_view('\User\errors\403error');

        $user = $this->user_model->withDeleted()->find($user_id);

        if(is_null($user))
            return redirect()->to(base_url('/user/admin/list_user'));

        // Action to perform
        switch ($action)
        {
            // Displays confirmation
            case 0:
                $output = array(
                    'user' => $user,
                    'title' => lang('user_lang.title_user_delete')
                );
                return $this->display_view('\User\admin\delete_user', $output);

            // Deactivates (soft delete) user
            case 1:
                if($_SESSION['user_id'] != $user['id'])
                    $this->user_model->delete($user_id, FALSE);

                break;

            // Deletes user
            case 2:
                if($_SESSION['user_id'] != $user['id'])
                {
                    // Deletes associated information
                    foreach($this->trainer_apprentice_model->where('fk_apprentice',$user['id'])->orWhere('fk_trainer',$user['id'])->findAll() as $trainerApprentice)
                        $trainerApprentice==null?:$this->trainer_apprentice_model->delete($trainerApprentice['id']);

                    if(count($this->user_course_model->getUser($user['id']))>0)
                    {
                        foreach($this->user_course_model->where('fk_user',$user['id'])->findAll() as $user_course)
                        {
                            foreach($this->user_course_model->getAcquisitionStatus($user_course['id']) as $acquisition_status)
                            {
                                foreach ($this->comment_model->where('fk_acquisition_status',$acquisition_status['id']) as $comment)
                                    $comment==null?:$this->comment_model->delete($comment['id'],true);

                                $this->acquisition_status_model->delete($acquisition_status['id'],true);
                            }

                            $this->user_course_model->delete($user_course['id'],true);
                        }
                    }

                    $this->user_model->delete($user_id, TRUE);
                }

                break;
        }

        return redirect()->to('/user/admin/list_user');
    }
}