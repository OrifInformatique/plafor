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

    <div class="row">
        <div class="col">
            <h2><?= $title ?></h2>
        </div>
    </div>

    <div class="row" style="justify-content:space-between">
        <div class="col-sm-3">
            <a href="<?= base_url('plafor/apprentice/save_user_course/'.$id_apprentice) ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_new_f') ?>
            </a>
        </div>
    </div>

    <div class="row mt-2">
        <?php if(!empty($user_courses))
        {
            foreach($user_courses as $user_course)
            {
                $data[] =
                [
                    'id'                 => $user_course['id'],
                    'course_plan_number' => $user_course['course_plan']['formation_number'],
                    'course_plan_name'   => $user_course['course_plan']['official_name'],
                    'date_begin'         => Time::createFromFormat('Y-m-d', $user_course['date_begin'])->toLocalizedString('dd.MM.Y'),
                    'date_end'           => isset($user_course['date_end']) ? Time::createFromFormat('Y-m-d', $user_course['date_end'])->toLocalizedString('dd.MM.Y'): '',
                    'course_plan_status' => $user_course['status'],
                ];
            }

            echo view('Common\Views\items_list',
            [
                'columns' =>
                [
                    'course_plan_number'    => lang('plafor_lang.field_course_plan_formation_number'),
                    'course_plan_name'      => lang('plafor_lang.field_course_plan_official_name'),
                    'date_begin'            => lang('plafor_lang.field_user_course_date_begin_short'),
                    'date_end'              => lang('plafor_lang.field_user_course_date_end_short'),
                    'course_plan_status'    => lang('plafor_lang.status'),
                ],
                'items'             => $data,
                'primary_key_field' => 'id',
                'url_update'        => 'plafor/apprentice/save_user_course/'.$id_apprentice.'/',
                'url_delete'        => 'plafor/courseplan/delete_user_course/'
            ]);
        }
        ?>
    </div>
</div>