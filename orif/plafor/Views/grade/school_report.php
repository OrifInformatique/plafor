<?php

// TODO : Separate this file into multiple ones to imporve lisibility
// TODO : Refactor and optimise this file to imporve lisibility

/**
 * Shows the school report of an apprentice.
 *
 * Called by \Views/apprentice/view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?float $cfc_average Average of all domains of the apprentice, rounded by '0.1'.
 *
 * @param array $modules All modules teached to the apprentice.
 * [
 *      'school' => array, All school modules teached to the apprentice. Required.
 *      [
 *          'modules'    => array, List of school modules teached to the apprentice. Required.
 *          Structure of one module below.
 *          [
 *              'number' => int,    Number of the module. Required.
 *              'name'   => string, Name of the module. Required.
 *              'grade'  => array,  Grade obtained by the apprentice to the module. Can be empty.
 *              One grade per module.
 *              Structure below.
 *              [
 *                  'id'    => int,   ID of the grade. Required.
 *                  'value' => float, Value of the grade. Required.
 *              ]
 *          ]
 *          'weighting' => int, Weighting of school modules. Required.
 *          'average'   => int, Average of school modules. Can be empty.
 *      ]
 *      'non-school' => All non-school modules teached to the apprentice. Required.
 *      [
 *          'modules'    => List of non-school module teached to an apprentice.
 *          Same structure as school module.
 *          'weighting' => int, Weighting of non-school modules. Required.
 *          'average'   => Average of non-school modules. Can be empty.
 *      ]
 *      'weighting' => Weighting of modules (in CFC average). Required.
 *      'average' => Average of school (80%) and non-school (20%) averages. Can be empty.
 * ]
 *
 * @param ?array $tpi_grade Grade of the TPI obtained by the apprentice.
 * [
 *      'id'    => int,   ID of the grade. Can be empty. Required if 'value' is provided.
 *      'value' => float, Value of the grade. Can be empty. Required if 'id' is provided.
 * ]
 *
 * @param ?array $cbe All CBE subjects teached to the apprentice.
 * [
 *      'subjects'  => array, All subjects teached to the apprentice. Required.
 *      [
 *          'name'      => string, Name of the subject. Required.
 *          'grades'    => array,  Grades of the apprentice in the subject. Required.
 *          Must be 8 values length. Empty values are allowed.
 *          Must be ordered by date (semester).
 *          Structure of one grade below.
*           [
 *              'id'    => int,   ID of the grade. Can be empty. Required if 'value' is provided.
 *              'value' => float, Value of the grade. Can be empty. Required if 'id' is provided.
 *          ]
 *          'weighting' => int,    Weighting of the subject. Required.
 *          'average'   => int,    Average of the subject. Can be empty.
 *      ]
 *      'weighting' => int, Weighting of school modules. Required.
 *      'average'   => int, Average of all subjects. Can be empty.
 * ]
 *
 * @param ?array $ecg All ECG subjects teached to the apprentice. Same structure as CBE.
 *
 * === NOTES ===
 *
 * If necessary values are not provided, the school display will not display at all.
 * An error will instead appear.
 *
 * When developing, a debug message is provided, listing
 * all errors encountered when trying to display the view.
 *
 */



/**
 * No data is sent by this view.
 *
 */



/**
 * Data verification
 *
 * Below, we control data to prevent errors,
 * and set default values.
 *
 *
 */

// TODO : Move data verification into the controller displaying this view. Send the errors into this view.

$errors = [];

/* Averages */

if(empty($cfc_average)
    || !is_float($cfc_average)
    || $cfc_average < 0
    || $cfc_average > 6)
{
    $cfc_average = lang('Grades.unavailable_short');
}

if(empty($tpi_grade)
    || !is_float($tpi_grade['value'])
    || $tpi_grade['value'] < 0
    || $tpi_grade['value'] > 6)
{
    $tpi_grade['value'] = lang('Grades.unavailable_short');
}

