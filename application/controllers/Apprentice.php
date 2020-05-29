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
        model(['user/user_model','user/user_type_model','course_plan_model','competence_domain_model','operational_competence_model','objective_model']);
        
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
        }else{
            //$apprentices = $this->user_model->get_many_by(array('id' => $formator_id));
        }
        
        $output = array(
            'apprentices' => $apprentices
        );
        $this->display_view('apprentice/list', $output);
    }

}
