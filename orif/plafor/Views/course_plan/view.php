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
 * @param string $title Page title.
 *
 * @param array $course_plan Course plan we want to see its details.
 *
 * @param array $competence_domains Competence domains linked to the course plan.
 *
 * @param array $teaching_domains Teaching domains linked to the course plan.
 *
 */



/**
 * No data is sent by this view
 *
 */

helper('form');

?>

<div class="container">
    <?=view('\Plafor\templates\navigator',['title'=>lang('plafor_lang.title_view_course_plan')])?>

    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2 class="title-section"><?= $title ?></h2>
        </div>
    </div>

    <!-- Course plan details -->
    <div class="row">
        <div class="col-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_course_plan') ?></p>
        </div>

        <div class="col-12">
            <p class="font-weight-bold"><?= $course_plan['official_name'] ?></p>
        </div>

        <div class="col-12">
            <p><?= lang('plafor_lang.number_abr').' '.$course_plan['formation_number'].', '
               .mb_strtolower(lang('plafor_lang.field_course_plan_into_effect')).' '.$course_plan['date_begin'] ?>
            </p>
        </div>
    </div>

    <!-- Linked competence domains -->
    <div class="row">
        <div class="col-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_competence_domains_linked') ?></p>
        </div>

        <div class="col-12">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin): ?>
                    <a href="<?=base_url('plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/0/')?>" class="btn btn-primary">
                        <?= lang('common_lang.btn_new_m') ?>
                    </a>
                <?php endif?>

                <!-- TODO : Make the checkbox display deleted competence domains when checked -->
                <span>
                <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted_competence_domains', ['class' => 'form-check-label','style'=>'padding-right:30px']); ?>
                <?= form_checkbox('toggle_deleted', '', isset($with_archived)?$with_archived:false, [
                    'id' => 'toggle_deleted', 'class' => 'form-check-input'
                ]); ?>
                </span>
            </div>

            <?php

            $datas = [];

            foreach ($competence_domains as $competence_domain)
                $datas[] =
                [
                    'id'      => $competence_domain['id'],
                    'symbol'  => $competence_domain['symbol'],
                    'compDom' => $competence_domain['name']
                ];
            ?>

            <?= view('Common\Views\items_list',
            [
                'columns' =>
                [
                    'symbol'  => lang('plafor_lang.symbol'),
                    'compDom' => lang('plafor_lang.competence_domain')
                ],
                'items'             => $datas,
                'primary_key_field' => 'id',
                'url_update'        => 'plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/',
                'url_delete'        => 'plafor/courseplan/delete_competence_domain/',
                'url_detail'        => 'plafor/courseplan/view_competence_domain/'
            ]);
            ?>
        </div>
    </div>

    <!-- Linked teaching domains -->
    <?= view('\Plafor/domain/view', $teaching_domains) ?>

</div>

<!-- TODO : Make the checkbox display deleted competence domains when checked -->
<script defer>
    $(document).ready(function(){
        $('#toggle_deleted_competence_domains').change(e => {
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