if(empty($modules['average'])
    || !is_float($modules['average'])
    || $modules['average'] < 0
    || $modules['average'] > 6)
{
    $modules['average'] = lang('Grades.unavailable_short');
}

if(empty($modules['school']['average'])
    || !is_float($modules['school']['average'])
    || $modules['school']['average'] < 0
    || $modules['school']['average'] > 6)
{
    $modules['school']['average'] = lang('Grades.unavailable_short');
}

if(empty($modules['non-school']['average'])
    || !is_float($modules['non-school']['average'])
    || $modules['non-school']['average'] < 0
    || $modules['non-school']['average'] > 6)
{
    $modules['non-school']['average'] = lang('Grades.unavailable_short');
}

if(empty($cbe['average'])
    || !is_float($cbe['average'])
    || $cbe['average'] < 0
    || $cbe['average'] > 6)
{
    $cbe['average'] = lang('Grades.unavailable_short');
}

if(empty($ecg['average'])
    || !is_float($ecg['average'])
    || $ecg['average'] < 0
    || $ecg['average'] > 6)
{
    $ecg['average'] = lang('Grades.unavailable_short');
}



/* Modules */

if(empty($modules)
    || empty($modules['school'])
    || empty($modules['school']['modules'])
    || empty($modules['school']['weighting'])
    || empty($modules['non-school'])
    || empty($modules['non-school']['modules'])
    || empty($modules['non-school']['weighting']))
{
    $errors[] = "Values are missing in modules variable.";
}

else
{
    foreach($modules['school']['modules'] as $school_module)
    {
        if(empty($school_module['number'])
            || empty($school_module['name']))
        {
            $errors[] = "Values are missing in modules['school']['modules'] variable.";
            break;
        }
    }

    foreach($modules['non-school']['modules'] as $non_school_module)
    {
        if(empty($non_school_module['number'])
            || empty($non_school_module['name']))
        {
            $errors[] = "Values are missing in modules['non-school']['modules'] variable.";
            break;
        }
    }
}



/* CBE */

if(!empty($cbe)
    && (empty($cbe['subjects'])
        || empty($cbe['weighting']))
)
{
    $errors[] = "Values are missing in cbe variable.";
}

else
{
    foreach($cbe['subjects'] as $cbe_subject)
    {
        if(empty($cbe_subject['name'])
            || !isset($cbe_subject['weighting']))
        {
            $errors[] = "Values are missing in cbe['subjects'] variable.";
            break;
        }

        else if(empty($cbe_subject['grades']) || count($cbe_subject['grades']) !== 8)
        {
            $errors[] = "Array cbe['subjects']['grades'] does not contain 8 values.";
            break;
        }
    }
}



/* ECG */

if(!empty($ecg)
    && (empty($ecg['subjects'])
        || empty($ecg['weighting']))
)
{
    $errors[] = "Values are missing in ecg variable.";
}

else
{
    foreach($ecg['subjects'] as $ecg_subject)
    {
        if(empty($ecg_subject['name'])
        || !isset($ecg_subject['weighting']))
        {
            $errors[] = "Values are missing in ecg['subjects'] variable.";
            break;
        }

        else if(empty($ecg_subject['grades']) || count($ecg_subject['grades']) !== 8)
        {
            $errors[] = "Array ecg['subjects']['grades'] does not contain 8 values.";
            break;
        }
    }
}

?>

