<?php

/**
 * Subject save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= lang('plafor_lang.create_subject') ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveTeachingSubject'), [], ['id' => $id]) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.name_subject'), 'subject', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'subject']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.weight_domain'), 'domain_weight', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'domain_weight'], 'number') ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('plafor_lang.parent_domain_subject'), 'parent_domain', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert domains list as form_dropdown $options -->
                <?= form_dropdown('parent_domain', 'Insert domains here', null, ['class' => 'form-control', 'id' => 'parent_domain']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/grade/showAllTeachingSubject') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>