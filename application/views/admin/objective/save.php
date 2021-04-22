<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$update = !is_null($objective);

    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_symbol = array(
        'name' => 'symbol',
        'value' => $objective_symbol ?? $objective->symbol ?? '',
        'max' => SYMBOL_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'objective_symbol'
    );

    $data_taxonomy = array(
        'name' => 'taxonomy',
        'value' => $objective_taxonomy ?? $objective->taxonomy ?? '',
        'type' => 'number',
        'max' => TAXONOMY_MAX_VALUE,
        'class' => 'form-control',
        'id' => 'objective_taxonomy'
    );

    $data_name = array(
        'name' => 'name',
        'value' => $objective_name ?? $objective->name ?? '',
        'max' => OBJECTIVE_NAME_MAX_LENGTH,
        'type' => 'text',
        'class' => 'form-control', 'id' => 'objective_name'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('objective_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'objective_form',
        'name' => 'objective_form'
    );
    echo form_open('admin/save_objective', $attributes, [
        'id' => $objective->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_objective_operational_competence'), 'operational_competence', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('operational_competence',$operational_competences,$objective->fk_operational_competence ?? '','id="operational_competence" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_objective_symbol'), 'objective_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('field_objective_taxonomy'), 'objective_taxonomy', ['class' => 'form-label']); ?>
                <?= form_input($data_taxonomy); ?>
                <?= form_label(lang('field_objective_name'), 'objective_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
            </div>
        </div>

        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('admin/list_objective'); ?>"><?= lang('btn_cancel'); ?></a>
				<?php if($objective && $objective->archive) { ?>
				<a href="<?=base_url('admin/delete_objective/'.$objective->id.'/3')?>" class="btn btn-primary">
					<?=lang('btn_reactivate')?>
				</a>
				<?php } ?>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
