<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Plafor Administraton
 *
 * @author      Orif (ToRe)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     2.0
 */
class Admin extends MY_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        /* Define controller access level */
        $this->access_level = ACCESS_LVL_ADMIN;

        parent::__construct();

        // Load required items
        $this->load->library('form_validation')->model(['user/user_model','course_plan_model','user_course_model','competence_domain_model','operational_competence_model','objective_model']);

        // Assign form_validation CI instance to this
        $this->form_validation->CI =& $this;
    }

    /**
    * Menu for admin privileges
    */
    public function index()
    {
      $this->list_course_plan();
    }


    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_course_plan($id_apprentice = null)
    {
        if($id_apprentice == null){
            $course_plans = $this->course_plan_model->get_all();
        }else{
            $userCourses = $this->user_course_model->get_many_by('fk_user',$id_apprentice);

            $coursesId = array_column($userCourses, 'fk_course_plan');

            $course_plans = $this->course_plan_model->get_many($coursesId);
        }

        $output = array(
            'course_plans' => $course_plans
        );

        if(is_numeric($id_apprentice)){
            $output[] = ['course_plans' => $course_plans];
        }

        $this->display_view('admin/course_plan/list', $output);
    }

    /**
     * Adds or modify a course plan
     *
     * @param integer $course_plan_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_course_plan($course_plan_id = 0)
    {
		if (count($_POST) > 0) {
			$course_plan_id = $this->input->post('id');
                        $rules = array(
                            array(
                              'field' => 'formation_number',
                              'label' => 'lang:field_course_plan_formation_number',
                              'rules' => 'required|max_length['.FORMATION_NUMBER_MAX_LENGTH.']|numeric',
                            ),
                            array(
                              'field' => 'official_name',
                              'label' => 'lang:field_course_plan_name',
                              'rules' => 'required|max_length['.OFFICIAL_NAME_MAX_LENGTH.']',
                            ),array(
                              'field' => 'date_begin',
                              'label' => 'lang:field_course_plan_official_name',
                              'rules' => 'required|required',
                            )
                        );
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()) {
				$course_plan = array(
					'formation_number' => $this->input->post('formation_number'),
					'official_name' => $this->input->post('official_name'),
					'date_begin' => $this->input->post('date_begin')
				);
				if ($course_plan_id > 0) {
					$this->course_plan_model->update($course_plan_id, $course_plan);
				} else {
					$this->course_plan_model->insert($course_plan);
				}
				redirect('admin/list_course_plan');
                                exit();
			}
		}

        $output = array(
            'title' => $this->lang->line('title_course_plan_'.((bool)$course_plan_id ? 'update' : 'new')),
            'course_plan' => $this->course_plan_model->get($course_plan_id),
	);

        $this->display_view('admin/course_plan/save', $output);
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
        $course_plan = $this->course_plan_model->with_all()->get($course_plan_id);
        if (is_null($course_plan)) {
            redirect('admin/list_course_plan');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'course_plan' => $course_plan,
                    'title' => lang('title_course_plan_delete')
                );
                $this->display_view('admin/course_plan/delete', $output);
                break;
            case 1: // Deactivate (soft delete) course plan

                $competenceDomainIds = array_column($course_plan->competence_domains, 'id');

                $operational_competences = $this->operational_competence_model->with_all()->get_many_by('fk_competence_domain',$competenceDomainIds);

                $operationalCompetencesIds = array_column($operational_competences, 'id');

                $objectiveIds = array();
                foreach($operational_competences as $operational_competence){
                    array_push($objectiveIds,array_column($operational_competence->objectives, 'id'));
                }

                $objectiveIds = array_merge(...$objectiveIds);

                $this->objective_model->delete_many($objectiveIds);

                $this->operational_competence_model->delete_many($operationalCompetencesIds);

                $this->competence_domain_model->delete_many($competenceDomainIds);

                $this->course_plan_model->delete($course_plan_id, FALSE);
                redirect('admin/list_course_plan');

			case 2: // Hard delete course plan
				$competenceDomainIds = array_column($course_plan->competence_domains, 'id');
				$operationalCompetences = $this->operational_competence_model->with_all()->get_many_by('fk_competence_domain', $competenceDomainIds);
				$operCompIds = array_column($operationalCompetences, 'id');
				$objectiveIds = [];

				foreach ($operationalCompetences as $operationalCompetence) {
					$objectiveIds = array_merge($objectiveIds, array_column($operationalCompetence->objectives, 'id'));
				}

				foreach ($objectiveIds as $objectiveId) {
					$this->objective_model->delete($objectiveId, TRUE);
				}
				foreach ($operCompIds as $operCompId) {
					$this->operational_competence_model->delete($operCompId, TRUE);
				}
				foreach ($competenceDomainIds as $competenceDomainId) {
					$this->competence_domain_model->delete($competenceDomainId, TRUE);
				}
				$this->course_plan_model->delete($course_plan_id, TRUE);
                redirect('admin/list_course_plan');

            default: // Do nothing
                redirect('admin/list_course_plan');
                break;
        }
    }


    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_competence_domain($id_course_plan = null)
    {
        if($id_course_plan == null){
            $competence_domains = $this->competence_domain_model->get_all();
        }else{
            $course_plan = $this->course_plan_model->get($id_course_plan);
            $competence_domains = $this->competence_domain_model->get_many_by('fk_course_plan', $course_plan->id);
        }

        $output = array(
            'competence_domains' => $competence_domains
        );

        if(is_numeric($id_course_plan)){
            $output[] = ['course_plan' => $course_plan];
        }

        $this->display_view('admin/competence_domain/list', $output);
    }

    /**
     * Adds or modify a course plan
     *
     * @param integer $competence_domain_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_competence_domain($competence_domain_id = 0)
    {
		if (count($_POST) > 0) {
			$competence_domain_id = $this->input->post('id');
                        $rules = array(
                            array(
                              'field' => 'symbol',
                              'label' => 'lang:field_competence_domain_symbol',
                              'rules' => 'required|max_length['.SYMBOL_MAX_LENGTH.']',
                            ),
                            array(
                              'field' => 'name',
                              'label' => 'lang:field_competence_domain_name',
                              'rules' => 'required|max_length['.COMPETENCE_DOMAIN_NAME_MAX_LENGTH.']',
                            )
                        );
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()) {
				$competence_domain = array(
					'symbol' => $this->input->post('symbol'),
					'name' => $this->input->post('name'),
                                        'fk_course_plan' => $this->input->post('course_plan')
				);
				if ($competence_domain_id > 0) {
					$this->competence_domain_model->update($competence_domain_id, $competence_domain);
				} else {
					$this->competence_domain_model->insert($competence_domain);
				}
				redirect('admin/list_competence_domain');
                                exit();
			}
		}

        $output = array(
            'title' => $this->lang->line('title_competence_domain_'.((bool)$competence_domain_id ? 'update' : 'new')),
            'competence_domain' => $this->competence_domain_model->get($competence_domain_id),
            'course_plans' => $this->course_plan_model->dropdown('official_name')
	);

        $this->display_view('admin/competence_domain/save', $output);
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
        $competence_domain = $this->competence_domain_model->with_all()->get($competence_domain_id);
        if (is_null($competence_domain)) {
            redirect('admin/competence_domain/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'competence_domain' => $competence_domain,
                    'title' => lang('title_competence_domain_delete')
                );
                $this->display_view('admin/competence_domain/delete', $output);
                break;
            case 1: // Deactivate (soft delete) competence domain

                $operationalCompetenceIds = array_column($this->operational_competence_model->get_many_by('fk_competence_domain', $competence_domain_id), 'id');

                $this->objective_model->delete_by('fk_operational_competence',$operationalCompetenceIds);
                $this->operational_competence_model->delete_by('fk_competence_domain='.$competence_domain_id);
                $this->competence_domain_model->delete($competence_domain_id, FALSE);
                redirect('admin/list_competence_domain');

			case 2: // Hard delete
				$operCompIds = array_column($this->operational_competence_model->get_many_by('fk_competence_domain', $competence_domain_id), 'id');
				$objectiveIds = array_column($this->objective_model->get_many_by('fk_operational_competence', $operCompIds), 'id');

				foreach ($objectiveIds as $objectiveId) {
					$this->objective_model->delete($objectiveId, TRUE);
				}
				foreach ($operCompIds as $operCompId) {
					$this->operational_competence_model->delete($operCompId, TRUE);
				}
				$this->competence_domain_model->delete($competence_domain_id, TRUE);
				redirect('admin/list_competence_domain');

            default: // Do nothing
                redirect('admin/list_competence_domain');
        }
    }

    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_operational_competence($id_competence_domain = null)
    {
        if($id_competence_domain == null){
            $operational_competences = $this->operational_competence_model->get_all();
        }else{
            $competence_domain = $this->competence_domain_model->get($id_competence_domain);
            $operational_competences = $this->operational_competence_model->get_many_by('fk_competence_domain',$competence_domain->id);
        }

        $output = array(
			'operational_competences' => $operational_competences,
			'id_competence_domain' => $id_competence_domain
        );

        $this->display_view('admin/operational_competence/list', $output);
    }

    /**
     * Adds or modify a course plan
     *
     * @param integer $operational_competence_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_operational_competence($operational_competence_id = 0)
    {
		if (count($_POST) > 0) {
			$operational_competence_id = $this->input->post('id');
			$rules = array(
				array(
					'field' => 'symbol',
					'label' => 'lang:field_operational_competence_symbol',
					'rules' => 'required|max_length['.SYMBOL_MAX_LENGTH.']',
				),
				array(
					'field' => 'name',
					'label' => 'lang:field_operational_name',
					'rules' => 'required|max_length['.OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH.']',
				),
				array(
					'field' => 'methodologic',
					'label' => 'lang:field_operational_methodologic',
					'rules' => 'max_length['.SQL_TEXT_MAX_LENGTH.']',
				),
				array(
					'field' => 'social',
					'label' => 'lang:field_operational_social',
					'rules' => 'max_length['.SQL_TEXT_MAX_LENGTH.']',
				),
				array(
					'field' => 'personal',
					'label' => 'lang:field_operational_personal',
					'rules' => 'max_length['.SQL_TEXT_MAX_LENGTH.']',
				),
			);
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()) {
				$operational_competence = array(
					'symbol' => $this->input->post('symbol'),
					'name' => $this->input->post('name'),
					'methodologic' => $this->input->post('methodologic'),
					'social' => $this->input->post('social'),
					'personal' => $this->input->post('personal'),
					'fk_competence_domain' => $this->input->post('competence_domain')
				);
				if ($operational_competence_id > 0) {
					$this->operational_competence_model->update($operational_competence_id, $operational_competence);
				} else {
					$this->operational_competence_model->insert($operational_competence);
				}
				redirect('admin/list_operational_competence');
                                exit();
			}
		}

        $output = array(
            'title' => $this->lang->line('title_operational_competence_'.((bool)$operational_competence_id ? 'update' : 'new')),
            'operational_competence' => $this->operational_competence_model->get($operational_competence_id),
            'competence_domains' => $this->competence_domain_model->dropdown('name')
		);

        $this->display_view('admin/operational_competence/save', $output);
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
        $operational_competence = $this->operational_competence_model->get($operational_competence_id);
        if (is_null($operational_competence)) {
            redirect('admin/operational_competence/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'operational_competence' => $operational_competence,
                    'title' => lang('title_operational_competence_delete')
                );
                $this->display_view('admin/operational_competence/delete', $output);
                break;
            case 1: // Deactivate (soft delete) operational competence
                $this->objective_model->delete_by('fk_operational_competence='.$operational_competence_id);
                $this->operational_competence_model->delete($operational_competence_id, FALSE);
				redirect('admin/list_operational_competence');
			case 2: // Hard delete
				$objectiveIds = array_column($this->objective_model->get_many_by('fk_operational_competence', $operational_competence_id), 'id');

				foreach ($objectiveIds as $objectiveId) {
					$this->objective_model->delete($objectiveId, TRUE);
				}
				$this->operational_competence_model->delete($operational_competence_id, TRUE);
            default: // Do nothing
                redirect('admin/list_operational_competence');
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
        $link = $this->trainer_apprentice_model->get($link_id);
        $apprentice = $this->user_model->get($link->fk_apprentice);
        $trainer = $this->user_model->get($link->fk_trainer);
        if (is_null($link)) {
            redirect('apprentice/list_apprentice');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'link' => $link,
                    'apprentice' => $apprentice,
                    'trainer' => $trainer,
                    'title' => lang('title_apprentice_link_delete')
                );
                $this->display_view('apprentice/delete', $output);
                break;
            case 1: // Delete apprentice link
                $this->trainer_apprentice_model->delete($link_id, TRUE);
                redirect('apprentice/list_apprentice/'.$apprentice->id);
            default: // Do nothing
                redirect('apprentice/list_apprentice/'.$apprentice->id);
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
        $user_course = $this->user_course_model->get($user_course_id);
        $course_plan = $this->course_plan_model->get($user_course->fk_course_plan);
        $apprentice = $this->user_model->get($user_course->fk_user);
        $status = $this->user_course_status_model->get($user_course->fk_status);
        if (is_null($user_course)) {
            redirect('admin/user_course/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'user_course' => $user_course,
                    'course_plan' => $course_plan,
                    'apprentice' => $apprentice,
                    'status' => $status,
                    'title' => lang('title_user_course_delete')
                );
                $this->display_view('user_course/delete', $output);
                break;
            case 1: // Delete course plan
                $this->user_course_model->delete($user_course_id, TRUE);
                redirect('apprentice/list_apprentice');
            default: // Do nothing
                redirect('apprentice/list_apprentice');
        }
    }

    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_objective($id_operational_competence = null)
    {
        if($id_operational_competence == null){
            $objectives = $this->objective_model->get_all();
        }else{
            $operational_competence = $this->operational_competence_model->get($id_operational_competence);
            $objectives = $this->objective_model->get_many_by('fk_operational_competence',$operational_competence->id);
        }

        $output = array(
            'objectives' => $objectives
        );

        if(is_numeric($id_operational_competence)){
            $output[] = ['operational_competence',$operational_competence];
        }

        $this->display_view('admin/objective/list', $output);
    }

    /**
     * Adds or modify a course plan
     *
     * @param integer $objective_id = The id of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_objective($objective_id = 0)
    {
		if (count($_POST) > 0) {
			$objective_id = $this->input->post('id');
                        $rules = array(
                            array(
                              'field' => 'symbol',
                              'label' => 'lang:field_objective_symbol',
                              'rules' => 'required|max_length['.SYMBOL_MAX_LENGTH.']',
                            ),
                            array(
                              'field' => 'taxonomy',
                              'label' => 'lang:field_objective_taxonomy',
                              'rules' => 'required|max_length['.TAXONOMY_MAX_VALUE.']',
                            ),array(
                              'field' => 'name',
                              'label' => 'lang:field_objective_name',
                              'rules' => 'required|max_length['.OBJECTIVE_NAME_MAX_LENGTH.']',
                            )
                        );
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()) {
				$objective = array(
					'symbol' => $this->input->post('symbol'),
					'taxonomy' => $this->input->post('taxonomy'),
					'name' => $this->input->post('name'),
                                        'fk_operational_competence' => $this->input->post('operational_competence')
				);
				if ($objective_id > 0) {
					$this->objective_model->update($objective_id, $objective);
				} else {
					$this->objective_model->insert($objective);
				}
				redirect('admin/list_objective');
                                exit();
			}
		}

        $output = array(
            'title' => $this->lang->line('title_objective_'.((bool)$objective_id ? 'update' : 'new')),
            'objective' => $this->objective_model->get($objective_id),
            'operational_competences' => $this->operational_competence_model->dropdown('name')
	);

        $this->display_view('admin/objective/save', $output);
    }

    /**
     * Deletes a course plan depending on $action
     *
     * @param integer $objective_id = ID of the objective to affect
     * @param integer $action = Action to apply on the course plan:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function delete_objective($objective_id, $action = 0)
    {
        $objective = $this->objective_model->get($objective_id);
        if (is_null($objective)) {
            redirect('admin/objective/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'objective' => $objective,
                    'title' => lang('title_objective_delete')
                );
                $this->display_view('admin/objective/delete', $output);
                break;
            case 1: // Deactivate (soft delete) objective
                $this->objective_model->delete($objective_id, FALSE);
                redirect('admin/list_objective');
			case 2: // Hard delete
				$this->objective_model->delete($objective_id, TRUE);
				redirect('admin/list_objective');
            default: // Do nothing
                redirect('admin/list_objective');
        }
    }

    /**
     * Form to create a link between a apprentice and a course plan
     *
     * @param int (SQL PRIMARY KEY) $id_user_course
     */
    public function save_user_course($id_apprentice = null,$id_user_course = 0){

        $apprentice = $this->user_model->get($id_apprentice);
        $user_course = $this->user_course_model->get($id_user_course);

        if($id_apprentice == null || $apprentice->fk_user_type != $this->user_type_model->get_by('name',$this->lang->line('title_apprentice'))->id){
            redirect(base_url('apprentice/list_apprentice'));
            exit();
        }

        if(count($_POST) > 0){
            $rules = array(
                array(
                    'field' => 'course_plan',
                    'label' => 'lang:course_plan',
                    'rules' => 'required|numeric',
                ),
                array(
                    'field' => 'status',
                    'label' => 'lang:status',
                    'rules' => 'required|numeric',
                ),
                array(
                    'field' => 'date_begin',
                    'label' => 'lang:field_user_course_date_begin',
                    'rules' => 'required',
                ),
                /*
                array(
                    'field' => 'date_end',
                    'label' => 'lang:field_user_course_date_end',
                    'rules' => 'required',
                ),
                */
            );

            $this->form_validation->set_rules($rules);

            if($this->form_validation->run()){
                $user_course = array(
                    'fk_user' => $id_apprentice,
                    'fk_course_plan' => $this->input->post('course_plan'),
                    'fk_status' => $this->input->post('status'),
                    'date_begin' => $this->input->post('date_begin'),
                    'date_end' => $this->input->post('date_end'),
                );

                if($id_user_course > 0){
                    echo $this->user_course_model->update($id_user_course, $user_course);
                }else{
                    $id_user_course = $this->user_course_model->insert($user_course);

                    $course_plan = $this->course_plan_model->with_all()->get($user_course['fk_course_plan']);

                    $competenceDomainIds = array_column($course_plan->competence_domains, 'id');

                    $operational_competences = $this->operational_competence_model->with_all()->get_many_by('fk_competence_domain',$competenceDomainIds);

                    $objectiveIds = array_column($operational_competences->objectives,'id');

                    foreach ($objectiveIds as $objectiveId){

                        $acquisition_status = array(
                            'fk_objective' => $objectiveId,
                            'fk_user_course' => $id_user_course,
                            'fk_acquisition_level' => 1
                        );

                        $this->acquisition_status_model->insert($acquisition_status);
                    }
                }

                redirect('apprentice/view_apprentice/'.$id_apprentice);
                exit();
            }
        }

        $course_plans = $this->course_plan_model->dropdown('official_name');
        $status = $this->user_course_status_model->dropdown('name');

        $output = array(
            'title' => $this->lang->line('title_course_plan_link'),
            'course_plans' => $course_plans,
            'user_course'   => $user_course,
            'status' => $status,
            'apprentice' => $apprentice
        );

        $this->display_view('user_course/save',$output);
    }

    /**
     * Create a link between a apprentice and a trainer, or change the trainer
     * linked on the selected trainer_apprentice SQL entry
     *
     * @param INT (SQL PRIMARY KEY) $id_apprentice
     * @param INT (SQL PRIMARY KEY) $id_link
     */
    public function save_apprentice_link($id_apprentice = null, $id_link = 0){

        $apprentice = $this->user_model->get($id_apprentice);

        if($_SESSION['user_access'] < ACCESS_LVL_ADMIN
        || $apprentice == null
        || $apprentice->fk_user_type != $this->user_type_model->
        get_by('name',$this->lang->line('title_apprentice'))->id){
            redirect(base_url());
            exit();
        }

        // It seems that the MY_model dropdown method can't return a filtered result
        // so here we get every users that are trainer, then we create a array
        // with the matching constitution

        if(count($_POST) > 0){
            $id_apprentice = $this->input->post('id');
            $rules = array(
                array(
                    'field' => 'apprentice',
                    'label' => 'field_apprentice_username',
                    'rules' => 'required|numeric'
                ),
                array(
                    'field' => 'trainer',
                    'label' => 'field_trainer_link',
                    'rules' => 'required|numeric'
                ),
            );

            $this->form_validation->set_rules($rules);

            if($this->form_validation->run()){
                echo var_dump($_POST);

                $apprentice_link = array(
                    'fk_trainer' => $this->input->post('trainer'),
                    'fk_apprentice' => $this->input->post('apprentice'),
                );

                if($id_link > 0){
                    echo $this->trainer_apprentice_model->update($id_apprentice,$apprentice_link);
                }else{
                    echo $this->trainer_apprentice_model->insert($apprentice_link);
                }

                redirect('apprentice');
                exit();
            }
        }

        $trainersRaw = $this->user_model->get_many_by('fk_user_type',$this->user_type_model->get_by('access_level',ACCESS_LVL_TRAINER)->id);

        $trainers = array();

        foreach ($trainersRaw as $trainer){
            $trainers[$trainer->id] = $trainer->username;
        }

        $link = $this->trainer_apprentice_model->get($id_link);

        $output = array(
            'apprentice' => $apprentice,
            'trainers' => $trainers,
            'link' => $link,
        );

        $this->display_view('apprentice/link',$output);
    }

}
