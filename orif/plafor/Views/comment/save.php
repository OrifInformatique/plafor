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
 * @param ?string $commentValue Text of the comment.
 *
 * // TODO : Only give the view acquisition status ID : only value needed
 * @param array acquisiton_status Acquisition status where a comment is added.
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
    <div class="row">
        <div class="col">
            <h2><?= lang('plafor_lang.title_comment_'.(!empty($comment_id) ? 'update' : 'new')) ?></h2>
        </div>
    </div>

    <?= form_open(base_url('plafor/apprentice/add_comment/'.$acquisition_status['id'].'/'.$comment_id)) ?>
        <?php foreach ($errors != null ? $errors : [] as $error): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endforeach ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_comment'), 'comment',
                    ['class' => 'form-label']) ?>

                <?= form_textarea('comment', $commentValue,
                    ['class' => 'form-control', 'id' => 'comment', 'maxlength' => $max_length]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status['id']) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>