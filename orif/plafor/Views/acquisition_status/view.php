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
 * @param array $acquisition_status Acquisition status.
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
            <p class="bg-primary text-white"><?= lang('plafor_lang.field_linked_comments') ?></p>
		</div>

		<?php if($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer): ?>
		    <div class="col mb-2">
		        <a href="<?= base_url('plafor/apprentice/save_comment/'.$acquisition_status['id']); ?>" class="btn btn-primary">
                    <?= lang('plafor_lang.title_comment_new'); ?>
                </a>
		    </div>
		<?php endif ?>

        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?= lang('plafor_lang.field_comment'); ?></th>
                        <th><?= lang('plafor_lang.field_comment_creater'); ?></th>
                        <th><?= lang('plafor_lang.field_comment_date_creation'); ?></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    // TODO : Sort the trainers in the controller (don't forget to update PHPDoc needed values for view)
                    $trainersSorted = [];
                    foreach ($trainers as $trainer)
                        $trainersSorted[$trainer['id']] = $trainer;

                    foreach ($comments as $comment):
                    ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('plafor/apprentice/save_comment/'.$acquisition_status['id'].'/'.$comment['id'])?>">
                                    <?= $comment['comment']; ?>
                                </a>
                            </td>

                            <td><?= isset($trainersSorted[$comment['fk_trainer']]) ? $trainers[$comment['fk_trainer']]['username'] : '' ?></td>
                            <td><?= $comment['date_creation'] ?></td>

                            <td>
                                <a class="bi bi-trash" id="<?=$comment['id']?>" onClick="
                            let obj = {yes: '<?= lang('common_lang.yes')?>',no: '<?= lang('common_lang.no') ?>',message: '<?= lang('plafor_lang.comment_delete') ?>',url: '<?= base_url('plafor/apprentice/delete_comment/'.$comment['id']) ?>'};
                            displayNotif(event.pageX, event.pageY,obj)">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?=base_url('notif.js')?>"></script>