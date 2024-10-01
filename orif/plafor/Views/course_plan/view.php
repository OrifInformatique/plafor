<?php

/**
 * Shows a course plan and its linked competence domains
 * and teaching domains.
 *
 * Called by CoursePlan/view_course_plan($course_plan_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $course_plan Course plan we want to see its details.
 * All fields from table.
 *
 * @param array $competence_domains Competence domains linked to the course plan.
 * All fields from table.
 *
 * @param array $teaching_domains Teaching domains linked to the course plan.
 * See \Views/domain/view for data structure needed.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action CoursePlan/view_course_plan($course_plan_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form');

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_course_plan')]) ?>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => lang('plafor_lang.title_view_course_plan')]) ?>

    <!-- Course plan details -->
    <?= view('\Plafor/course_plan/details', $course_plan) ?>

    <!-- Linked competence domains -->
    <div class="row">
        <div class="col-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_competence_domains_linked') ?></p>
        </div>

        <?= view('Common\Views\items_list',
        [
            'items'   => $competence_domains,
            'columns' =>
            [
                'symbol'  => lang('plafor_lang.symbol'),
                'name' => lang('plafor_lang.competence_domain')
            ],
            'with_deleted'  => true,
            'url_detail'    => 'plafor/courseplan/view_competence_domain/',
            'url_create'    => 'plafor/courseplan/save_competence_domain/'.$course_plan['id'],
            'url_update'    => 'plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/',
            'url_delete'    => 'plafor/courseplan/delete_competence_domain/1/',
            'url_getView'   => 'plafor/courseplan/view_course_plan/'.$course_plan['id'],
            'url_restore'   => 'plafor/courseplan/delete_competence_domain/3/',
        ]);
        ?>
    </div>

    <!-- Linked teaching domains -->
    <?= view('\Plafor/domain/view', ['teaching_domains' => $teaching_domains, 'parent_course_plan_id' => $course_plan['id']]) ?>
</div>