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
            <h1 class="title-section"><?= lang('plafor_lang.title_course_plan_list'); ?></h1>
        </div>
    </div>
    <div class="row" style="justify-content:space-between">
        <div class="col-sm-3">
            <a href="<?= base_url('plafor/courseplan/save_course_plan'); ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_new_m'); ?>
            </a>
        </div>
        <div class="col-sm-9 text-right">
            <?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label','style'=>'padding-right:30px']);?>

            <?=form_checkbox('toggle_deleted', '', $with_archived, [
                'id' => 'toggle_deleted', 'class' => 'form-check-input'
            ]);?>
        </div>
    </div>
    <div class="row mt-2">
     <div class="col-sm-3 offset-6">
		</div>
        <?php
        $datas=[];
        foreach($course_plans as $course_plan) {
            $datas[]=['id'=>$course_plan['id'],'formNumber'=>$course_plan['formation_number'],'coursePlan'=>$course_plan['official_name'],'begin_date'=>Time::createFromFormat('Y-m-d', $course_plan['date_begin'])->toLocalizedString('dd.MM.Y')];
        }
        ?>

        <?= view('Common\Views\items_list',[
            'columns'=>[
                'formNumber'=>lang('plafor_lang.field_course_plan_formation_number'),
                'coursePlan'=>lang('plafor_lang.field_course_plan_official_name'),
                'begin_date'=>lang('plafor_lang.field_course_plan_into_effect')
            ],
            'items'=>$datas,
            'primary_key_field'=>'id',
            'url_update'=>'plafor/courseplan/save_course_plan/',
            'url_delete'=>'plafor/courseplan/delete_course_plan/',
            'url_detail'=>'plafor/courseplan/view_course_plan/',
        ])?>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;
            history.replaceState(null,null,'<?=base_url('/plafor/courseplan/list_course_plan');?>?wa='+(checked?1:0))
            $.get('<?=base_url('/plafor/courseplan/list_course_plan');?>?wa='+(checked?1:0),(datas)=>{
                let parser=new DOMParser();
                parser.parseFromString(datas,'text/html').querySelectorAll('table').forEach((domTag)=>{
                    document.querySelectorAll('table').forEach((thisDomTag)=>{
                        thisDomTag.innerHTML=domTag.innerHTML;
                    })
                })
            })
        })
    });
</script>
