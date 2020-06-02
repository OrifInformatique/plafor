<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Plafor Administraton
 *
 * @author      Orif (ToRe)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     2.0
 */
class Apprentice extends MY_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Load required items
        $this->load->library('form_validation')->
        model(['user/user_model','user/user_type_model','course_plan_model','user_course_model','user_course_status_model','competence_domain_model','operational_competence_model','objective_model']);
        
        // Assign form_validation CI instance to this
        $this->form_validation->CI =& $this;
    }

    /**
    * Menu for admin privileges
    */
    public function index()
    {
      if($_SESSION['user_access'] >= ACCESS_LVL_ADMIN){
        redirect(base_url('apprentice/list_apprentice'));
      }else if($_SESSION['user_access'] >= ACCESS_LVL_APPRENTICE){
          redirect(base_url('apprentice/list_apprentice/'.$_SESSION['user_id']));
      }else{
          redirect(base_url('apprentice/view_apprentice/'.$_SESSION['user_id']));
      }
    }
    
    /**
     * Displays the list of apprentice
     *
     * @return void
     */
    public function list_apprentice($formator_id = null)
    {
        if($formator_id == null){
            $apprentice_level = $this->user_type_model->get_by('access_level', ACCESS_LVL_APPRENTICE);
            $apprentices = $this->user_model->get_many_by('fk_user_type', $apprentice_level->id);
            $coursesList = $this->course_plan_model->get_all();
            $courses = $this->user_course_model->get_all();
        }else{
            //$apprentices = $this->user_model->get_many_by(array('id' => $formator_id));
        }
        
        $output = array(
            'apprentices' => $apprentices,
            'coursesList' => $coursesList,
            'courses' => $courses
        );
        
        $this->display_view('apprentice/list', $output);
    }

    /**
     * Show details of the selected apprentice
     * 
     * @param int (SQL PRIMARY KEY) $apprentice_id
     * 
     */
    public function view_apprentice($apprentice_id = null)
    {
        $apprentice = $this->user_model->get($apprentice_id);
        //$level = $this->user_type_model->get_all();
        
        if($apprentice->fk_user_type != $this->user_type_model->get_by('name',$this->lang->line('title_apprentice'))->id){
            redirect(base_url('apprentice/list_apprentice'));
            exit();
        }
        
        $user_courses = $this->user_course_model->get_many_by('fk_user',$apprentice_id);
        $user_course_status = $this->user_course_status_model->get_all();
        $course_plans = $this->course_plan_model->get_all();
        
        $output = array(
            'apprentice' => $apprentice,
            'user_courses' => $user_courses,
            'user_course_status' => $user_course_status,
            'course_plans' => $course_plans
        );
        
        $this->display_view('apprentice/view',$output);
    }
    
    /**
     * Show details of the selected course plan
     * 
     * @param int (SQL PRIMARY KEY) $course_plan_id
     * 
     */
    public function view_course_plan($course_plan_id = null)
    {
        $course_plan = $this->course_plan_model->get($course_plan_id);
        
        if($course_plan == null){
            redirect(base_url('apprentice/list_course_plan'));
            exit();
        }
        
        $competence_domains = $this->competence_domain_model->get_many_by('fk_course_plan',$course_plan_id);
        
        $output = array(
            'course_plan' => $course_plan,
            'competence_domains' => $competence_domains
        );
        
        $this->display_view('admin/course_plan/view',$output);
    }
    
    /**
     * Show details of the selected competence domain
     * 
     * @param int (SQL PRIMARY KEY) $competence_domain_id
     * 
     */
    public function view_competence_domain($competence_domain_id = null)
    {
        $competence_domain = $this->competence_domain_model->get($competence_domain_id);
        
        if($competence_domain == null){
            redirect(base_url('admin/list_competence_domain'));
            exit();
        }
        
        $course_plan = $this->course_plan_model->get($competence_domain->fk_course_plan);
        $operational_competences = $this->operational_competence_model->get_many_by('fk_competence_domain',$competence_domain_id);
        
        $output = array(
            'course_plan' => $course_plan,
            'competence_domain' => $competence_domain,
            'operational_competences' => $operational_competences
        );
        
        $this->display_view('admin/competence_domain/view',$output);
    }
    
    /**
     * Show details of the selected operational competence
     * 
     * @param int (SQL PRIMARY KEY) $operational_competence_id
     * 
     */
    public function view_operational_competence($operational_competence_id = null)
    {
        $operational_competence = $this->operational_competence_model->get($operational_competence_id);
        
        if($operational_competence == null){
            redirect(base_url('admin/list_operational_competence'));
            exit();
        }
        
        $objectives = $this->objective_model->get_many_by('fk_operational_competence',$operational_competence_id);
        $competence_domain = $this->competence_domain_model->get($operational_competence->fk_competence_domain);
        $course_plan = $this->course_plan_model->get_by($competence_domain->id);
        
        $output = array(
            'operational_competence' => $operational_competence,
            'objectives' => $objectives,
            'competence_domain' => $competence_domain,
            'course_plan' => $course_plan
        );
        
        $this->display_view('admin/operational_competence/view',$output);
    }
    
    /**
     * Show details of the selected objective
     * 
     * @param int (SQL PRIMARY KEY) $objective_id
     * 
     */
    public function view_objective($objective_id = null)
    {
        $objective = $this->objective_model->get($objective_id);
        
        if($objective == null){
            redirect(base_url('admin/list_objective'));
            exit();
        }
        
        $operational_competence = $this->operational_competence_model->get($objective->fk_operational_competence);
        $competence_domain = $this->competence_domain_model->get($operational_competence->fk_competence_domain);
        $course_plan = $this->course_plan_model->get($competence_domain->fk_course_plan);
        
        $output = array(
            'objective' => $objective,
            'operational_competence' => $operational_competence,
            'objective' => $objective
        );
        
        $this->display_view('admin/objective/view',$output);
    }
}
