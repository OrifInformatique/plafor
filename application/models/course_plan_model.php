<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Course_plan is used to save the courses available
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class course_plan_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'course_plan';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $has_many = ['competence_domains' => ['primary_key' => 'fk_course_plan',
                                'model' => 'Competence_domain_model']];
    protected $soft_delete = TRUE;
    protected $soft_delete_key = 'archive';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
}