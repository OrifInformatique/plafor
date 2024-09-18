<?php

/**
 * Shows a competence domain and its linked operational competences.
 *
 * Called by CoursePlan/view_competence_domain($comp_domain_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * // TODO : Directly put the title in the view, then delete this param
 * @param string $title Page title.
 *
 * @param array $course_plan Parent course plan of the competence domain.
 * All fields from table.
 *
 * @param array $competence_domain Competence domain.
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
 * action CoursePlan/view_competence_domain($comp_domain_id)
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
            <p class="bg-primary text-white">
                <?= lang('plafor_lang.title_view_operational_competence_linked') ?>
            </p>
        </div>

        <div class="col-12">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_admin): ?>
                    <a href="<?= base_url('plafor/courseplan/save_operational_competence/'.$competence_domain['id'].'/0') ?>" class="btn btn-primary">
                        <?= lang('common_lang.btn_new_f') ?>
                    </a>
                <?php endif ?>

                <div>
                    <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted',
                        ['class' => 'form-check-label', 'style'=>'padding-right: 30px;']) ?>

                    <?= form_checkbox('toggle_deleted', '', $with_archived,
                        ['id' => 'toggle_deleted', 'class' => 'form-check-input']) ?>
                </div>
            </div>

            <?php
            $datas = [];
            // TODO : Call the model and arrange data in the controller, send the data to the view
            $competenceDomainModel = model('\Plafor\Models\CompetenceDomainModel');

            foreach ($competenceDomainModel->getOperationalCompetences($competence_domain['id'],$with_archived) as $operational_competence)
            {
                $datas[] =
                [
                    'id'     => $operational_competence['id'],
                    'symbol' => $operational_competence['symbol'],
                    'opComp' => $operational_competence['name']
                ];
            }

            echo view('Common\Views\items_list',
            [
                'columns' =>
                [
                    'symbol' => lang('plafor_lang.symbol'),
                    'opComp' => lang('plafor_lang.operational_competence')
                ],
                'items'             => $datas,
                'primary_key_field' => 'id',
                'url_update'        => 'plafor/courseplan/save_operational_competence/'.$competence_domain['id'].'/',
                'url_delete'        => 'plafor/courseplan/delete_operational_competence/',
                'url_detail'        => 'plafor/courseplan/view_operational_competence/',
            ]);
            ?>
        </div>
    </div>
</div>

<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;

            history.replaceState(null, null, '<?= base_url('/plafor/courseplan/view_competence_domain/'.$competence_domain['id']) ?>?wa='+(checked ? 1 : 0))

            $.get('<?= base_url('/plafor/courseplan/view_competence_domain/').$competence_domain['id'] ?>?wa='+(checked ? 1 : 0), (datas) => {
                let parser = new DOMParser();

                parser.parseFromString(datas,'text/html').querySelectorAll('table').forEach((domTag) => {
                    document.querySelectorAll('table').forEach((thisDomTag) => {
                        thisDomTag.innerHTML = domTag.innerHTML;
                    })
                })
            })
        })
    });
</script>