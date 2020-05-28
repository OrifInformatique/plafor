<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$update = !is_null($operational_competence);
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_symbol = array(
        'name' => 'symbol',
        'value' => $operational_competence_symbol ?? $operational_competence->symbol ?? '',
        'max' => SYMBOL_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_symbol'
    );
    
    $data_name = array(
        'name' => 'name',
        'value' => $operational_competence_name ?? $operational_competence->name ?? '',
        'max' => OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_name'
    );
    
    $data_methodologic = array(
        'name' => 'methodologic',
        'value' => $operational_competence_methodologic ?? $operational_competence->methodologic ?? '',
        'max' => SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_methodologic'
    );
    
    $data_social = array(
        'name' => 'social',
        'value' => $operational_competence_social ?? $operational_competence->social ?? '',
        'max' => SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_social'
    );
    
    $data_personal = array(
        'name' => 'personal',
        'value' => $operational_competence_personal ?? $operational_competence->personal ?? '',
        'max' => SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_personal'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('operational_competence_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'operational_competence_form',
        'name' => 'operational_competence_form'
    );
    echo form_open('admin/save_operational_competence', $attributes, [
        'id' => $operational_competence->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_operational_competence_competence_domain'), 'competence_domain', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('competence_domain',$competence_domains,$operational_competence->fk_competence_domain ?? '','id="competence_domain" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_operational_competence_symbol'), 'operational_competence_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('field_operational_competence_name'), 'operational_competence_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
                <?= form_label(lang('field_operational_competence_methodologic'), 'operational_competence_methodologic', ['class' => 'form-label']); ?>
                <?= form_textarea($data_methodologic); ?>
                <?= form_label(lang('field_operational_competence_social'), 'operational_competence_social', ['class' => 'form-label']); ?>
                <?= form_textarea($data_social); ?>
                <?= form_label(lang('field_operational_competence_personal'), 'operational_competence_personal', ['class' => 'form-label']); ?>
                <?= form_textarea($data_personal); ?>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('admin/list_operational_competence'); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
