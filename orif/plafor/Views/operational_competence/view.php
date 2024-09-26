<?php

/**
 * Shows an operational competence and its linked objectives.
 *
 * Called by CoursePlan/view_operational_competence($operational_comp_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed by this view ***
 *
 * // TODO : Directly put the title in the view, then delete this param
 * @param string $title Page title.
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
 * action CoursePlan/view_operational_competence($operational_comp_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form');

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_operational_competence')]) ?>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Course plan details -->
    <?= view('\Plafor/course_plan/details', $course_plan) ?>

    <!-- Competence domain details -->
    <?= view('\Plafor/competence_domain/details', $competence_domain) ?>

    <!-- Operational competence details -->
    <?= view('\Plafor/operational_competence/details', $operational_competence) ?>

    <!-- Linked objectives -->
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
            'url_create'   => 'plafor/courseplan/save_objective/' . $operational_competence['id'] . '/0',
            'url_update'   => 'plafor/courseplan/save_objective/' . $operational_competence['id'] . '/',
            'url_delete'   => 'plafor/courseplan/delete_objective/',
            'url_getView'  => 'plafor/courseplan/view_operational_competence/' . $operational_competence['id'],
            'url_restore'  => 'plafor/courseplan/delete_objective/',
        ])
        ?>
    </div>
</div>