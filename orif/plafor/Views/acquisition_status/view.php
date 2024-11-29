<?php

/**
 * Shows the details of an acquisition status.
 *
 * Called by Apprentice/view_acquisition_status($acquisition_status_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $acquisition_status_id ID of the acquisition status.
 * All fields from table.
 *
 * @param array $acquisition_level Acquisiton level of the objective.
 * All fields from table.
 *
 * @param array $objective Objective.
 * All fields from table.
 *
 * @param array $comments List of comments posted on the objective/acquisition status.
 * All fields from table.
 *
 * @param array $trainers List of all trainers.
 * All datbase fields needed.
 * To display the comment author.
 *
 */



/**
 * No data is sent by this view.
 *
 */

helper("AccessPermissions_helper")

?>


<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_acquisition_status')]) ?>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => lang('plafor_lang.title_view_acquisition_status')]) ?>

    <!-- Objective details -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.details_acquisition_status') ?></p>
        </div>

        <div class="col-md-12">
            <p><strong><?= lang('plafor_lang.objective').' '.$objective['symbol'] ?></strong> : <?= $objective['name'] ?></p>
        </div>

        <div class="col-md-4">
            <p><strong><?= lang('plafor_lang.field_objective_taxonomy') ?></strong> : <?= $objective['taxonomy'] ?></p>
        </div>

        <div class="col-md-6">
            <p><strong><?= lang('plafor_lang.field_acquisition_level') ?></strong> : <?= $acquisition_level['name'] ?></p>
        </div>
    </div>

    <!-- Comments -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.linked_comments') ?></p>
		</div>

		<?php if(hasCurrentUserApprenticeAccess()): ?>
		    <div class="col-12 mb-2">
		        <a href="<?= base_url('plafor/apprentice/save_comment/'.$acquisition_status_id) ?>" class="btn btn-primary">
                    <?= lang('plafor_lang.title_comment_new'); ?>
                </a>
		    </div>
		<?php endif ?>

        <div class="col-12">
            <?php foreach($comments as $comment): ?>
                <div class="col-12 border border-secondary rounded mx-auto my-2 pt-2">
                    <div class="col-12">
                        <em class="mb-0">
                            <?= $comment["fk_user"].', '.lang('plafor_lang.the_m').' '.$comment["date_creation"] ?>
                        </em>
                    </div>

                    <div class="col-12 py-2">
                        <p class="mb-0"><?= $comment["comment"] ?></p>
                    </div>

                    <?php if(hasCurrentUserApprenticeAccess()): ?>
                        <div class="col-12 py-2 border-top border-primary text-right">
                            <a href="<?= base_url('plafor/apprentice/save_comment/'.$acquisition_status_id.'/'.$comment["id"]) ?>" class="btn btn-primary">
                                <?= lang('common_lang.btn_edit'); ?>
                            </a>
                            <a href="<?= base_url('plafor/apprentice/delete_comment/2/'.$comment["id"]) ?>" class="btn btn-danger">
                                <?= lang('common_lang.btn_hard_delete'); ?>
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>