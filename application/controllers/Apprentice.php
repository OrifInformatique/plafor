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
        /* Define controller access level */
        $this->access_level = $this->config->item('access_lvl_apprentice');
        
        parent::__construct();

        // Load required items
        $this->load->library('form_validation')->
        model(['user/user_model','user/user_type_model','trainer_apprentice_model',
        'course_plan_model','user_course_model','user_course_status_model',
        'competence_domain_model','operational_competence_model','objective_model'
        ,'acquisition_status_model','acquisition_level_model','comment_model']);
        
        // Assign form_validation CI instance to this
        $this->form_validation->CI =& $this;
    }

    /**
    * Menu for admin privileges
    */
    public function index()
    {
      if(empty($_SESSION) || $_SESSION['logged_in'] != true){
          redirect(base_url('user/auth/login'));
      }else if($_SESSION['user_access'] >= ACCESS_LVL_ADMIN){
        redirect(base_url('apprentice/list_apprentice'));
      }else if($_SESSION['user_access'] == ACCESS_LVL_APPRENTICE){
          redirect(base_url('apprentice/view_apprentice/'.$_SESSION['user_id']));
      }else if($_SESSION['user_access'] == ACCESS_LVL_TRAINER){
          redirect(base_url('apprentice/list_apprentice/'.$_SESSION['user_id']));
      }
    }
    
    /**
     * Displays the list of apprentice
     *
     * @return void
     */
    public function list_apprentice($trainer_id = null)
    {
        if(is_null($trainer_id) && $_SESSION['user_access'] < ACCESS_LVL_ADMIN){
            redirect("apprentice");
        }
        
        //if($trainer_id == null){
            $apprentice_level = $this->user_type_model->get_by('access_level', ACCESS_LVL_APPRENTICE);
            $apprentices = $this->user_model->get_many_by('fk_user_type', $apprentice_level->id);
            $coursesList = $this->course_plan_model->get_all();
            $courses = $this->user_course_model->get_all();
        //}else{
        //        $apprentices = $this->user_model->get_many_by(array('id' => $trainer_id));
            
        //}
        
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
        
        if($apprentice->fk_user_type != $this->user_type_model->get_by('name',$this->lang->line('title_apprentice'))->id){
            redirect(base_url('apprentice/list_apprentice'));
            exit();
        }
        
        $user_courses = $this->user_course_model->get_many_by('fk_user',$apprentice_id);
        $user_course_status = $this->user_course_status_model->get_all();
        $course_plans = $this->course_plan_model->get_all();
        
        $trainers = $this->user_model->get_many_by('fk_user_type',$this->user_type_model->get_by('name',$this->lang->line('title_trainer'))->id);
        $links = $this->trainer_apprentice_model->get_many_by('fk_apprentice',$apprentice_id);
        
        $output = array(
            'apprentice' => $apprentice,
            'trainers' => $trainers,
            'links' => $links,
            'user_courses' => $user_courses,
            'user_course_status' => $user_course_status,
            'course_plans' => $course_plans
        );
        
        $this->display_view('apprentice/view',$output);
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
                    
                    foreach ($course_plan->competence_domains as $competence_domain){
                        $competenceDomainIds[] = $competence_domain->id;
                    }
                    
                    $operational_competences = $this->operational_competence_model->with_all()->get_many_by('fk_competence_domain',$competenceDomainIds);
                    
                    $objectiveIds = array();
                    foreach ($operational_competences as $operational_competence){
                        foreach ($operational_competence->objectives as $objective){
                            $objectiveIds[] = $objective->id;
                        }
                    }
                    
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
     * Show a user course's details
     * 
     * @param int (SQL PRIMARY KEY) $id_user_course
     */
    public function view_user_course($id_user_course = null){
        $user_course = $this->user_course_model->get($id_user_course);
        $apprentice = $this->user_model->get($user_course->fk_user);
        $user_course_status = $this->user_course_status_model->get($user_course->fk_status);
        $course_plan = $this->course_plan_model->get($user_course->fk_course_plan);
        $trainers_apprentice = $this->trainer_apprentice_model->get_many_by('fk_apprentice',$apprentice->id);
        $acquisition_status = $this->acquisition_status_model->with_all()->get_many_by('fk_user_course',$id_user_course);
        $acquisition_levels = $this->acquisition_level_model->get_all();
        
        if($user_course == null){
            redirect('apprentice/list_apprentice');
            exit();
        }
        
        $output = array(
            'user_course' => $user_course,
            'apprentice' => $apprentice,
            'user_course_status' => $user_course_status,
            'course_plan' => $course_plan,
            'trainers_apprentice' => $trainers_apprentice,
            'acquisition_status' => $acquisition_status,
            'acquisition_levels' => $acquisition_levels
        );
        
        $this->display_view('user_course/view',$output);
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
    
    /**
     * Show details of the selected acquisition status
     * 
     * @param int (SQL PRIMARY KEY) $acquisition_status_id
     */
    public function view_acquisition_status($acquisition_status_id = null){
        $acquisition_status = $this->acquisition_status_model->with_all()->get($acquisition_status_id);
        
        if($acquisition_status == null){
            redirect(base_url('apprentice'));
            exit();
        }
        
        $comments = $this->comment_model->get_many_by('fk_acquisition_status',$acquisition_status_id);
        $trainers = $this->user_model->get_many_by('fk_user_type',$this->user_type_model->get_by('name',$this->lang->line('title_trainer'))->id);
        $output = array(
            'acquisition_status' => $acquisition_status,
            'trainers' => $trainers,
            'comments' => $comments,
        );
        
        $this->display_view('acquisition_status/view',$output);
    }
    
    public function add_comment($acquisition_status_id = null){
        $acquisition_status = $this->acquisition_status_model->get($acquisition_status_id);
        
        if($acquisition_status == null || $_SESSION['user_access'] != ACCESS_LVL_TRAINER){
            redirect(base_url('apprentice'));
            exit();
        }
        
        if (count($_POST) > 0) {
            $rules = array(
                array(
                  'field' => 'comment',
                  'label' => 'lang:field_comment',
                  'rules' => 'required|max_length['.SQL_TEXT_MAX_LENGTH.']',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run()) {
                $comment = array(
                    'fk_trainer' => $_SESSION['user_id'],
                    'fk_acquisition_status' => $acquisition_status_id,
                    'comment' => $this->input->post('comment'),
                    'date_creation' => date('Y-m-d H:i:s'),
                );
                $this->comment_model->insert($comment);
                
                redirect(base_url('apprentice/view_acquisition_status/'.$acquisition_status->id));
                exit();
            }
        }
        
        $output = array(
            'acquisition_status' => $acquisition_status,
        );
        
        $this->display_view('comment/save',$output);
    }
    
    /**
     * Show details of the selected course plan
     * 
     * @param int (SQL PRIMARY KEY) $course_plan_id
     * 
     */
    public function view_course_plan($course_plan_id = null)
    {
        $course_plan = $this->course_plan_model->with_all()->get($course_plan_id);
        
        if($course_plan == null){
            redirect(base_url('apprentice/list_course_plan'));
            exit();
        }
        
        $output = array(
            'course_plan' => $course_plan,
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
        $competence_domain = $this->competence_domain_model->with_all()->get($competence_domain_id);
        
        if($competence_domain == null){
            redirect(base_url('admin/list_competence_domain'));
            exit();
        }
        
        $output = array(
            'course_plan' => $competence_domain->course_plan,
            'competence_domain' => $competence_domain,
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
        $operational_competence = $this->operational_competence_model->with_all()->get($operational_competence_id);
        
        if($operational_competence == null){
            redirect(base_url('admin/list_operational_competence'));
            exit();
        }
        
        $competence_domain = $this->competence_domain_model->get($operational_competence->fk_competence_domain);
        $course_plan = $this->course_plan_model->get($competence_domain->fk_course_plan);
        
        $output = array(
            'operational_competence' => $operational_competence,
            'competence_domain' => $operational_competence->competence_domain,
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
        $objective = $this->objective_model->with_all()->get($objective_id);
        
        if($objective == null){
            redirect(base_url('admin/list_objective'));
            exit();
        }
        
        $operational_competence = $this->operational_competence_model->get($objective->fk_operational_competence);
        $competence_domain = $this->competence_domain_model->get($operational_competence->fk_competence_domain);
        $course_plan = $this->course_plan_model->get($competence_domain->fk_course_plan);
        
        $output = array(
            'objective' => $objective,
            'operational_competence' => $objective->operational_competence,
            'competence_domain' => $competence_domain,
            'course_plan' => $course_plan
        );
        
        $this->display_view('admin/objective/view',$output);
    }
}
