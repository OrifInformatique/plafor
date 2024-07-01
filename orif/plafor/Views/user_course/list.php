<?php

use CodeIgniter\I18n\Time;

view('\Plafor\templates\navigator',['reset'=>true]);
helper('form');
/**
 * Users List View
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= $title ?></h1>
        </div>
    </div>
    <div class="row" style="justify-content:space-between">
        <div class="col-sm-3">
            <a href="<?= base_url('plafor/apprentice/save_user_course/'.$id_apprentice); ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_new_m'); ?>
            </a>
        </div>
    </div>
    <div class="row mt-2">
     <div class="col-sm-3 offset-6">
		</div>
        <?php
        if(isset($course_plans) && !empty($course_plans))
        {
            foreach($course_plans as $course_plan) 
            {
                $data[] = 
                [
                    'id'         => $course_plan['id'],
                    'formNumber' => $course_plan['formation_number'],
                    'coursePlan' => $course_plan['official_name'],
                    'begin_date' => Time::createFromFormat('Y-m-d', $course_plan['date_begin'])->toLocalizedString('dd.MM.Y'),
                    'active'     => isset($course_plan['archive']) ? lang('common_lang.no') : lang('common_lang.yes')
                ];
            }
            
            echo view('Common\Views\items_list',
            [
                'columns' =>
                [
                    'formNumber' =>lang('plafor_lang.field_course_plan_formation_number'),
                    'coursePlan' =>lang('plafor_lang.field_course_plan_official_name'),
                    'begin_date' =>lang('plafor_lang.field_course_plan_into_effect'),
                    'active'     => lang('plafor_lang.current')
                ],
                'items'             => $data,
                'primary_key_field' => 'id',
                'url_update'        => 'plafor/apprentice/save_user_course/'.$id_apprentice.'/',
                'url_delete'        => 'plafor/courseplan/delete_user_course/'
            ]);
        }?>
    </div>
</div>
