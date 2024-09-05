<?php

/**
 * Domain save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?int $domain_id ID of the domain.
 *
 * @param array $domains List of all domains. All database fields required.
 *
 * @param ?int $selected_domain Domain selected when posting data.
 *
 * @param array $course_plans List of all course_plans. Structure of one course plan below.
 * [
 *      'id'            => int,    ID of the course plan. Required.
 *      'official_name' => string, Name of the course plan. Required.
 * ]
 *
 * @param ?int $selected_course_plan Course plan selected when posting data.
 *
 * @param ?int $domain_weight Weighting of the domain (in CFC average).
 *
 * @param ?bool $is_domain_eliminatory Defines whether the domain is eliminatory.
 *
 * === NOTES ===
 *
 * Params are provided when editing an existing module,
 * or conserving user inputs after an incorrect form completion.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @param int $domain_id ID of the domain.
 *
 * @param int $selected_domain Domain selected when posting data.
 *
 * @param int $selected_course_plan Course plan selected when posting data.
 *
 * @param int $domain_weight Weighting of the domain (in CFC average).
 *
 * @param bool $is_domain_eliminatory Defines whether the domain is eliminatory.
 *
 * === NOTES ===
 *
 * $domain_id is optional.
 *
 * If provided, an existing entry will be updated.
 * If empty or '0', a new entry will be created.
 *
 */



/**
 * Data management
 *
 */

// TODO : Move data management in controller

if(isset($domain_id) && $domain_id > 0)
    $title = lang('Grades.update_domain');

else
{
    $title = lang('Grades.create_domain');
    $domain_id = 0;
}

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= $title ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveTeachingDomain'), [], ['domain_id' => $domain_id]) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.name'), 'domain_name', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert domains list as form_dropdown $options -->
                <?= form_dropdown('domain_name', 'Insert domains here', null, ['class' => 'form-control', 'id' => 'domain_name']) ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('Grades.domain_parent_course_plan'), 'domain_parent_course_plan', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert course_plans list as form_dropdown $options -->
                <!-- TODO : When creating a domain (no ID or ID == 0), preselect the course_plan we came from -->
                <?= form_dropdown('domain_parent_course_plan', 'Insert course_plans here', null, ['class' => 'form-control', 'id' => 'domain_parent_course_plan']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 form-group">
                <?= form_label(lang('Grades.weighting'), 'domain_weight', ['class' => 'form-label']) ?><br>
                <?= form_input(null, 1, ['class' => 'form-control', 'id' => 'domain_weight', 'min' => 0, 'max' => 1, 'step' => 0.1], 'number') ?>
            </div>

            <div class="col-sm-3 form-group form-check form-check-inline">
                <?= form_label(lang('Grades.is_eliminatory'), 'is_domain_eliminatory', ['class' => 'form-check-label mr-2']) ?><br>
                <?= form_checkbox('is_domain_eliminatory', '', false, ['class' => 'form-check-input', 'id' => 'is_domain_eliminatory']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <!-- TODO : Get the course_plan id to redirect to the course_plan details -->
                <?php $course_plan_id = 1; ?>
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan_id) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>