<?php

/**
 * Grade save view
 *
 * Called by GradeController/saveGrade()
 *
 * @author      Orif (Dedy)
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
 * @param array $apprentce Apprentice who made the grade.
 * One entry. For view purposes.
 * [
 *      'id'       => int,    ID of the apprentice. Required.
 *      'username' => string, Username of the apprentice. Required.
 * ]
 *
 * @param string $course_plan Name of the course_plan we insert a grade in.
 * For view purposes.
 *
 * @param array $subject_and_domains_list List of subjects and domains contained in the course plan.
 * [
 *      lang('Grades.subjects') => array, List of sujects contained in the course_plan. Required.
 *          Array of key-values where keys are subjects IDs with a 's' before and values are subject names.
 *
 *      lang('Grades.modules') => array, List of modules contained in the course_plan. Required.
 *          Array of key-values where keys are modules IDs with a 'm' before and values are modules names.
 * ]
 *
 * @param ?string $selected_entry ID of the selected entry.
 * To remember user input.
 *
 * @param ?int $grade Value of the grade.
 * To remember user input.
 *
 * @param ?Date $exam_date Date of the exam.
 * To remember user input.
 *
 * @param ?bool $is_exam_made_in_school Defines whether the exam has been made in school or not (concerns modules).
 * To remember user input.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @action GradeController/saveGrade($grade_id)
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



/* Random data set for testing, can be deleted anytime */
$grade_id = 0; // ID de la note

$user_course_id = 98765; // ID du user_course de l'apprenti

$apprentice = [
    'id' => 2,
    'username' => 'jane_smith', // Nom d'utilisateur de l'apprenti
];

$course_plan = "Computer Science"; // Nom du course_plan

$subject_and_domains_list = [
    lang('Grades.subjects') => [
        's101' => 'Data Structures',
        's102' => 'Operating Systems',
        's103' => 'Networks',
    ],
    lang('Grades.modules') => [
        'm101' => 'Module 1: Introduction to Data Structures',
        'm102' => 'Module 2: Operating Systems Internals',
    ],
];

$selected_entry = 's102';

$grade = 5; // Valeur de la note (peut être laissée vide)

$exam_date = '2024-07-01'; // Date de l'examen (peut être laissée vide)

$is_exam_made_in_school = true; // Indication si l'examen a été réalisé à l'école

/**
 * Data management
 *
 */

// TODO : Move data management in controller

if(isset($grade_id) && $grade_id > 0)
    $title = lang('Grades.update_grade');

else
{
    $title = lang('Grades.add_grade');
    $grade_id = 0;
}

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= $title ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveGrade/'.$grade_id), [], ['user_course_id' => $user_course_id]) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.apprentice'), 'apprentice', ['class' => 'form-label']) ?><br>
                <?= form_input('apprentice', $apprentice['username'], ['class' => 'form-control', 'id' => 'apprentice', 'disabled' => true]) ?>
            </div>
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.course_plan'), 'course_plan', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $course_plan, ['class' => 'form-control', 'id' => 'course_plan', 'disabled' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.subject').' '.lang('Grades.or').' '.strtolower(lang('Grades.module')), 'subject', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert all subjects teached to the apprentice + empty first slot as form_dropdown $options -->
                <!-- TODO : Disable form_dropdown if a module is selected -->
                <?= form_dropdown('subject', $subject_and_domains_list, $selected_entry ?? null, ['class' => 'form-control', 'id' => 'subject']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.grade'), 'grade', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $grade ?? 0, ['class' => 'form-control', 'id' => 'grade', 'min' => 0, 'max' => 6, 'step' => 0.5], 'number') ?>
            </div>

            <div class="col-sm-4 form-group">
                <?= form_label(lang('Grades.exam_date'), 'exam_date', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $exam_date ?? '', ['class' => 'form-control', 'id' => 'exam_date'], 'date') ?>
            </div>

            <div class="col-sm-4 form-group form-check form-check-inline">
                <?= form_label(lang('Grades.is_exam_made_at_school'), 'is_exam_made_at_school', ['class' => 'form-check-label mr-2']) ?>
                <!-- TODO : Auto-check and put on read-only the checkbox if a subject is selected -->
                <?= form_checkbox('is_exam_made_at_school', true, $is_exam_made_in_school ?? false, ['class' => 'form-check-input', 'id' => 'is_exam_made_at_school']) ?>
            </div>
        </div>

        <div class="row">
            <?php if($grade_id > 0): ?>
                <div class="col">
                    <a href="<?= base_url('plafor/grade/deleteGrade/'.$grade_id) ?>" class="btn btn-danger text-left">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

            <div class="col text-right">
                <!-- TODO : Append $apprentice['id'] in cancel button base_url() link -->
                <a class="btn btn-secondary" href="<?= base_url('plafor/apprentice/view_apprentice/') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>