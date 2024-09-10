<?php

/**
 * Domains linked to a course plan view
 *
 * Called by \Views/course_plan/view, himself called by CoursePlan/view_course_plan($course_plan_id)
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $teaching_domains Teaching domains linked to a course plan. Required. Structure of one domain below.
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
 *      'weighing'       => float, Weighting of the domain (in CFC average). Required.
 *      'is_eliminatory' => bool,  Determines whether a domain is eliminatory. Required.
 * ]
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

helper('form');

?>

<div class="row mt-3">
    <!-- Section title  -->
    <div class="col-12">
        <p class="bg-primary text-white"><?= lang('Grades.domains') ?></p>
    </div>

    <!-- Buttons + Checkbox  -->
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <div>
                <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin):?>
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingDomain') ?>" class="btn btn-primary">
                        <?=lang ('common_lang.btn_new_m').' '.substr(strtolower(lang('Grades.domain')), 0, 7) ?>
                    </a>

                    <a href="<?=base_url('plafor/teachingdomain/saveTeachingSubject')?>" class="btn btn-primary">
                        <?= lang('common_lang.btn_new_f').' '.strtolower(lang('Grades.subject')) ?>
                    </a>
                <?php endif?>
            </div>

            <!-- TODO : Make the checkbox display deleted teaching domains AND teaching subjects when checked -->
            <div>
                <?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted_teaching_domains', ['class' => 'form-check-label','style'=>'padding-right:30px']);?>
                <?=form_checkbox('toggle_deleted', '', isset($with_archived) ? $with_archived : false, ['id' => 'toggle_deleted_teaching_domains', 'class' => 'form-check-input']);
                ?>
            </div>
        </div>
    </div>

    <!-- Domains list -->
    <div class="col-12 mt-2">
        <?php foreach($teaching_domains as $teaching_domain): ?>

            <!-- Domain details -->
            <div class="row mt-5 m-2 pt-2 border-top border-bottom border-primary align-items-center">
                <p class="col-6 h3 text-center">
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingDomain/'.$teaching_domain['id']) ?>">
                        <?= $teaching_domain['name'] ?>
                    </a>
                </p>

                <p class="col-3 text-center">
                    <?= lang('Grades.weighting') ?><br>

                    <strong><?= $teaching_domain['weighting'] ?></strong>
                </p>

                <p class="col-3 text-center">
                    <?= lang('Grades.is_eliminatory') ?><br>

                    <strong>
                        <?= $teaching_domain['is_eliminatory'] ? lang('common_lang.yes') : lang('common_lang.no') ?>
                    </strong>
                </p>
            </div>

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
                                            <a href="<?= base_url('plafor/teachingdomain/saveTeachingSubject/'.$teaching_subject['id']) ?>">
                                                <?= $teaching_subject['name'] ?>
                                            </a>
                                        </th>

                                        <th><?= $teaching_subject['weighting'] ?></th>
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

                        <!-- Domain school modules -->
                        <table class="table table-striped mt-2">
                            <thead>
                                <th><?= lang('Grades.module_number') ?></th>
                                <th><?= lang('Grades.module_name') ?></th>
                            </thead>

                            <tbody>
                                <?php foreach($teaching_domain['modules'] as $module): ?>
                                    <tr>
                                        <th>
                                            <a href="<?= base_url('plafor/teachingdomain/saveTeachingModule/'.$module['id']) ?>">
                                                <?= $module['number'] ?>
                                            </a>
                                        </th>

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