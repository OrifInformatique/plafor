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

        <?php
        $datas = [];
        // TODO : Call the model and arrange data in the controller, send the data to the view
        $competenceDomainModel = model('\Plafor\Models\CompetenceDomainModel');

        foreach ($competenceDomainModel->getOperationalCompetences($competence_domain['id'], $with_archived) as $operational_competence)
        {
            $datas[] =
            [
                'id'      => $operational_competence['id'],
                'symbol'  => $operational_competence['symbol'],
                'opComp'  => $operational_competence['name'],
                'archive' => $operational_competence['archive']
            ];
        }

        echo view('Common\Views\items_list',
        [
            'items'   => $datas,
            'columns' =>
            [
                'symbol' => lang('plafor_lang.symbol'),
                'opComp' => lang('plafor_lang.operational_competence')
            ],
            'with_deleted'  => true,
            'url_detail'    => 'plafor/courseplan/view_operational_competence/',
            'url_create'    => 'plafor/courseplan/save_operational_competence/'.$competence_domain['id'].'/0',
            'url_update'    => 'plafor/courseplan/save_operational_competence/'.$competence_domain['id'].'/',
            'url_delete'    => 'plafor/courseplan/delete_operational_competence/',
            'url_getView'   => 'plafor/courseplan/view_competence_domain/'.$competence_domain['id'],
            'url_restore'   => 'plafor/courseplan/delete_operational_competence/',
        ]);
        ?>
    </div>
</div>