<?php

/**
 * Module save view
 *
 * Called by TeachingDomainController/saveTeachingModule()
 *
 * @author      Orif (Dedy)
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
 * @param array $domains List of all domains.
 * Array of key-values where keys are domains IDs and values are domains names.
 *
 * @param ?int $module_parent_domain Module's parent domain. Stored as domain ID.
 * To edit an existing entry or remember user input.
 *
 * @param ?int $module_version Version of the module.
 * To edit an existing entry or remember user input.
 *
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
 * @param int $module_parent_domain Parent domain of the module, stored as domain ID.
 *
 * @param int $module_version Version of the module.
 *
 */



/**
 * Data management
 *
 */

// TODO : Move data management in controller

if(isset($module_id) && $module_id > 0)
    $title = lang('Grades.update_module');

else
{
    $title = lang('Grades.create_module');
    $module_id = 0;
}

$domains = [];

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= $title ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveTeachingModule/'.$module_id)) ?>
        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.module_number'), 'module_number', ['class' => 'form-label']) ?><br>
                <?= form_input('module_number', $module_number ?? '', ['class' => 'form-control', 'id' => 'module_number', 'min' => 1], 'number') ?>
            </div>
            <div class="col form-group">
                <?= form_label(lang('Grades.module_name'), 'module_name', ['class' => 'form-label']) ?><br>
                <?= form_input('module_name', $module_name ?? '', ['class' => 'form-control', 'id' => 'module_name']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('Grades.module_parent_domain'), 'module_parent_domain', ['class' => 'form-label']) ?><br>
                <?= form_dropdown('module_parent_domain', $domains, $module_parent_domain ?? null, ['class' => 'form-control', 'id' => 'module_parent_domain']) ?>
            </div>
            <div class="col-sm-2 form-group">
                <?= form_label(lang('Grades.module_version'), 'version', ['class' => 'form-label']) ?><br>
                <?= form_input('version', $module_version ?? '', ['class' => 'form-control', 'id' => 'version', 'min' => 1], 'number') ?>
            </div>
        </div>

        <div class="row">
            <?php if($module_id > 0): ?>
                <div class="col">
                    <a href="<?= base_url('plafor/teachingdomain/deleteTeachingModule/'.$module_id) ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/teachingdomain/getAllTeachingModule') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>
                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>