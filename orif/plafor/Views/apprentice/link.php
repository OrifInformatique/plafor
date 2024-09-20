<?php

/**
 * Let the edition of a link between an apprentice and a trainer.
 *
 * Called by Apprentice/save_apprentice_link($id_apprentice, $id_link)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * // TODO : Only give apprentice ID and username : only values needed
 * @param array $apprentice Apprentice.
 * All fields from table.
 *
 * @param array $trainers List of all trainers unlinked to the apprentice.
 * Array of key-values where keys are trainers IDs and values are trainers usernames.
 * For select options.
 *
 * @param ?array $link Link between an apprentice and a trainer.
 * All fields from table.
 * If set, means an entry is updated.
 *
 * @param ?array $errors trainer_apprentice_model errors.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action Apprentice/save_apprentice_link($id_apprentice, $id_link)
 *
 * @param int $apprentice Apprentice, stored as apprentice ID.
 *
 * @param int $trainer Trainer selected, stored as trainer ID.
 *
 */

helper('form');

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title',
        ['title' => lang('plafor_lang.title_apprentice_link_'.(isset($link) ? 'update' : 'new'))]) ?>

    <?= form_open( 'plafor/apprentice/save_apprentice_link/' .$apprentice[ 'id' ].(!empty($link[ 'id' ]) ?  '/' .$link[ 'id' ] : ''),
        [], ['apprentice' => $apprentice['id']]) ?>

        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_apprentice_username'), '',
                    ['class' => 'form-label']) ?>

                <?= form_input(null, $apprentice['username'],
                    ['class' => 'form-control', 'disabled' => true]) ?>
            </div>

            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_trainer_link'), 'trainer',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('trainer', $trainers, $link['fk_trainer'] ?? '',
                    ['class' => 'form-control', 'id' => 'trainer']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>