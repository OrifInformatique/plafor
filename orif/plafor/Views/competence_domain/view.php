<?php
/**
 * Shows a competence domain and its linked operational competences.
 *
 * Called by CoursePlan/view_competence_domain($competence_domain_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $course_plan Parent course plan of the competence domain.
 * All fields from table.
 *
 * @param array $competence_domain Competence domain.
 * All fields from table.
 *
 * @param array $operational_competences Operational competences linked to the competence domain.
 * All fields from table.
 *
 * @param bool $with_archived Defines whether to show deleted entries.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action CoursePlan/view_competence_domain($competence_domain_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form')

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.details_competence_domain')]) ?>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Course plan details -->
    <?= view('\Plafor/course_plan/details', $course_plan) ?>

    <!-- Competence domain details -->
    <?= view('\Plafor/competence_domain/details', $competence_domain) ?>

    <!-- Linked operational competences -->
    <div class="row">
        <div class="col-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <div class="col-12">
            <p class="font-weight-bold"><?=$course_plan['official_name']?></p>
        </div>
        <div class="col-12">
            <p><?= lang('plafor_lang.number_abr').' '.$course_plan['formation_number'].', '
               .mb_strtolower(lang('plafor_lang.field_course_plan_into_effect')).' '.$course_plan['date_begin']?>
            </p>
        </div>

        <?= view('Common\Views\items_list',
        [
            'items'   => $operational_competences,
            'columns' =>
            [
                'symbol' => lang('plafor_lang.symbol'),
                'name'   => lang('plafor_lang.operational_competence')
            ],
            'with_deleted'  => true,
            'url_detail'    => 'plafor/courseplan/view_operational_competence/',
            'url_create'    => 'plafor/courseplan/save_operational_competence/'.$competence_domain['id'],
            'url_update'    => 'plafor/courseplan/save_operational_competence/'.$competence_domain['id'].'/',
            'url_delete'    => 'plafor/courseplan/delete_operational_competence/1/',
            'url_getView'   => 'plafor/courseplan/view_competence_domain/'.$competence_domain['id'],
            'url_restore'   => 'plafor/courseplan/delete_operational_competence/3/',
        ]);
        ?>
    </div>
</div>
<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;
            history.replaceState(null,null,'<?=base_url('/plafor/courseplan/view_competence_domain/'.$competence_domain['id']);?>?wa='+(checked?1:0))
            $.get('<?=base_url('/plafor/courseplan/view_competence_domain/')."/${competence_domain['id']}";?>?wa='+(checked?1:0),(datas)=>{
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
