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
    /** Update this array to customize admin pannel tabs for your needs 
     *  Syntax : ['label'=>'tab label','pageLink'=>'tab link']
    */
    public $tabs=[
        /** eg... */
        ['label'=>'user_lang.title_user_list','title'=>'user_lang.title_user_list','pageLink'=>'user/admin/list_user'],
        ['label'=>'plafor_lang.title_my_apprentices','title'=>'plafor_lang.title_apprentice_list','pageLink'=>'plafor/apprentice/list_apprentice'],
        ['label'=>'plafor_lang.admin_course_plans','title'=>'plafor_lang.admin_course_plans','pageLink'=>'plafor/courseplan/list_course_plan'],
        ['label'=>'plafor_lang.list_title_domain','title'=>'plafor_lang.list_title_domain','pageLink'=>'plafor/grade/showAllTeachingDomain'],
        ['label'=>'plafor_lang.title_list_subject','title'=>'plafor_lang.title_list_subject','pageLink'=>'plafor/grade/showAllTeachingSubject'],
        ['label'=>'plafor_lang.title_list_module','title'=>'plafor_lang.title_list_module','pageLink'=>'plafor/grade/showAllTeachingModule'],
        ['label'=>'plafor_lang.name_grade','title'=>'plafor_lang.name_grade','pageLink'=>'plafor/grade/showAllGrade'],
    ];
}