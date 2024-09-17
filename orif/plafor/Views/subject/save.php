<?php

/**
 * Let the edition of a teaching subject.
 *
 * Called by TeachingDomainController/saveTeachingSubject($subject_id)
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
 * @param int $subject_id ID of the subject.
 *
 * @param ?string $subject_name Name of the subject.
 * To edit an existing entry or remember user input.
 *
 * @param ?float $subject_weight Weighting of the subject (in the domain average).
 * To edit an existing entry or remember user input.
 *
 * @param array $domains List of all domains.
 * Array of key-values where keys are domains IDs and values are domains names.
 *
 * @param ?int $subject_parent_domain Parent domain of the subject, stored as domain ID.
 * To edit an existing entry or remember user input.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action TeachingDomainController/saveTeachingSubject($subject_id)
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



/* Random data set for testing, can be deleted anytime */
$title = lang('Grades.create_subject'); // Titre de la page

$subject_id = 4567; // ID de la matière

$subject_name = 'Database Management'; // Nom de la matière (peut être laissé vide)

$subject_weight = 25.0; // Ponderation de la matière (peut être laissé vide)

$subject_parent_domain = 1234; // ID du domaine parent (peut être laissé vide)

$domains = [
    101 => 'ECG',
    102 => 'CBE',
    103 => 'TPI'
];

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
        <div class="col">
            <h2><?= $title ?></h2>
        </div>
    </div>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingSubject/'.$subject_id)) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.name'), 'subject_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('subject_name', $subject_name ?? '',
                    ['class' => 'form-control', 'id' => 'subject_name']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.weighting'), 'subject_weight',
                    ['class' => 'form-label']) ?>

                <?= form_input('subject_weight', $subject_weight ?? '',
                    ['class' => 'form-control', 'id' => 'subject_weight', 'min' => 0, 'max' => 1, 'step' => 0.1], 'number') ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('Grades.subject_parent_domain'), 'subject_parent_domain',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('subject_parent_domain', $domains, $subject_parent_domain ?? null,
                    ['class' => 'form-control', 'id' => 'subject_parent_domain']) ?>
            </div>
        </div>

        <div class="row">
            <?php if($subject_id > 0): ?>
                <div class="col">
                    <a href="<?= base_url('plafor/teachingdomain/deleteSubject/'.$subject_id) ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

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