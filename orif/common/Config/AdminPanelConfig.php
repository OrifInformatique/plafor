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
    public $tabs=[
        /** eg... */
        ['label'=>'user_lang.title_user_list','title'=>'user_lang.title_user_list','pageLink'=>'user/admin/list_user'],
        ['label'=>'plafor_lang.title_apprentice_list','title'=>'plafor_lang.title_apprentice_list','pageLink'=>'plafor/apprentice/list_apprentice'],
        ['label'=>'plafor_lang.admin_course_plans','title'=>'plafor_lang.admin_course_plans','pageLink'=>'plafor/admin/list_course_plan'],
        ['label'=>'plafor_lang.admin_competence_domains','title'=>'plafor_lang.admin_competence_domains','pageLink'=>'plafor/admin/list_competence_domain'],
        ['label'=>'plafor_lang.admin_operational_competences','title'=>'plafor_lang.admin_operational_competences','pageLink'=>'plafor/admin/list_operational_competence'],
        ['label'=>'plafor_lang.admin_objectives','title'=>'plafor_lang.admin_objectives','pageLink'=>'plafor/admin/list_objective'],
    ];
}