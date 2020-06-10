<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Objective is used to save the objectives available, and link them with
 * Operational_competence entries
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class objective_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'objective';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['operational_competence'=> ['primary_key' => 'fk_operational_competence',
                                            'model' => 'operational_competence_model']];
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