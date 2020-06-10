<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Operational_competence is used to save the opetational competences available, and link them with
 * Competence_domain entries
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class operational_competence_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'operational_competence';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['competence_domain'=> ['primary_key' => 'fk_competence_domain',
                                            'model' => 'competence_domain_model']];
    protected $has_many = ['objectives' => ['primary_key' => 'fk_operational_competence',
                                'model' => 'Objective_model']];
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