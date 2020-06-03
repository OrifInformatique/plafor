<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Comment is used to link a comment from a user (formator level) to another user (apprentice level) on a objective
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class comment_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'comment';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
    protected $belongs_to = ['formator_apprentice'=> ['primary_key' => 'fk_formator_apprentice',
                                'model' => 'formator_apprentice_model'],
                            'acquisition_status' => ['primary_key' => 'fk_acquisition_status',
                                'model' => 'acquisition_status_model']
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