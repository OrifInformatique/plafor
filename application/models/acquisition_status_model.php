<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User_course is used to link a user (apprentice level) with any course and to 
 * keep the course's status as well as its begin and end date
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class acquisition_status_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'acquisition_status';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['objective' => ['primary_key' => 'fk_objective',
                                'model' => 'objective_model'],
                            'user_course'=> ['primary_key' => 'fk_user_course',
                                'model' => 'user_course_model'],
                            'acquisition_level' => ['primary_key' => 'fk_acquisition_level',
                                'model' => 'acquisition_level_model']
                            ];
    /* protected $soft_delete = TRUE; */
    /* protected $soft_delete_key = 'archive'; */

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
}