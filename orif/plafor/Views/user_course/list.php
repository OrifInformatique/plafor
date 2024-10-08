<?php

/**
 * Lists all course plan followed by an apprentice.
 *
 * Called by Apprentice/list_user_courses($id_apprentice)
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Page title.
 *
 * @param array $user_courses Course plans followed by the the apprentice.
 * All fields from table, plus content below.
 * [
 *      ...
 *      'course_plan' => array,  Course plan data linked to the user course.
 *      All fields from table.
 *      'status'      => string, Status of the user course.
 * ]
 *
 * @param int $id_apprentice ID of the apprentice.
 *
 */



/**
 * No data is sent by this view.
 *
 */

use CodeIgniter\I18n\Time;

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['reset' => true]) ?>

    <div class="row mb-2">
        <div class="col">
            <a href="<?= base_url('plafor/apprentice/view_apprentice/'.$id_apprentice) ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_back') ?>
            </a>
        </div>
    </div>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <div class="row mt-2">
        <?= view('Common\Views\items_list',
        [
            'items'   => $user_courses,
            'columns' =>
            [
                'course_plan_number'    => lang('plafor_lang.field_course_plan_formation_number'),
                'course_plan_name'      => lang('plafor_lang.field_course_plan_official_name'),
                'date_begin'            => lang('plafor_lang.field_user_course_date_begin_short'),
                'date_end'              => lang('plafor_lang.field_user_course_date_end_short'),
                'course_plan_status'    => lang('plafor_lang.status'),
            ],
            'allow_hard_delete' => true,
            'url_create'        => 'plafor/apprentice/save_user_course/'.$id_apprentice,
            'url_update'        => 'plafor/apprentice/save_user_course/'.$id_apprentice.'/',
            'url_hard_delete'   => 'plafor/apprentice/delete_user_course/2/'
        ]);
        ?>
    </div>
</div>