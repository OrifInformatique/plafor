<?php

/**
 * Module save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?int $module_id ID of the module.
 *
 * @param ?int $module_number Number of the module.
 *
 * @param ?string $module_name Name of the module.
 *
 * @param ?int $module_parent_domain Module's parent domain. Stored as domain ID.
 *
 * @param ?int $module_version Version of the module.
 *
 * === NOTES ===
 *
 * No param is required.
 *
 * Params are provided when editing an existing module,
 * or conserving user inputs after an incorrect form completion.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @param ?int $module_id ID of the module.
 *
 * @param int $module_number Number of the module.
 *
 * @param string $module_name Name of the module.
 *
 * @param int $module_parent_domain Module's parent domain. Stored as domain ID.
 *
 * @param int $module_version Version of the module.
 *
 * === NOTES ===
 *
 * $module_id is optional.
 *
 * If provided, an existing entry will be updated.
 * If empty or '0', a new entry will be created.
 *
 */

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= lang('Grades.create_module') ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveTeachingModule'), [], ['module_id' => $module_id ?? 0]) ?>
        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.module_number'), 'module_number', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $module_number ?? '', ['class' => 'form-control', 'id' => 'module_number'], 'number') ?>
            </div>
            <div class="col form-group">
                <?= form_label(lang('Grades.module_name'), 'module_name', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $module_name ?? '', ['class' => 'form-control', 'id' => 'module_name']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.module_parent_domain'), 'module_parent_domain', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert domains list as form_dropdown $options -->
                <?= form_dropdown('module_parent_domain', 'Insert domains here', $module_parent_domain ?? null, ['class' => 'form-control', 'id' => 'module_parent_domain']) ?>
            </div>
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.module_version'), 'version', ['class' => 'form-label']) ?><br>
                <?= form_input(null, $module_version ?? '', ['class' => 'form-control', 'id' => 'version'], 'number') ?>
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