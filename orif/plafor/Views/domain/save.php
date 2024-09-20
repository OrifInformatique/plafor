<?php

/**
 * Let the edition of a teaching domain.
 *
 * Called by TeachingDomainController/saveTeachingDomain($course_plan_id, $domain_id)
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
 * Should be lang('Grades.update_domain') or lang('Grades.create_domain');
 *
 * @param ?int $domain_id ID of the domain.
 * To edit an existng entry.
 *
 * @param array $parent_course_plan Domain's parent course plan.
 * One entry. Structure below.
 * [
 *      'id'            => int,    ID of the course plan. Required.
 *      'official_name' => string, Official name of the course plan. Required
 * ]
 *
 * @param array $domain_names List of all teaching domains titles.
 * All fields from table.
 *
 * @param ?int $domain_name Name of the domain, stored as domain title ID.
 * To edit an existing entry or remember user input.
 *
 * @param ?int $domain_weight Weighting of the domain (in CFC average).
 * To edit an existing entry or remember user input.
 *
 * @param ?bool $is_domain_eliminatory Defines whether the domain is eliminatory.
 * To edit an existing entry or remember user input.
 *
 * @param array $errors teaching_domain_model errors. Can be empty.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action TeachingDomainController/saveTeachingDomain($course_plan_id, $domain_id)
 *
 * @param ?int $domain_name Name of the domain, stored as domain title ID.
 * Required if $new_domain_name == null.
 *
 * @param ?string $new_domain_name Name of the domain.
 * Required if $domain_name == null.
 *
 * @param float $domain_weight Weighting of the domain (in CFC average).
 *
 * @param bool $is_domain_eliminatory Defines whether the domain is eliminatory.
 *
 * === NOTES ===
 *
 * $domain_name and $new_domain_name should not
 * be both defined when submtting the form.
 * Prevent this case by returning an error in the controller.
 *
 */



// /* Random data set for testing, can be deleted anytime */
// $title = lang('Grades.create_domain'); // Titre de la page

$domain_id = null; // ID du domaine (à éditer, peut être vide)

$parent_course_plan = [
    'id' => 345,
    'official_name' => 'Computer Science Engineering', // Nom officiel du plan de cours
];

$domain_names = [
    1 => 'Software Development',
    2 => 'Network Administration',
    3 => 'Cybersecurity',
];

$domain_name = 1; // ID du titre du domaine sélectionné (peut être vide)

$domain_weight = 40; // Ponderation du domaine (peut être vide)

$is_domain_eliminatory = false; // Le domaine est-il éliminatoire (peut être vide)

$errors = []; // Erreurs du modèle teaching_domain_model (peut être vide)

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

helper('form');

$new_domain_name = lang('Grades.name').' '.lang('Grades.of_a').' '.
    strtolower(lang('common_lang.btn_new_m').' '.lang('Grades.domain'));

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Form errors -->
    <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingDomain/'.$parent_course_plan['id'].'/'.($domain_id ?? 0))) ?>
        <div class="row">
            <!-- Domain name dropdown (visible by default) -->
            <div class="col-9 form-group" id="dropdown-container">
                <?= form_label(lang('Grades.name'), 'domain_name',
                    ['class' => 'form-label']) ?>

                <!-- TODO : List all domain names + first empty option for form_dropdown options -->
                <?= form_dropdown('domain_name', $domain_names, $domain_name ?? '',
                    ['class' => 'form-control', 'id' => 'domain_name']) ?>
            </div>

            <!-- New domain name text input (hidden by default) -->
            <div class="col-9 form-group" id="textinput-container" style="display: none;">
                <?= form_label($new_domain_name, 'new_domain_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('new_domain_name', '',
                    ['class' => 'form-control', 'id' => 'new_domain_name']) ?>
            </div>

            <!-- Button to switch between choosing an existing name and adding a new teaching domain name -->
            <div class="col form-group text-right align-content-end">
                <button type="button" class="btn btn-primary" id="toggleButton" style="cursor: pointer;">
                    <?= lang('common_lang.btn_new_m'). ' '. strtolower(lang('Grades.name')) ?>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.domain_parent_course_plan'), '',
                    ['class' => 'form-label']) ?>

                <?= form_input(null, $parent_course_plan['official_name'],
                    ['class' => 'form-control', 'disabled' => true]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 form-group">
                <?= form_label(lang('Grades.weighting_in_%'), 'domain_weight',
                    ['class' => 'form-label']) ?>

                <?= form_input('domain_weight', $domain_weight ?? '',
                    ['class' => 'form-control', 'id' => 'domain_weight', 'min' => 0, 'max' => 100], 'number') ?>
            </div>

            <div class="col-sm-3 form-group form-check form-check-inline">
                <?= form_label(lang('Grades.is_eliminatory'), 'is_domain_eliminatory',
                    ['class' => 'form-check-label mr-2']) ?>

                <?= form_checkbox('is_domain_eliminatory', true, $is_domain_eliminatory ?? false,
                    ['class' => 'form-check-input', 'id' => 'is_domain_eliminatory']) ?>
            </div>
        </div>

        <div class="row">
            <?php if($domain_id > 0): ?>
                <div class="col">
                    <a href="<?= base_url('plafor/teachingdomain/deleteTeachingDomain/'.$domain_id) ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_course_plan/'.$parent_course_plan['id']) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>

<script>
    // This code toggles the domain name input between
    // choosing an existing name and creating a new name.
    // (Generated with ChatGPT)
    document.addEventListener("DOMContentLoaded", function()
    {
        const dropdownContainer = document.getElementById('dropdown-container');
        const textInputContainer = document.getElementById('textinput-container');

        const dropdown = document.getElementById('domain_name');
        const textInput = document.getElementById('new_domain_name');

        const toggleButton = document.getElementById('toggleButton');

        let isTextInputVisible = false;

        // Toggle between dropdown and text input
        toggleButton.addEventListener('click', function()
        {
            if (isTextInputVisible)
            {
                // Show the dropdown and hide the text input
                dropdownContainer.style.display = 'block';

                textInputContainer.style.display = 'none';
                textInput.value = '';

                toggleButton.textContent = "<?= lang('common_lang.btn_new_m') . ' ' . strtolower(lang('Grades.name')) ?>";
            }

            else
            {
                // Show the text input and hide the dropdown
                textInputContainer.style.display = 'block';

                dropdownContainer.style.display = 'none';
                dropdown.selectedIndex = 0;

                toggleButton.textContent = "<?= lang('common_lang.btn_cancel') ?>";
            }

            isTextInputVisible = !isTextInputVisible;
        });
    });
</script>