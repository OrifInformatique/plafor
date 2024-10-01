<?php

/**
 * Let the edition of a course plan.
 *
 * Called by CoursePlan/save_course_plan($course_plan_id)
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
 * @param ?array $course_plan Edited course plan.
 *
 * @param ?array $errors course_plan_model errors.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action CoursePlan/save_course_plan($course_plan_id)
 *
 * @param int $formation_number Number of the course plan.
 *
 * @param string $official_name Name of the course plan.
 *
 * @param Date $date_begin Creation date of the course plan.
 *
 */

helper('form');

$formation_number_max = str_repeat(9, config('\Plafor\Config\PlaforConfig')->FORMATION_NUMBER_MAX_LENGTH);
$offical_name_max_length = config('\Plafor\Config\PlaforConfig')->OFFICIAL_NAME_MAX_LENGTH;

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <?= form_open(base_url('plafor/courseplan/save_course_plan/'.($course_plan['id'] ?? 0))) ?>
        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_course_plan_formation_number'), 'formation_number',
                    ['class' => 'form-label']) ?>

                <?= form_input('formation_number', $course_plan['formation_number'] ?? '',
                    ['class' => 'form-control', 'max' => $formation_number_max], 'number') ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_course_plan_official_name'), 'official_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('official_name', $course_plan['official_name'] ?? '',
                    ['class' => 'form-control', 'maxlength' => $offical_name_max_length]) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_course_plan_date_begin'), 'date_begin',
                    ['class' => 'form-label']) ?>

                <?= form_input('date_begin', $course_plan['date_begin'] ?? '',
                    ['class' => 'form-control'], 'date') ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/list_course_plan') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>