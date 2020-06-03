<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User_course is used to link a user (apprentice level) with any course and to 
 * keep the course's status as well as its begin and end date
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class user_course_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'user_course';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['user'=> ['primary_key' => 'fk_user',
                                'model' => 'user_model'],
                            'course_plan' => ['primary_key' => 'fk_course_plan',
                                'model' => 'course_plan_model'],
                            'status' => ['primary_key' => 'fk_status',
                                'model' => 'status_model']
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