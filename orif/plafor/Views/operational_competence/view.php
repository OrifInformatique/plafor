<?php
/**
 * Shows an operational competence and its linked objectives.
 *
 * Called by CoursePlan/view_operational_competence($operational_competence_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed by this view ***
 *
 * @param array $course_plan Course plan containing the operational competence.
 * All fields from table.
 *
 * @param array $competence_domain Competence domain containing the operational competence.
 * All fields from table.
 *
 * @param array $operational_competence Operational competence.
 * All fields from table.
 *
 * @param array $objectives Objectives linked to the operational competence.
 * All fields from table.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action CoursePlan/view_operational_competence($operational_competence_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form');
?>
<div class="container">
    <?=view('\Plafor\templates\navigator',['title'=>lang('plafor_lang.title_view_operational_competence')])?>
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h2 class="title-section"><?= $title; ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <?php if(isset($course_plan)): ?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_formation_number')?></p>
            <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['formation_number']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_official_name')?></p>
            <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['official_name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_competence_domain')?></p>
        </div>
        <?php if(isset($competence_domain)):?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_symbol')?></p>
            <a href="<?= base_url('plafor/courseplan/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['symbol']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_name')?></p>
            <a href="<?= base_url('plafor/courseplan/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if(isset($operational_competence)):?>
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_operational_competence')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_symbol')?></p>
            <p><?=$operational_competence['symbol']?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_name')?></p>
            <p><?=$operational_competence['name']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_methodologic')?></p>
            <p><?=$operational_competence['methodologic']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_social')?></p>
            <p><?=$operational_competence['social']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_personal')?></p>
            <p><?=$operational_competence['personal']?></p>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.field_linked_objectives')?></p>
        </div>

        <?= view('Common\Views\items_list',
        [
            'items'   => $objectives,
            'columns' =>
            [
                'symbol'   => lang('plafor_lang.field_objectives_symbols'),
                'taxonomy' => lang('plafor_lang.field_objectives_taxonomies'),
                'name'     => lang('plafor_lang.field_objectives_names')
            ],
            'with_deleted' => true,
            'url_detail'   => 'plafor/courseplan/view_objective/',
            'url_create'   => 'plafor/courseplan/save_objective/'.$operational_competence['id'],
            'url_update'   => 'plafor/courseplan/save_objective/'.$operational_competence['id'].'/',
            'url_delete'   => 'plafor/courseplan/delete_objective/1/',
            'url_getView'  => 'plafor/courseplan/view_operational_competence/'.$operational_competence['id'],
            'url_restore'  => 'plafor/courseplan/delete_objective/3/',
        ])
        ?>
    </div>
</div>
<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;
            history.replaceState(null,null,'<?=base_url('/plafor/courseplan/view_operational_competence/'.$operational_competence['id']);?>?wa='+(checked?1:0))
            $.get('<?=base_url('/plafor/courseplan/view_operational_competence/')."/${operational_competence['id']}";?>?wa='+(checked?1:0),(datas)=>{
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
<script defer type="text/javascript">
    window.addEventListener('resize',()=>{

        if (window.innerWidth<490){

            if (document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)!==null?window.getComputedStyle(document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)).display!=="none":false)
                document.querySelectorAll('.responsiveTable td:nth-child(2)').forEach((element)=>{
                let tax=document.createElement('span');
                tax.innerHTML=`<span class='taxonomyResponsive'>${element.innerHTML}<span>`

                element.previousElementSibling.appendChild(tax);
                element.remove();

            })
        }
        else{
            document.querySelectorAll('.responsiveTable td:nth-child(1) .taxonomyResponsive').forEach((element)=>{
                let td=document.createElement('td');
                td.innerHTML=element.innerHTML;
                if (element.parentElement.parentElement.nextElementSibling.querySelector('.descTitle')==null)
                element.parentElement.parentElement.after(td);
                element.remove();
            })
        }

    });
    if (window.innerWidth<490){

        if (document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)!==null?window.getComputedStyle(document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)).display!=="none":false)
            document.querySelectorAll('.responsiveTable td:nth-child(2)').forEach((element)=>{
                let tax=document.createElement('span');
                tax.innerHTML=`<span class='taxonomyResponsive'>${element.innerHTML}<span>`

                element.previousElementSibling.appendChild(tax);
                element.remove();

            })
    }
    else{
        document.querySelectorAll('.responsiveTable td:nth-child(1) .taxonomyResponsive').forEach((element)=>{
            let td=document.createElement('td');
            td.innerHTML=element.innerHTML;
            if (element.parentElement.parentElement.nextElementSibling.querySelector('.descTitle')==null)
                element.parentElement.parentElement.after(td);
            element.remove();
        })
    }
</script>