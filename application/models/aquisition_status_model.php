<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User_course is used to link a user (apprentice level) with any course and to 
 * keep the course's status as well as its begin and end date
 * 
 * @author      Orif (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class aquisition_status_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'aquisition_status';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['objective' => ['primary_key' => 'fk_objective',
                                'model' => 'objective_model'],
                            'user'=> ['primary_key' => 'fk_user',
                                'model' => 'user_model'],
                            'aquisition_level' => ['primary_key' => 'fk_aquisition_level',
                                'model' => 'aquisition_level_model']
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