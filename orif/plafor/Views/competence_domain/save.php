<?php

/**
 * Let the edition of a competence domain.
 *
 * Called by CoursePlan/save_competence_domain($course_plan_id, $competence_domain_id)
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
 * @param int $competence_domain_id ID of the competence domain.
 *
 * @param ?array $competence_domain Existing competence domain.
 * All fields from table.
 * For entry update.
 *
 * @param array $course_plans List of course plans.
 * Array of key-values where keys are course plans IDs and values are course plans official names.
 * For select options.
 *
 * @param int fk_course_plan_id ID of the course plan.
 *
 * @param ?array $errors comp_domain_model errors.
 *
 */



/**
 * *** Data sent dy this view ***
 *
 * method POST
 *
 * action CoursePlan/save_competence_domain($course_plan_id, $competence_domain_id)
 *
 * @param int $id ID of the competence domain.
 *
 * @param string $type Type of the entry.
 * For plafor validation rules.
 *
 * @param int $course_plan Parent course plan, stored as course plan ID.
 *
 * @param string $symbol Symbol of the competence domain.
 *
 * @param string $name Name of the competence domain.
 *
 */

helper('form');

$symbol_max_length = config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH;
$name_max_length   = config('\Plafor\Config\PlaforConfig')->COMPETENCE_DOMAIN_NAME_MAX_LENGTH;

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <?= form_open(base_url('plafor/courseplan/save_competence_domain/'.$fk_course_plan_id.'/'.$competence_domain_id), null,
        ['id' => $competence_domain['id'] ?? 0, 'type' => 'competence_domain']) ?>

        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_competence_domain_course_plan'), 'course_plan',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('course_plan', $course_plans, $fk_course_plan_id ?? '',
                    ['class' => 'form-control']) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_competence_domain_symbol'), 'competence_domain_symbol',
                    ['class' => 'form-label']) ?>

                <?= form_input('symbol', $competence_domain['symbol'] ?? '',
                    ['class' => 'form-control', 'maxlength' => $symbol_max_length]) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_competence_domain_name'), 'competence_domain_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('name', $competence_domain['name'] ?? '',
                    ['class' => 'form-control', 'maxlength' => $name_max_length]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_course_plan/'
                    .($fk_course_plan_id != null ? $fk_course_plan_id : '')) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>