<?php

/**
 * Let the edition of a teaching subject.
 *
 * Called by TeachingDomainController/saveTeachingSubject($domain_id, $subject_id)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Page title.
 * Should be lang('Grades.update_subject') or lang('Grades.create_subject');
 *
 * @param array $parent_domain Subject's parent domain.
 * One entry. Structure below.
 * [
 *      'id'   => int,    ID of the domain. Required.
 *      'name' => string, Name of the domain. Required.
 * ]
 *
 * @param ?int $subject_id ID of the subject.
 *
 * @param ?string $subject_name Name of the subject.
 * To edit an existing entry or remember user input.
 *
 * @param ?float $subject_weight Weighting of the subject (in the domain average).
 * To edit an existing entry or remember user input.
 *
 * @param bool $is_subject_archived Defines whether the subject is archived.
 * To disable/delete/reactivate the entry.
 *
 * @param int $parent_course_plan_id ID of the parent course plan.
 * To redirect the user to the correct place.
 *
 * @param array $errors teaching_subject_model errors. Can be empty.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action TeachingDomainController/saveTeachingSubject($domain_id, $subject_id)
 *
 * @param string $subject_name Name of the subject.
 *
 * @param float $subject_weight Weighting of the subject (in the domain average).
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
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Form errors -->
    <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingSubject/').$parent_domain['id'].'/'.($subject_id ?? 0)) ?>
        <div class="row">
            <div class="col-9 form-group">
                <?= form_label(lang('Grades.name'), 'subject_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('subject_name', $subject_name ?? '',
                    ['class' => 'form-control', 'id' => 'subject_name']) ?>
            </div>

            <div class="col-3 form-group">
                <?= form_label(lang('Grades.weighting_in_%'), 'subject_weight',
                    ['class' => 'form-label']) ?>

                <?= form_input('subject_weight', $subject_weight ?? '',
                    ['class' => 'form-control', 'id' => 'subject_weight', 'min' => 0, 'max' => 100], 'number') ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
            <?= form_label(lang('Grades.subject_parent_domain'), '',
                    ['class' => 'form-label']) ?>

                <?= form_input(null, $parent_domain['name'],
                    ['class' => 'form-control', 'disabled' => true]) ?>
            </div>
        </div>

        <div class="row">
            <?php if($subject_id > 0): ?>
                <div class="col">
                    <?php if($is_subject_archived): ?>
                        <a href="<?= base_url('plafor/teachingdomain/deleteTeachingSubject/3/'.$subject_id) ?>" class="btn btn-warning">
                            <?= lang('common_lang.btn_reactivate') ?>
                        </a>

                        <a href="<?= base_url('plafor/teachingdomain/deleteTeachingSubject/2/'.$subject_id) ?>" class="btn btn-danger">
                            <?= lang('common_lang.btn_hard_delete') ?>
                        </a>

                    <?php else: ?>
                        <a href="<?= base_url('plafor/teachingdomain/deleteTeachingSubject/1/'.$subject_id) ?>" class="btn btn-warning">
                            <?= lang('common_lang.btn_disable') ?>
                        </a>

                    <?php endif ?>
                </div>
            <?php endif ?>

            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_course_plan/'.$parent_course_plan_id) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>