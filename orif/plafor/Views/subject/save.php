<?php

/**
 * Subject save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?int $subject_id ID of the subject.
 *
 * @param ?string $subject_name Name of the subject.
 *
 * @param ?float $subject_weight Weighting of the subject (in the domain average).
 *
 * @param ?int $subject_parent_domain Parent domain of the subject, stored as domain ID.
 *
 * === NOTES ===
 *
 * No param is required.
 *
 * Params are provided when editing an existing subject,
 * or conserving user inputs after an incorrect form completion.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @param int $subject_id ID of the subject.
 *
 * @param string $subject_name Name of the subject.
 *
 * @param float $subject_weight Weighting of the subject (in the domain average).
 *
 * @param int $subject_parent_domain Parent domain of the subject, stored as domain ID.
 *
 */



/**
 * Data management
 *
 */

// TODO : Move data management into the controller displaying this view.

if(isset($subject_id) && $subject_id > 0)
    $title = lang('Grades.update_subject');

else
{
    $title = lang('Grades.create_subject');
    $subject_id = 0;
}

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= $title ?></h2>
    </div>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingSubject'), [], ['subject_id' => $subject_id]) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.name'), 'subject_name', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $subject_name ?? '', ['class' => 'form-control', 'id' => 'subject_name']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.weighting'), 'subject_weight', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $subject_weight ?? 0, ['class' => 'form-control', 'id' => 'subject_weight', 'min' => 0, 'max' => 1, 'step' => 0.1], 'number') ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('Grades.subject_parent_domain'), 'subject_parent_domain', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert domains list as form_dropdown $options -->
                <?= form_dropdown('subject_parent_domain', 'Insert domains here', $subject_parent_domain ?? null, ['class' => 'form-control', 'id' => 'subject_parent_domain']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <!-- TODO : Get the course_plan we came from for cancel button -->
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_course_plan/1') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>