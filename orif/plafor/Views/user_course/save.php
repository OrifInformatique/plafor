<?php

/**
 * Lists all course plan followed by an apprentice.
 *
 * Called by Apprentice/save_user_course($id_apprentice, id_user_course)
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * // TODO : Add the page title as a param
 *
 * @param array $course_plans List of all course plans.
 * Array of key-values where keys are course_plans IDs and values are course_plans official names.
 *
 * @param array $user_course Existing user course.
 * All fields from table.
 * For entry update.
 *
 * @param array $status List of all user course statuses.
 * Array of key-values where keys are user course statuses IDs and values are user courses statuses names.
 *
 * // TODO : Only give apprentice ID and username : only values needed
 * @param array $apprentice Apprentice.
 * All fields from table.
 *
 * @param ?array $errors user_course_model errors.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action Apprentice/save_user_courses($id_apprentice, id_user_course)
 *
 * @param int $id ID of the apprentice.
 *
 * @param int $course_plan Course plan followed by the user, stored as course plan ID.
 *
 * @param Date $date_begin The date where the apprentice starts following the course plan.
 *
 * @param Date $date_end The date where the apprentice ends following the course plan.
 *
 * @param int $status Status of the formation, stored as user course status ID.
 *
 */

helper('form');

?>

<div class="container">
    <div class="row">
        <div class="col">
            <h2>
                <?= $apprentice['username'].' - '.
                    lang('plafor_lang.title_user_course_'.(!empty($user_course) ? 'update' : 'new')) ?>
            </h2>
        </div>
    </div>

    <?= form_open('plafor/apprentice/save_user_course/'.$apprentice['id'].'/'.(!empty($user_course) ? $user_course['id'] : ''),
        [], ['id' => $apprentice['id'] ?? 0]) ?>

        <?php foreach ($errors != null ? $errors : [] as $error): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endforeach ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_course_plan'), 'course_plan',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('course_plan', $course_plans, $user_course['fk_course_plan'] ?? '',
                    ['id' => 'course_plan', 'class' => 'form-control',
                    'style' => !empty($user_course) ? 'pointer-events: none; background-color: rgba(0, 0, 0, 0.15);' : '']) ?>
            </div>

            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_date_begin'), 'user_course_date_begin',
                    ['class' => 'form-label']) ?>

                <?= form_input('date_begin', $user_course['date_begin'] ?? date("Y-m-d"),
                    ['class' => 'form-control', 'id' => 'user_course_date_begin'], 'date') ?>
            </div>

            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_date_end'), 'user_course_date_end',
                    ['class' => 'form-label']) ?>

                <?= form_input('date_end', $user_course['date_end'] ?? '',
                    ['class' => 'form-control', 'id' => 'user_course_date_end'], 'date') ?>
            </div>

            <div class="col-sm-4 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_status'), 'status',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('status', $status, $user_course['fk_status'] ?? '',
                    ['class' => 'form-control', 'id' => 'status']) ?>
            </div>
        </div>

        <div class="col text-right">
            <a class="btn btn-secondary" href="<?= base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']) ?>">
                <?= lang('common_lang.btn_cancel') ?>
            </a>

            <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?= form_close() ?>
</div>