<div class="container">
    <p class="bg-primary text-white"><?= lang('Grades.school_report') ?></p>

    <?php if(!empty($errors)): ?>
        <p class="alert alert-warning"><?= lang('Grades.err_school_report_display') ?></p>

        <?php if(ENVIRONMENT != 'production'): ?>
            <div class="alert alert-danger">
                <p class="h3"><?= lang('Grades.error_details') ?></p>
                <?php foreach($errors as $error): ?>
                    <samp><?= $error ?></samp><br>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    <?php else: ?>
        <!-- Averages -->
        <div class="mb-5">
            <p class="alert alert-primary text-center">
                <strong><?= lang('Grades.global_average') ?></strong><br>
                <span class="display-4"><?= $cfc_average ?></span>
            </p>

            <div class="row">
                <div class="col-sm-3 border-right border-primary">
                    <p class="text-center">
                        <strong><?= lang('Grades.modules') ?></strong><br>
                        <span class="display-4"><?= $modules['average'] ?></span>
                    </p>
                </div>

                <div class="col-sm-3 border-right border-primary">
                    <p class="text-center">
                        <strong><?= lang('Grades.TPI_acronym') ?></strong><br>
                        <span class="display-4"><?= $tpi_grade['value'] ?></span>
                    </p>
                </div>

                <?php if(!empty($ecg)): ?>
                    <div class="col-sm-3 border-right border-primary">
                        <p class="text-center">
                            <strong><?= lang('Grades.ECG_acronym') ?></strong><br>
                            <span class="display-4"><?= $ecg['average'] ?></span>
                        </p>
                    </div>
                <?php endif ?>

                <?php if(!empty($cbe)): ?>
                    <div class="col-sm-3">
                        <p class="text-center">
                            <strong><?= lang('Grades.CBE_acronym') ?></strong><br>
                            <span class="display-4"><?= $cbe['average']?></span>
                        </p>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <!-- Modules -->
        <div class="mb-5">
            <p class="bg-secondary"><?= lang('Grades.modules') ?></p>

            <div class="mb-3">
                <a href="<?= url_to('insertGradeWithPreselect', $user_course_id, 0, 'modules' ) ?>" class="btn btn-primary">
                    <?= lang('Grades.add_grade') ?>
                </a>
            </div>

            <p class="border-left border-bottom border-primary pl-1">
                <?= lang('Grades.school_modules') ?>
            </p>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= lang('Grades.module_number') ?></th>
                        <th><?= lang('Grades.module_name') ?></th>
                        <th><?= lang('Grades.grade') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($modules['school']['modules'] as $school_module): ?>
                        <tr>
                            <td><?= $school_module['number'] ?></td>
                            <td><?= $school_module['name'] ?></td>
                            <td>
                                <a href="<?= url_to('updateGrade', $user_course_id, $school_module['grade']['id'])  ?>">
                                    <?= $school_module['grade']['value'] ?? '' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">
                            <?= lang('Grades.weighting')?> : <?= $modules['school']['weighting'].'%' ?>
                        </td>

                        <td>
                            <strong>
                                <?= $modules['school']['average'] ?? lang('Grades.unavailable_short') ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <p class="border-left border-bottom border-primary pl-1">
                <?= lang('Grades.non_school_modules') ?>
            </p>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= lang('Grades.module_number') ?></th>
                        <th><?= lang('Grades.module_name') ?></th>
                        <th><?= lang('Grades.grade') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($modules['non-school']['modules'] as $non_school_module): ?>
                        <tr>
                            <td><?= $non_school_module['number'] ?></td>
                            <td><?= $non_school_module['name'] ?></td>
                            <td>
                                <a href="<?= url_to('updateGrade', $user_course_id, $non_school_module['grade']['id']) ?>">
                                    <?= $non_school_module['grade']['value'] ?? '' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">
                            <?= lang('Grades.weighting')?> : <?= $modules['non-school']['weighting'].'%' ?>
                        </td>

                        <td>
                            <strong>
                                <?= $modules['non-school']['average'] ?? lang('Grades.unavailable_short') ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <p class="border-left border-bottom border-primary pl-1">
                <?= lang('Grades.average') ?>
            </p>

            <table class="table table-borderless">
                <tr>
                    <td class="text-right">
                        <?= lang('Grades.weighting')?> : <?= $modules['weighting'].'%' ?>
                    </td>

                    <td>
                        <strong>
                            <?= $modules['average'] ?? lang('Grades.unavailable_short') ?>
                        </strong>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TPI -->
        <div class="mb-5">
            <p class="bg-secondary"><?= lang('Grades.TPI_long') ?></p>

            <div class="mb-3">
                <a href="<?= url_to('insertGradeWithPreselect', $user_course_id, 0, 'tpi' ) ?>" class="btn btn-primary">
                    <?= lang('Grades.add_grade') ?>
                </a>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= lang('Grades.TPI_long') ?></th>
                        <th><?= lang('Grades.grade') ?></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><?= lang('Grades.TPI_long') ?></td>

                        <td>
                            <strong>
                                <a href="<?= url_to('updateGrade', $user_course_id, $tpi_grade['id']) ?>">
                                    <?= $tpi_grade['value'] ?>
                                </a>
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- CBE -->
        <?php if(!empty($cbe)): ?>
            <div class="mb-5">
                <p class="bg-secondary"><?= lang('Grades.CBE_long') ?></p>

                <div class="mb-3">
                <a href="<?= url_to('insertGradeWithPreselect', $user_course_id, 0, 'cbe' ) ?>" class="btn btn-primary">
                        <?= lang('Grades.add_grade') ?>
                    </a>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= lang('Grades.subject') ?></th>

                            <?php for($i = 1; $i <= 8; $i++): ?>
                                <th class="text-center">
                                    <?= substr(lang('Grades.semester'), 0, 3).'. '.$i ?>
                                </th>
                            <?php endfor ?>

                            <th><?= lang('Grades.average') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($cbe['subjects'] as $cbe_subject): ?>
                            <tr>
                                <td><?= $cbe_subject['name'] ?></td>

                                <?php foreach($cbe_subject['grades'] as $cbe_subject_grade): ?>
                                    <td class="text-center">
                                        <a href="<?= url_to('updateGrade', $user_course_id, $cbe_subject_grade['id']) ?>">
                                            <?= $cbe_subject_grade['value'] ?? '' ?>
                                        </a>
                                    </td>
                                <?php endforeach ?>

                                <td><?= $cbe_subject['average'] ?? lang('Grades.unavailable_short') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="9" class="text-right">
                                <?= lang('Grades.weighting')?> : <?= $cbe['weighting'].'%' ?>
                            </td>

                            <td>
                                <strong>
                                    <?= $cbe['average'] ?? lang('Grades.unavailable_short') ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif ?>

        <!-- ECG -->
        <?php if(!empty($ecg)): ?>
            <div class="mb-5">
                <p class="bg-secondary"><?= lang('Grades.ECG_long') ?></p>

                <div class="mb-3">
                <a href="<?= url_to('insertGradeWithPreselect', $user_course_id, 0, 'ecg' ) ?>" class="btn btn-primary">
                        <?= lang('Grades.add_grade') ?>
                    </a>
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= lang('Grades.subject') ?></th>

                            <?php for($i=1; $i <= 8; $i++): ?>
                                <th class="text-center">
                                    <?= substr(lang('Grades.semester'), 0, 3).'. '.$i ?>
                                </th>
                            <?php endfor ?>

                            <th><?= lang('Grades.average') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($ecg['subjects'] as $ecg_subject): ?>
                            <tr>
                                <td><?= $ecg_subject['name'] ?></td>

                                <?php foreach($ecg_subject['grades'] as $ecg_subject_grade): ?>
                                    <td class="text-center">
                                        <a href="<?= url_to('updateGrade', $user_course_id, $ecg_subject_grade['id']) ?>">
                                            <?= $ecg_subject_grade['value'] ?? '' ?>
                                        </a>
                                    </td>
                                <?php endforeach ?>

                                <td><?= $ecg_subject['average'] ?? lang('Grades.unavailable_short') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="9" class="text-right">
                                <?= lang('Grades.weighting')?> : <?= $ecg['weighting'].'%' ?>
                            </td>

                            <td>
                                <strong>
                                    <?= $ecg['average'] ?? lang('Grades.unavailable_short') ?>
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif ?>
    <?php endif ?>
</div>
