<?php
/**
 * Fichier de vue pour save_objective
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<?php
$update = !is_null($objective);
helper('form');
$validation=\CodeIgniter\Config\Services::validation();

    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_symbol = array(
        'name' => 'symbol',
        'value' => $objective_symbol ?? $objective['symbol'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'objective_symbol'
    );

    $data_taxonomy = array(
        'name' => 'taxonomy',
        'value' => $objective_taxonomy ?? $objective['taxonomy'] ?? '',
        'type' => 'number',
        'max' => config('\Plafor\Config\PlaforConfig')->TAXONOMY_MAX_VALUE,
        'class' => 'form-control',
        'id' => 'objective_taxonomy'
    );

    $data_name = array(
        'name' => 'name',
        'value' => $objective_name ?? $objective['name'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->OBJECTIVE_NAME_MAX_LENGTH,
        'type' => 'text',
        'class' => 'form-control', 'id' => 'objective_name'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_objective_'.($update ? 'update' : 'new')); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'objective_form',
        'name' => 'objective_form'
    );
    echo form_open(base_url('plafor/courseplan/save_objective/'.($objective['id']??'0').'/'.($operational_competence_id??'')), $attributes, [
        'id' => $objective['id'] ?? 0,
        'type' => 'objective',
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?php
        foreach ($errors!=null?$errors:[] as $error){ ?>
        <div class="alert alert-danger">
            <?=$error ?>
        </div>
        <?php } ?>


        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_objective_operational_competence'), 'operational_competence', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('operational_competence',$operational_competences,($operational_competence_id??''),'id="operational_competence" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_objective_symbol'), 'objective_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('plafor_lang.field_objective_taxonomy'), 'objective_taxonomy', ['class' => 'form-label']); ?>
                <?= form_input($data_taxonomy); ?>
                <?= form_label(lang('plafor_lang.field_objective_name'), 'objective_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
            </div>
        </div>

        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_operational_competence/'.($operational_competence_id??'')); ?>"><?= lang('common_lang.btn_cancel'); ?></a>
				<?php if($objective && $objective['archive']) { ?>
				<a href="<?=base_url('plafor/courseplan/delete_objective/'.$objective['id'].'/3')?>" class="btn btn-primary">
					<?=lang('common_lang.btn_reactivate')?>
				</a>
				<?php } ?>
                <?= form_submit('save', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
