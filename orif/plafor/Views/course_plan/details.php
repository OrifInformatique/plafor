<?php

/**
 * Shows the details of a course plan.
 *
 * Called by \Views/course_plan/view
 *           \Views/competence_domain/view
 *           \Views/operational_comptence/view
 *           \Views/objective/view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $course_plan Course plan.
 * All fields from table.
 *
 */



/**
 * No data is sent by this view.
 *
 */

?>

<div class="row">
    <div class="col-12">
        <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_course_plan') ?></p>
    </div>

    <div class="col-12">
        <p><strong><?= $course_plan['official_name'] ?></strong></p>
    </div>

    <div class="col-12">
        <p>
            <?= lang('plafor_lang.number_abr').' '.$course_plan['formation_number'].', '
            .strtolower(lang('plafor_lang.field_course_plan_into_effect')).' '.$course_plan['date_begin'] ?>
        </p>
    </div>
</div>