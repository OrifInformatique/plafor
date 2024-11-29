<?php

/**
 * Let the edition of a teaching module.
 *
 * Called by TeachingDomainController/saveTeachingModule($module_id)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Page title.
 * Should be lang('Grades.update_module') or lang('Grades.create_module');
 *
 * @param ?int $module_id ID of the module.
 *
 * @param ?int $module_number Number of the module.
 * To edit an existing entry or remember user input.
 *
 * @param ?string $module_name Name of the module.
 * To edit an existing entry or remember user input.
 *
 * @param ?int $module_version Version of the module.
 * To edit an existing entry or remember user input.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * @method POST
 *
 * @action TeachingDomainController/saveTeachingModule($module_id)
 *
 * @param int $module_id ID of the module.
 *
 * @param int $module_number Number of the module.
 *
 * @param string $module_name Name of the module.
 *
 * @param int $module_version Version of the module.
 *
 */

helper('form');

$module_name_max_length = config('\Plafor\Config\PlaforConfig')->MODULE_OFFICIAL_NAME_MAX_LENGTH;
$module_number_min      = '1'.str_repeat(0, config('\Plafor\Config\PlaforConfig')->MODULE_NUMBER_MIN_LENGTH - 1);
$module_number_max      = str_repeat(9, config('\Plafor\Config\PlaforConfig')->MODULE_NUMBER_MAX_LENGTH);

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Form errors -->
    <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingModule/'.$module_id)) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.module_name'), 'module_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('module_name', $module_name ?? '',
                    ['class' => 'form-control', 'id' => 'module_name', 'maxlength' => $module_name_max_length, 'required' => 'required']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.module_number'), 'module_number',
                    ['class' => 'form-label']) ?>

                <?= form_input('module_number', $module_number ?? '',
                    ['class' => 'form-control', 'id' => 'module_number',
                    'min' => $module_number_min, 'max' => $module_number_max,
                    'required' => 'required'], 'number') ?>
            </div>

            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.module_version'), 'module_version',
                    ['class' => 'form-label']) ?>

                <?= form_input('module_version', $module_version ?? '',
                    ['class' => 'form-control', 'id' => 'module_version', 'min' => 1, 'required' => 'required'], 'number') ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/teachingdomain/getAllTeachingModule') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>
