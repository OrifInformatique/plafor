<?php

/**
 * Let the edition of an acquisition status comment.
 *
 * Called by Apprentice/add_comment($acquisition_status_id, $comment_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?int $comment_id ID of the comment.
 * If set, means an entry is updated.
 *
 * @param ?string $comment_text Text of the comment.
 *
 * @param array acquisiton_status_id ID of the acquisition status where a comment is added.
 *
 * @param ?array $errors comment_model errors.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action Apprentice/add_comment($acquisition_status_id, $comment_id)
 *
 * @param string $comment Text of the comment.
 *
 */

helper('form');

$max_length = config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH;

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <?= form_open(base_url('plafor/apprentice/save_comment/'.$acquisition_status_id.'/'.$comment_id)) ?>
        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.comment'), 'comment',
                    ['class' => 'form-label']) ?>

                <?= form_textarea('comment', $comment_text,
                    ['class' => 'form-control', 'id' => 'comment', 'maxlength' => $max_length, 'required' => 'required']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status_id) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>
