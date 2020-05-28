<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Administraton
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
        $this->access_level = $this->config->item('access_lvl_admin');

        parent::__construct();

        // Load required items
        $this->load->library('form_validation')->model(['course_plan_model']);

        // Assign form_validation CI instance to this
        $this->form_validation->CI =& $this;
    }

    /**
     * Displays the list of course plans
     *
     * @return void
     */
    public function list_course_plan()
    {
        $course_plans = $this->course_plan_model->get_all();

        $output = array(
            'course_plans' => $course_plans
        );
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
                              'rules' => 'required',
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
        $course_plan = $this->course_plan_model->get($course_plan_id);
        if (is_null($course_plan)) {
            redirect('admin/course_plan/list');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'course_plan' => $course_plan,
                    'title' => lang('title_course_plan_delete')
                );
                $this->display_view('admin/course_plan/delete', $output);
                break;
            case 1: // Delete course plan
                if ($_SESSION['course_plan_id'] != $course_plan->id) {
                    $this->course_plan_model->delete($course_plan_id, TRUE);
                }
                redirect('admin/list_course_plan');
            default: // Do nothing
                redirect('admin/list_course_plan');
        }
    }
    
}
