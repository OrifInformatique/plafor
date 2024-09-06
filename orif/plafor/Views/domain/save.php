<?php

/**
 * Domain save view
 *
 * Called by TeachingDomainController/saveTeachingDomain()
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Page title.
 * Should be lang('Grades.update_domain') or lang('Grades.create_domain');
 *
 * @param int $domain_id ID of the domain.
 *
 * @param ?int $domain_name Name of the domain.
 * To edit an existing entry or remember user input.
 *
 * @param array $course_plans List of all course_plans. Structure of one course plan below.
 * Array of key-values where keys are course_plans IDs and values are course_plans official names.
 *
 * @param ?int $domain_parent_course_plan Course plan selected when posting data.
 * To edit an existing entry or remember user input.
 *
 * @param ?int $domain_weight Weighting of the domain (in CFC average).
 * To edit an existing entry or remember user input.
 *
 * @param ?bool $is_domain_eliminatory Defines whether the domain is eliminatory.
 * To edit an existing entry or remember user input.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @action TeachingDomainController/saveTeachingDomain($domain_id)
 *
 * @param int $domain_id ID of the domain.
 *
 * @param string $domain_name Name of the domain.
 *
 * @param int $domain_parent_course_plan Course plan selected when posting data.
 *
 * @param int $domain_weight Weighting of the domain (in CFC average).
 *
 * @param bool $is_domain_eliminatory Defines whether the domain is eliminatory.
 *
 */



/* Random data set for testing, can be deleted anytime */
$title = lang('Grades.create_domain'); // Titre de la page

$domain_id = 7890; // ID du domaine

$domain_name = 'Software Development'; // Nom du domaine (peut être laissé vide)

$course_plans = [
        101 => 'Computer Science Engineering',
        102 => 'Information Systems',
        103 => 'Quantum Algorythms',
];

$selected_course_plan = 102; // ID du course_plan sélectionné (peut être laissé vide)

$domain_weight = 0.7; // Ponderation du domaine (peut être laissé vide)

$is_domain_eliminatory = false; // Le domaine est-il éliminatoire (peut être laissé vide)

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

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingDomain/'.$domain_id)) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.name'), 'domain_name', ['class' => 'form-label']) ?><br>
                <?= form_input('domain_name', $domain_name ?? '', ['class' => 'form-control', 'id' => 'domain_name']) ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('Grades.domain_parent_course_plan'), 'domain_parent_course_plan', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert course_plans list as form_dropdown $options -->
                <!-- TODO : When creating a domain (no ID or ID == 0), preselect the course_plan we came from -->
                <?= form_dropdown('domain_parent_course_plan', $course_plans, $selected_course_plan ?? null, ['class' => 'form-control', 'id' => 'domain_parent_course_plan']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 form-group">
                <?= form_label(lang('Grades.weighting'), 'domain_weight', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $domain_weight ?? '', ['class' => 'form-control', 'id' => 'domain_weight', 'min' => 0, 'max' => 1, 'step' => 0.1], 'number') ?>
            </div>

            <div class="col-sm-3 form-group form-check form-check-inline">
                <?= form_label(lang('Grades.is_eliminatory'), 'is_domain_eliminatory', ['class' => 'form-check-label mr-2']) ?><br>
                <?= form_checkbox('is_domain_eliminatory', '', $is_domain_eliminatory ?? false, ['class' => 'form-check-input', 'id' => 'is_domain_eliminatory']) ?>
            </div>
        </div>

        <div class="row">
            <?php if($domain_id > 0): ?>
                <div class="col">
                    <a href="<?= base_url('plafor/teachingdomain/deleteDomain/'.$domain_id) ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

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