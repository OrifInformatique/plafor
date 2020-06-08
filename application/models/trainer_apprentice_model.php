<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User_course is used to link a user (apprentice level) with another user (formator level)
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class formator_apprentice_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'formator_apprentice';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['user'=> ['primary_key' => 'fk_apprentice',
                                'model' => 'user_model'],
                            'course_plan' => ['primary_key' => 'fk_formator',
                                'model' => 'course_plan_model']
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