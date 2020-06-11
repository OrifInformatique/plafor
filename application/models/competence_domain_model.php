<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Competence_domain is used to save the competence domains available, and link them with
 * Course_plan entries
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
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
    protected $has_many = ['operational_competences' => ['primary_key' => 'fk_competence_domain',
                                'model' => 'Operational_competence_model']];
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