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

helper(['form', 'AccessPermissions_helper']);

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
                <?php if(hasCurrentUserAdminAccess()): ?>
                    <a href="<?= base_url('plafor/teachingdomain/saveTeachingDomain/'.$parent_course_plan_id) ?>" class="btn btn-primary">
                        <?=lang ('common_lang.btn_new_m').' '.substr(strtolower(lang('Grades.domain')), 0, 7) ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="col-sm-6 d-flex justify-content-end align-content-center">
                <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted_teaching_domains_subjects',
                    ['class' => 'form-check-label mr-2', 'style' => 'margin-top: 5.5px;']) ?>

                <?= form_checkbox('toggle_deleted', '', false,
                    ['class' => 'align-self-center', 'id' => 'toggle_deleted_teaching_domains_subjects',
                    'style' => 'width: 20px; height: 20px;']) ?>
            </div>
        </div>
    </div>

    <!-- Domains list -->
    <div id="teachingDomain" class="col-12 mt-2">
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

            <?php if(hasCurrentUserAdminAccess()): ?>
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
                                        <td>
                                            <a href="<?= base_url('plafor/teachingdomain/saveTeachingSubject/'.
                                                $teaching_domain['id'].'/'.$teaching_subject['id']) ?>">
                                                <?= isset($teaching_subject['archive']) ? '<del>' : '' ?>
                                                    <?= $teaching_subject['name'] ?>
                                                <?= isset($teaching_subject['archive']) ? '</del>' : '' ?>
                                            </a>
                                        </td>

                                        <td><?= $teaching_subject['weighting'] * 100 ?> %</td>
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
                                        <td>
                                            <?= isset($module['archive']) ? '<del>' : '' ?>
                                                <?= $module['number'] ?>
                                            <?= isset($module['archive']) ? '</del>' : '' ?>
                                        </td>

                                        <td>
                                            <?= isset($module['archive']) ? '<del>' : '' ?>
                                                <?= $module['title'] ?>
                                            <?= isset($module['archive']) ? '</del>' : '' ?>
                                        </td>
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

<!-- JQuery script to refresh items list after user action -->
<?php if(isset($url_getView)): ?>
    <script>
        $(document).ready(() =>
        {
            $('#toggle_deleted_teaching_domains_subjects').change(e =>
            {
                let checked = e.currentTarget.checked;

                history.replaceState(null, null, '<?= base_url($url_getView) ?>?wb=' + (checked ? 1 : 0))
                $.get('<?= base_url($url_getView) ?>?wb=' + (checked ? 1 : 0), (datas) => {
                    let parser = new DOMParser();

                    parser.parseFromString(datas, 'text/html').querySelectorAll('#teachingDomain').forEach((domTag) =>
                    {
                        document.querySelectorAll('#teachingDomain').forEach((thisDomTag) =>
                        {
                            thisDomTag.innerHTML = domTag.innerHTML;
                        })
                    })
                })
            })
        });
    </script>
<?php endif ?>