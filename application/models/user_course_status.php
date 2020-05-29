<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User_course_status is used to list the possible status of a course's avancement
 * so any course's status can be keep
 * 
 * @author      Orif (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c) Orif (https://www.orif.ch)
 */
class user_course_status_model extends MY_Model
{
    /* Set MY_Model variables */
    protected $_table = 'user_course_status';
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