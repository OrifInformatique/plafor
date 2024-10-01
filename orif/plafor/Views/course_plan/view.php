<?php
/**
 * Fichier de vue pour view_course_plan
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $course_plan Course plan we want to see its details.
 * All fields from table.
 *
 * @param array $competence_domains Competence domains linked to the course plan.
 * All fields from table.
 *
 * @param array $teaching_domains Teaching domains linked to the course plan.
 * See \Views/domain/view for data structure needed.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action CoursePlan/view_course_plan($course_plan_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form');

?>

<div class="container">
    <?=view('\Plafor\templates\navigator',['title'=>lang('plafor_lang.title_view_course_plan')])?>
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h2 class="title-section"><?= $title; ?></h2>
        </div>
    </div>
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
    </div>
    <div class="row">
        <div class="col-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_competence_domains_linked')?></p>
        </div>
        <div class="col-12">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin):?>
                    <a href="<?=base_url('plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/0/')?>" class="btn btn-primary"><?=lang('common_lang.btn_new_m')?></a>
                <?php endif; ?>
                <span>
                <?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label','style'=>'padding-right:30px']);?>
                <?=form_checkbox('toggle_deleted', '', isset($with_archived)?$with_archived:false, [
                    'id' => 'toggle_deleted', 'class' => 'form-check-input'
                ]);?>
                </span>
            </div>

        <?= view('Common\Views\items_list',
        [
            'items'   => $competence_domains,
            'columns' =>
            [
                'symbol'  => lang('plafor_lang.symbol'),
                'name' => lang('plafor_lang.competence_domain')
            ],
            'with_deleted'  => true,
            'url_detail'    => 'plafor/courseplan/view_competence_domain/',
            'url_create'    => 'plafor/courseplan/save_competence_domain/'.$course_plan['id'],
            'url_update'    => 'plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/',
            'url_delete'    => 'plafor/courseplan/delete_competence_domain/1/',
            'url_getView'   => 'plafor/courseplan/view_course_plan/'.$course_plan['id'],
            'url_restore'   => 'plafor/courseplan/delete_competence_domain/3/',
        ]);
        ?>
    </div>
</div>
<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;
            history.replaceState(null,null,'<?=base_url('/plafor/courseplan/view_course_plan/'.$course_plan['id']);?>?wa='+(checked?1:0))
            $.get('<?=base_url('/plafor/courseplan/view_course_plan/')."/${course_plan['id']}";?>?wa='+(checked?1:0),(datas)=>{
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