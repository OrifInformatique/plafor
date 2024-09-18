<?php

/**
 * Let the user link modules he selects with a domain.
 *
 * Called by TeachingDomainController/saveTeachingModuleLink($domain_id)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $modules List of all modules.
 * Structure of one module below.
 * [
 *      'id'        => int,    ID of the module. Required.
 *      'number'    => int,    Nunber of the module. Required.
 *      'name'      => string, Name of the module. Required.
 *      'is_linked' => bool,   Defines whether the module is linked to the domain. Required.
 * ]
 *
 * @param int $domain_id ID of the domain we link modules to.
 * For redirection.
 *
 * @param int $parent_course_plan_id Id of the parent course plan.
 * For redirection.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @action TeachingDomainController/saveTeachingModuleLink($domain_id)
 *
 * @param bool $submitted Tells that the form is submitted. Value is always true.
 * To let the possibility to perform actions on form submit when no checkbox is checked.
 *
 * The form will submit all checked checkboxes seperately.
 * $_POST will look like an array of key-values, where the keys are modules numbers, and the values are modules IDs.
 *
 */

/* Random data set for testing, can be deleted anytime */
$modules = [
    [
        'id' => 1,
        'number' => 101,
        'name' => 'Introduction to ProgrammingIntroduction to ProgrammingIntroduction to Programming',
        'is_linked' => true,
    ],
    [
        'id' => 2,
        'number' => 102,
        'name' => 'Advanced Algorithms',
        'is_linked' => false,
    ],
    [
        'id' => 3,
        'number' => 103,
        'name' => 'Database Management',
        'is_linked' => true,
    ],
    [
        'id' => 4,
        'number' => 104,
        'name' => 'Web Development',
        'is_linked' => true,
    ],
    [
        'id' => 5,
        'number' => 105,
        'name' => 'Operating Systems',
        'is_linked' => false,
    ],
    [
        'id' => 6,
        'number' => 106,
        'name' => 'Computer Networks',
        'is_linked' => true,
    ],
    [
        'id' => 7,
        'number' => 107,
        'name' => 'Software Engineering',
        'is_linked' => false,
    ],
    [
        'id' => 8,
        'number' => 108,
        'name' => 'Cybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity FundamentalsCybersecurity Fundamentals',
        'is_linked' => true,
    ],
    [
        'id' => 9,
        'number' => 109,
        'name' => 'Cloud Computing',
        'is_linked' => false,
    ],
    [
        'id' => 10,
        'number' => 110,
        'name' => 'Machine Learning',
        'is_linked' => true,
    ],
];

$domain_id = 1;
$parent_course_plan_id = 1;

helper('form');

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => lang('Grades.link_modules_to_domain')]) ?>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingModuleLink/'.$domain_id),
        [], ['submitted' => true]) ?>
        <div class="row">
            <div class="col">
                <p class="bg-primary text-white m-0"><?= lang('Grades.modules_list') ?></p>
            </div>
        </div>

        <div class="row my-3">
            <div class="col">
                <button type="button" class="btn btn-primary" id="selectAllBtn">
                    <?= lang('Grades.select_all') ?>
                </button>

                <button type="button" class="btn btn-primary" id="deselectAllBtn">
                    <?= lang('Grades.deselect_all') ?>
                </button>
            </div>
        </div>

        <?php foreach($modules as $module): ?>
            <div class="row py-2 border-bottom border-primary">
                <div class="col-11 form-group mx-0 my-1">
                    <?= form_label('<strong>'.$module['number'].'</strong> - '. $module['name'], $module['number'],
                        ['class' => 'form-label m-0']) ?>
                </div>
                <div class="col d-flex justify-content-center align-items-center">
                    <?= form_checkbox($module['number'], $module['id'], $module['is_linked'],
                        ['class' => 'form-check', 'id' => $module['number'],
                        'style' => 'width: 22px; height: 22px;']) ?>
                </div>
            </div>
        <?php endforeach ?>

        <div class="row my-4">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_course_plan/'.$parent_course_plan_id) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>

<script>
    // This code let the user quickly
    // select or deselect all modules.
    // Generated with ChatGPT.
    document.addEventListener("DOMContentLoaded", () =>
    {
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');

        // Get all checkboxes
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');

        // Function to select all checkboxes
        selectAllBtn.addEventListener('click', () =>
        {
            checkboxes.forEach((checkbox) =>
            {
                checkbox.checked = true;
            });
        });

        // Function to deselect all checkboxes
        deselectAllBtn.addEventListener('click', () =>
        {
            checkboxes.forEach((checkbox) =>
            {
                checkbox.checked = false;
            });
        });
    });
</script>
