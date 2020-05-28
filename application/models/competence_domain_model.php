<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User is used to give access to the application.
 * User_type is used to give different access rights (defining an access level).
 * 
 * @author      Orif (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class competence_domain_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'competence_domain';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['course_plan'=> ['primary_key' => 'fk_course_plan',
                                            'model' => 'course_plan_model']];
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