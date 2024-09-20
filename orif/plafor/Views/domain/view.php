<?php

/**
 * Shows the domains linked to a course plan.
 *
 * Called by \Views/course_plan/view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $teaching_domains Teaching domains linked to a course plan.
 * Structure of one domain below.
 * [
 *      'id'       => int,    ID of the domain. Required.
 *      'name'     => string, Name of the domain. Required.
 *      'subjects' => array,  List of linked subjects. Can be empty.
 *      Structure of one subject below.
 *      [
 *          'id'        => int,    ID of the subject. Required.
 *          'name'      => string, Name of the subject. Required.
 *          'weighting' => float,  Weighing of the subject (in the domain average). Reqiured.
 *      ]
 *      'modules' => array, List of linked modules. Can be empty.
 *      Structure of one module below.
 *      [
 *          'id'        => int,    ID of the module. Required.
 *          'number'    => int,    Number of the module. Required
 *          'title'     => string, Name of the module. Required.
 *      ]
 *      'weighting'      => float, Weighting of the domain (in CFC average). Required.
 *      'is_eliminatory' => bool,  Determines whether a domain is eliminatory. Required.
 * ]
 *
 * @param int $parent_course_plan_id Domain's parent course plan ID.
 *
 * === NOTES ===
 *
 * Even it's possible, we will prevent having a domain
 * that have subjects and modules. (XOR logic)
 *
 */



/**
 * No data is sent by this view.
 *
 */

/* Random data set for testing, can be deleted anytime */
//  $teaching_domains = [
//     [
//         'id' => 1,
//         'name' => 'Software Development',
//         'subjects' => [
//             [
//                 'id' => 101,
//                 'name' => 'Object-Oriented Programming',
//                 'weighting' => 0.5,
//             ],
//             [
//                 'id' => 102,
//                 'name' => 'Databases',
//                 'weighting' => 0.5,
//             ],
//         ],
//         'modules' => [], // Pas de modules pour ce domaine
//         'weighting' => 0.6,
//         'is_eliminatory' => true,
//     ],
//     [
//         'id' => 2,
//         'name' => 'Network Administration',
//         'subjects' => [], // Pas de matières pour ce domaine
//         'modules' => [
//             [
//                 'id' => 201,
//                 'number' => 1,
//                 'title' => 'Introduction to Networks',
//             ],
//             [
//                 'id' => 202,
//                 'number' => 2,
//                 'title' => 'Advanced Networking',
//             ],
//         ],
//         'weighting' => 0.4,
//         'is_eliminatory' => false,
//     ],
// ];

// $parent_course_plan_id = 1;

helper('form');

?>

<div class="row mt-3">
    <div class="col-12">
        <p class="bg-primary text-white"><?= lang('Grades.domains') ?></p>
    </div>

    <!-- Buttons + Checkbox  -->
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <div>
                <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_admin): ?>
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingDomain/'.$parent_course_plan_id) ?>" class="btn btn-primary">
                        <?=lang ('common_lang.btn_new_m').' '.substr(strtolower(lang('Grades.domain')), 0, 7) ?>
                    </a>
                <?php endif ?>
            </div>

            <!-- TODO : Make the checkbox display deleted teaching domains AND teaching subjects AND teaching modules when checked -->
            <div>
                <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted_teaching_domains',
                    ['class' => 'form-check-label', 'style'=>'padding-right: 30px;']) ?>

                <?= form_checkbox('toggle_deleted', '', isset($with_archived) ? $with_archived : false,
                    ['class' => 'form-check-input', 'id' => 'toggle_deleted_teaching_domains']) ?>
            </div>
        </div>
    </div>

    <!-- Domains list -->
    <div class="col-12 mt-2">
        <?php foreach($teaching_domains as $teaching_domain): ?>
            <!-- Domain details -->
            <div class="row mt-3 m-2 pt-2 border-top border-bottom border-primary align-items-center">
                <p class="col-6 h3 text-center">
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingDomain/'.$parent_course_plan_id.'/'.$teaching_domain['id']) ?>">
                        <?= $teaching_domain['name'] ?>
                    </a>
                </p>

                <p class="col-3 text-center">
                    <?= lang('Grades.weighting') ?><br>
                    <strong><?= $teaching_domain['weighting'] * 100 ?> %</strong>
                </p>

                <p class="col-3 text-center">
                    <?= lang('Grades.is_eliminatory') ?><br>
                    <strong>
                        <?= $teaching_domain['is_eliminatory'] ? lang('common_lang.yes') : lang('common_lang.no') ?>
                    </strong>
                </p>
            </div>

            <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_admin): ?>
                <div class="m-2">
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingSubject/'.$teaching_domain['id']) ?>" class="btn btn-primary">
                        <?= lang('common_lang.btn_new_f').' '.strtolower(lang('Grades.subject')) ?>
                    </a>

                    <!-- TODO : Create method to link modules to domain -->
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingModuleLink/'.$teaching_domain['id']) ?>" class="btn btn-primary">
                        <?= lang('Grades.link_modules') ?>
                    </a>
                </div>
            <?php endif ?>

            <?php if(!empty($teaching_domain['subjects'])): ?>
                <!-- Domain subjects -->
                <div class="row mb-4">
                    <!-- Subjects list -->
                    <div class="col-11 m-auto">
                        <p class="bg-secondary"><?= lang('Grades.subjects') ?></p>

                        <table class="table table-striped mt-2">
                            <thead>
                                <th><?= lang('Grades.subject') ?></th>
                                <th><?= lang('Grades.weighting') ?></th>
                            </thead>

                            <tbody>
                                <?php foreach($teaching_domain['subjects'] as $teaching_subject): ?>
                                    <tr>
                                        <th>
                                            <a href="<?= base_url('plafor/teachingdomain/saveTeachingSubject/'.
                                                $teaching_domain['id'].'/'.$teaching_subject['id']) ?>">
                                                <?= $teaching_subject['name'] ?>
                                            </a>
                                        </th>

                                        <th><?= $teaching_subject['weighting'] * 100 ?> %</th>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>

            <?php if(!empty($teaching_domain['modules'])): ?>
                <!-- Domain modules -->
                <div class="row mb-4">
                    <div class="col-11 m-auto">
                        <p class="bg-secondary mb-0"><?= lang('Grades.modules') ?></p>

                        <table class="table table-striped mt-2">
                            <thead>
                                <th><?= lang('Grades.module_number') ?></th>
                                <th><?= lang('Grades.module_name') ?></th>
                            </thead>

                            <tbody>
                                <?php foreach($teaching_domain['modules'] as $module): ?>
                                    <tr>
                                        <th><?= $module['number'] ?></th>

                                        <th>
                                            <a href="<?= base_url('plafor/teachingdomain/saveTeachingModule/'.$module['id']) ?>">
                                                <?= $module['title'] ?>
                                            </a>
                                        </th>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>