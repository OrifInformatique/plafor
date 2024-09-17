<?php

/**
 * Shows the details of an objective.
 *
 * Called by CoursePlan/view_objective($objective_id)
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
 * @param array $course_plan Course plan containing the objective.
 * All fields from table.
 *
 * @param array $competence_domain Competence domain containing the objective.
 * All fields from table.
 *
 * @param array $operational_competence Operational competence containing the objective.
 * All fields from table.
 *
 * @param array $objective Objective.
 * All fields from table.
 *
 */



/**
 * No data is sent by this view.
 *
 */

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_objective')]) ?>

    <div class="row">
        <div class="col">
            <h2><?= $title ?></h2>
        </div>
    </div>

    <!-- Course plan details -->
    <?= view('\Plafor/course_plan/details', $course_plan) ?>

    <!-- Competence domain details -->
    <?= view('\Plafor/competence_domain/details', $competence_domain) ?>

    <!-- Operational competence details -->
    <?= view('\Plafor/operational_competence/details', $operational_competence) ?>

    <!-- Objective details -->
    <div class="row mb-3">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_objective') ?></p>
        </div>

        <div class="col-md-12">
            <p><strong><?= $objective['symbol'] ?></strong> : <?= $objective['name'] ?></p>
        </div>

        <div class="col-md-4">
            <p><strong><?= lang('plafor_lang.field_objective_taxonomy') ?></strong> : <?= $objective['taxonomy'] ?></p>
        </div>
    </div>
</div>