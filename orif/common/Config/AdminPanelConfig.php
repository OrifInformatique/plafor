<?php
/**
 * Config for common module
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Config;


use User\Controllers\Admin;

class AdminPanelConfig extends \CodeIgniter\Config\BaseConfig
{
    /** here come the views details for adminPanel */
    public $views=[
        /** eg... */
        ['label'=>'user_lang.title_user_list','title'=>'user_lang.title_user_list','pageLink'=>'user/admin/list_user'],
        ['label'=>'user_lang.admin_course_plans','title'=>'user_lang.admin_course_plans','pageLink'=>'plafor/admin/list_course_plan'],
        ['label'=>'user_lang.admin_competence_domains','title'=>'user_lang.admin_competence_domains','pageLink'=>'plafor/admin/list_competence_domain'],
        ['label'=>'user_lang.admin_operational_competences','title'=>'user_lang.admin_operational_competences','pageLink'=>'plafor/admin/list_operational_competence'],
        ['label'=>'user_lang.admin_objectives','title'=>'user_lang.admin_objectives','pageLink'=>'plafor/admin/list_objective'],

    ];

}