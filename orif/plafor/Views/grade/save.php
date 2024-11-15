<?php

/**
 * Let the edition of a grade.
 *
 * Called by GradeController/saveGrade($apprentice_id, $grade_id)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Title of the view.
 * Should be lang('Grades.update_grade') or lang('Grades.add_grade');
 *
 * @param int $grade_id ID of the grade.
 *
 * @param int $user_course_id ID of the apprentice user_course.
 *
 * @param array $apprentice Apprentice who made the grade.
 * One entry. For view purposes.
 * Structure below.
 * [
 *      'id'       => int,    ID of the apprentice. Required.
 *      'username' => string, Username of the apprentice. Required.
 * ]
 *
 * @param string $course_plan Name of the course_plan we insert a grade in.
 * For view purposes.
 *
 * @param array $subject_and_domains_list List of subjects and domains contained in the course plan.
 * Structure below.
 * [
 *      lang('Grades.subjects') => array, List of sujects contained in the course_plan. Required.
 *          Array of key-values where keys are subjects IDs with a 's' before and values are subject names.
 *
 *      lang('Grades.modules') => array, List of modules contained in the course_plan. Required.
 *          Array of key-values where keys are modules IDs with a 'm' before and values are modules names.
 * ]
 *
 * @param ?string $selected_entry ID of the selected entry.
 * To edit an existing entry or remember user input.
 *
 * @param ?int $grade Value of the grade.
 * To edit an existing entry or remember user input.
 *
 * @param ?Date $exam_date Date of the exam.
 * To edit an existing entry or remember user input.
 *
 * @param ?bool $is_exam_made_in_school Defines whether the exam has been made in school or not (concerns modules).
 * To edit an existing entry or remember user input.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action GradeController/saveGrade($apprentice_id, $grade_id)
 *
 * @param int $grade_id ID of the grade.
 *
 * @param int $user_course_id ID of the apprentice user_course.
 *
 * @param string $selected_entry ID of the selected entry.
 *
 * @param int $grade Value of the grade.
 *
 * @param Date $exam_date Date of the exam.
 *
 * @param bool $is_exam_made_in_school Defines whether the exam has been made in school or not (concerns modules).
 *
 */
helper('form');
$subject_or_module_label = lang('Grades.subject').' '.lang('Grades.or').' '.strtolower(lang('Grades.module'));

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>
    <?= form_open(url_to('updateGrade', $user_course_id, $grade_id), [],
        ['user_course_id' => $user_course_id]) ?>

        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>


        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.apprentice'), 'apprentice',
                    ['class' => 'form-label']) ?>

                <?= form_input('apprentice', $apprentice['username'],
                    ['class' => 'form-control', 'disabled' => true]) ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('plafor_lang.course_plan'), 'course_plan',
                    ['class' => 'form-label']) ?>

                <?= form_input('course_plan', $course_plan,
                    ['class' => 'form-control', 'disabled' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label($subject_or_module_label, 'subject',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('subject', $subject_and_domains_list, $selected_entry ?? null,
                    ['class' => 'form-control', 'id' => 'subject']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.grade'), 'grade',
                    ['class' => 'form-label']) ?>

                <?= form_input('grade', $grade ?? 0,
                    ['class' => 'form-control', 'id' => 'grade', 'min' => 0, 'max' => 6, 'step' => 0.5], 'number') ?>
            </div>

            <div class="col-sm-4 form-group">
                <?= form_label(lang('Grades.exam_date'), 'exam_date',
                    ['class' => 'form-label']) ?>

                <?= form_input('exam_date', $exam_date ?? '',
                    ['class' => 'form-control', 'id' => 'exam_date'], 'date') ?>
            </div>

            <div class="col-sm-4 form-group form-check form-check-inline">
                <?= form_label(lang('Grades.is_exam_made_at_school'), 'is_exam_made_at_school',
                    ['class' => 'form-check-label mr-2']) ?>
                <?php if ((array_key_first($subject_and_domains_list) ?? ' ')[0] === 's') :?>
                <?= form_checkbox('is_exam_made_at_school', true, $is_exam_made_in_school ?? false,
                    [ 'class' => 'form-check-input', 'id' => 'is_exam_made_at_school', 'disabled' => true]) ?>
                <?php else: ?>
                    <?= form_checkbox('is_exam_made_at_school', true, $is_exam_made_in_school ?? false,
                        ['class' => 'form-check-input', 'id' => 'is_exam_made_at_school']) ?>
                <?php endif ?>
            </div>
        </div>

        <div class="row">
            <?php if($grade_id > 0): ?>
                <div class="col">
                    <?php // 2 is hard deleted ?>
                    <a href="<?= url_to('deleteGrade', 2, $grade_id) ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/apprentice/view_apprentice/'. $apprentice['id'] . '/' . $user_course_id) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>
