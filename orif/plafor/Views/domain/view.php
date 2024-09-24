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
 *          'archive'   => string, Date of soft deletion. Can be empty.
 *      ]
 *      'modules' => array, List of linked modules. Can be empty.
 *      Structure of one module below.
 *      [
 *          'id'        => int,    ID of the module. Required.
 *          'number'    => int,    Number of the module. Required
 *          'title'     => string, Name of the module. Required.
 *          'archive'   => string, Date of soft deletion. Can be empty.
 *      ]
 *      'weighting'      => float, Weighting of the domain (in CFC average). Required.
 *      'is_eliminatory' => bool,  Determines whether a domain is eliminatory. Required.
 *      'archive'   => string, Date of soft deletion. Can be empty.
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
 $teaching_domains = [
    [
        'id' => 1,
        'name' => 'Software Development',
        'subjects' => [
            [
                'id' => 101,
                'name' => 'Object-Oriented Programming',
                'weighting' => 0.5,
                'archive' => true,
            ],
            [
                'id' => 102,
                'name' => 'Databases',
                'weighting' => 0.5,
            ],
        ],
        'modules' => [], // Pas de modules pour ce domaine
        'weighting' => 0.6,
        'is_eliminatory' => true,
    ],
    [
        'id' => 2,
        'name' => 'Network Administration',
        'subjects' => [], // Pas de matières pour ce domaine
        'modules' => [
            [
                'id' => 201,
                'number' => 1,
                'title' => 'Introduction to Networks',
                'archive' => true,
            ],
            [
                'id' => 202,
                'number' => 2,
                'title' => 'Advanced Networking',
            ],
        ],
        'weighting' => 0.4,
        'is_eliminatory' => false,
        'archive' => true
    ],
];

helper('form');

?>

<style>
    a:hover
    {
        text-decoration: none
    }
</style>

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
            <div class="col-sm-6 d-flex justify-content-end align-content-center">
                <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted_teaching_domains_subjects',
                    ['class' => 'form-check-label mr-2', 'style' => 'margin-top: 5.5px;']) ?>

                <?= form_checkbox('toggle_deleted', '', $with_deleted,
                    ['class' => 'align-self-center', 'id' => 'toggle_deleted_teaching_domains_subjects',
                    'style' => 'width: 20px; height: 20px;']) ?>
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
                        <?= isset($teaching_domain['archive']) ? '<del>' : '' ?>
                            <?= $teaching_domain['name'] ?>
                        <?= isset($teaching_domain['archive']) ? '</del>' : '' ?>
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
                                                <?= isset($teaching_subject['archive']) ? '<del>' : '' ?>
                                                    <?= $teaching_subject['name'] ?>
                                                <?= isset($teaching_subject['archive']) ? '</del>' : '' ?>
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
                                        <th>
                                            <?= isset($module['archive']) ? '<del>' : '' ?>
                                                <?= $module['number'] ?>
                                            <?= isset($module['archive']) ? '</del>' : '' ?>
                                        </th>

                                        <th>
                                            <a href="<?= base_url('plafor/teachingdomain/saveTeachingModule/'.$module['id']) ?>">
                                                <?= isset($module['archive']) ? '<del>' : '' ?>
                                                    <?= $module['title'] ?>
                                                <?= isset($module['archive']) ? '</del>' : '' ?>
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

<script>
        $(document).ready(() =>
        {
            $('#toggle_deleted_teaching_domains_subjects').change(e =>
            {
                let checked = e.currentTarget.checked;

                history.replaceState(null, null, '<?= base_url('plafor/courseplan/view_course_plan/'.$parent_course_plan_id) ?>?wads=' + (checked ? 1 : 0))
                $.get('<?= base_url('plafor/courseplan/view_course_plan/'.$parent_course_plan_id) ?>?wads=' + (checked ? 1 : 0), (datas) => {
                    let parser = new DOMParser();

                    parser.parseFromString(datas, 'text/html').querySelectorAll('#itemsList').forEach((domTag) =>
                    {
                        document.querySelectorAll('#itemsList').forEach((thisDomTag) =>
                        {
                            thisDomTag.innerHTML = domTag.innerHTML;
                        })
                    })
                })
            })
        });
    </script>