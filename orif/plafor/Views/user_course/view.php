<?php

/**
 * Let the edition of the acquisition status of
 * objectives contained in a operational competence.
 *
 * Called by Apprentice/view_user_course($id_user_course)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * // TODO : Directly put the title in the view, then delete this param
 * @param string $title Page title.
 *
 * @param array $user_course Apprentice user course.
 * All fields from table.
 *
 * @param array $apprentice Apprentice.
 * All fields from table.
 *
 * @param array $user_course_status Status of the user course.
 * All fields from table.
 *
 * @param array $course_plan Course plan followed by the apprentice.
 * All fields from table.
 *
 * @param array $trainers_apprentice Contains the trainers linked to the apprentice.
 * All fields from table.
 *
 * @param array $acquisition_status Acquisition statuses of the objecives of the course plan.
 * All fields from table.
 *
 * @param array $acquisition_levels Acquisition levels.
 * All fields from table.
 *
 * @param array $objectives Objectives linked to the operational competence.
 * All fields from table.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action Apprentice/save_acquisition_status($acquisition_status_id)
 *
 * @param int $fiels_acquisition_status Acquistion status, stored as acquisition status ID.
 *
 * === NOTES ===
 *
 * The update is made when changing the acquisition status of an objective
 * in the select input. This is made using JS.
 *
 */

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_user_course')]) ?>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- User course details -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_user_course') ?></p>
        </div>

        <div class="col-md-4 mb-3">
            <p><strong><?= lang('plafor_lang.apprentice') ?></strong></p>

            <a href="<?= base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']) ?>">
                <?= $apprentice['username'] ?>
            </a>
        </div>

        <div class="col-md-8 mb-3">
            <p><strong><?= lang('plafor_lang.course_plan') ?></strong></p>

            <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan['id']) ?>">
                <strong><?= $course_plan['formation_number'] ?></strong>
                <?=$course_plan['official_name']?>
            </a>
        </div>

        <div class="col-md-4">
            <p><strong><?= lang('plafor_lang.field_user_course_date_begin') ?></strong></p>
            <p><?= $user_course['date_begin'] ?></p>
        </div>

        <div class="col-md-4">
            <p><strong><?= lang('plafor_lang.field_user_course_date_end') ?></strong></p>
            <p><?= $user_course['date_end'] ?></p>
        </div>

        <div class="col-md-4">
            <p><strong><?= lang('plafor_lang.field_user_course_status') ?></strong></p>
            <p><?= $user_course_status['name'] ?></p>
        </div>

        <?php if($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_admin): ?>
            <div class="col-md-10 mb-2">
                <a class="btn btn-primary" href="<?= base_url('plafor/apprentice/save_user_course/'.$apprentice['id']."/".$user_course['id'])?>">
                    <?= lang('plafor_lang.title_user_course_update') ?>
                </a>

                <a class="btn btn-danger" href="<?= base_url('plafor/courseplan/delete_user_course/'.$user_course['id'])?>">
                    <?= lang('plafor_lang.title_user_course_delete') ?>
                </a>
            </div>
        <?php endif ?>
    </div>

    <!-- Objectives acquisition status -->
    <?php
    $trainers_id = array();

    foreach($trainers_apprentice as $trainer_apprentice)
        $trainers_id[] = $trainer_apprentice['fk_trainer'];

    if(($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_admin)
    || ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_trainer
        && in_array($_SESSION['user_id'], $trainers_id))
    || ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice
        && $user_course['fk_user'] == $apprentice['id'])):
    ?>
        <div class="row">
            <div class="col">
                <p class="bg-primary text-white"><?= lang('plafor_lang.field_user_course_objectives_status') ?></p>
            </div>

            <div class="col-md-12">
                <table class="table table-hover" id="objectiveListContent">
                    <thead>
                        <tr>
                            <th><?= lang('plafor_lang.field_symbol') ?></th>
                            <th><?= lang('plafor_lang.field_objective_name') ?></th>
                            <th><?= lang('plafor_lang.field_taxonomy') ?></th>
                            <th><?= lang('plafor_lang.field_acquisition_level') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($acquisition_status as $acquisition): ?>
                            <tr>
                                <td>
                                    <?= $objectives[$acquisition['fk_objective']]['symbol'] ?>
                                </td>

                                <td>
                                    <a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id']) ?>">
                                        <?= $objectives[$acquisition['fk_objective']]['name'] ?>
                                    </a>
                                </td>

                                <td>
                                    <?= $objectives[$acquisition['fk_objective']]['taxonomy'] ?>
                                </td>

                                <td style="padding: .75rem 0;">
                                    <select class="form-control acquisitionStatusSelect" data-acquisition-status-id="<?= $acquisition['id'] ?>">
                                        <?php foreach($acquisition_levels as $acquisition_level): ?>
                                            <option
                                            <?= $acquisition_level['id'] == $acquisition['fk_acquisition_level'] ? 'selected' : '' ?>
                                            value="<?= $acquisition_level['id'] ?>">
                                                <?= $acquisition_level['name'] ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

                <!-- TODO : Remove this unused card display feature. It's currently hidden by CSS (MY_styles.css). -->
                <div id="objectiveListContentResponsive">
                    <?php foreach($acquisition_status as $acquisition): ?>
                        <div class="objectiveCardContainer card">
                            <header class="objectiveCardHeader bg-primary text-white">
                                <a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id']) ?>">
                                    <?= $objectives[$acquisition['fk_objective']]['symbol'] ?>
                                </a>
                            </header>

                            <div class="objectiveCardContentContainer">
                                <p class="objectiveCardDescription">
                                    <a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id']) ?>">
                                        <?= $objectives[$acquisition['fk_objective']]['name'] ?>
                                    </a>
                                </p>
                            </div>
                            <footer class="objectiveCardFooter">
                                <p class="objectiveCardTaxonomy bg-secondary text-white"><a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['taxonomy']; ?></a></p>
                                <select class="form-control acquisitionStatusSelect" data-acquisition-status-id="<?=$acquisition['id']?>">
                                    <?php foreach($acquisition_levels as $acquisition_level):?>
                                        <option <?=$acquisition_level['id']==$acquisition['fk_acquisition_level']?'selected':''?> value="<?=$acquisition_level['id']?>"><?=$acquisition_level['name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </footer>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<script defer>
    window.onload = () => {
        fixCardDescriptionLength();
    }

    window.onresize = () => {
        fixCardDescriptionLength();
    }

    document.querySelectorAll('.acquisitionStatusSelect').forEach((element) => {
        element.addEventListener('change', (e) => {
            $.post(`<?= base_url('plafor/apprentice/save_acquisition_status') ?>/${e.target.getAttribute('data-acquisition-status-id')}`, {field_acquisition_level:e.target.value}).done((response) => {})
        })
    })

    function fixCardDescriptionLength(){
        let maxHeight = 0;
        document.querySelectorAll('.objectiveCardDescription').forEach((node) => {
            if(node.clientHeight > maxHeight)
                maxHeight = node.clientHeight;
        })

        document.querySelectorAll('.objectiveCardDescription').forEach((node) => {
            node.style.minHeight = `${maxHeight}px`;
        })
    }
</script>