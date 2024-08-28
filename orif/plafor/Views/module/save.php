<?php

/**
 * Module save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= lang('plafor_lang.create_module') ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveTeachingModule'), [], ['id' => $id]) ?>
        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('plafor_lang.number_module'), 'number', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'number'], 'number') ?>
            </div>
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.name_module'), 'module_name', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'module_name']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.weight_domain'), 'domain_weight', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert domains list as form_dropdwon $options -->
                <?= form_dropdown('parent_domain', 'Insert domains here', null, ['class' => 'form-control', 'id' => 'parentdomain']) ?>
            </div>
            <div class="col-sm-2 form-group">
                <?= form_label(lang('plafor_lang.version_module'), 'version', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'version'], 'number') ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/grade/showAllTeachingModule') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>
                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>