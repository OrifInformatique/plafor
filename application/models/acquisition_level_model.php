<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Aquisition_level is used to list the possible aquisition level of every 
 * user (apprentice) of any objective so it can be keep
 * 
 * @author      Orif (UlSi, ViDi, ToRé)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class acquisition_level_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'acquisition_level';
    protected $primary_key = 'id';
    protected $protected_attributes = ['id'];
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