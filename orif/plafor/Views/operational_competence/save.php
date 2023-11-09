<?php
/**
 * Fichier de vue pour save_operational_competence
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<?php
$update = !is_null($operational_competence);
helper('form');
$validation=\CodeIgniter\Config\Services::validation();
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_symbol = array(
        'name' => 'symbol',
        'value' => $operational_competence_symbol ?? $operational_competence['symbol'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_symbol'
    );
    
    $data_name = array(
        'name' => 'name',
        'value' => $operational_competence_name ?? $operational_competence['name'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_name'
    );
    
    $data_methodologic = array(
        'name' => 'methodologic',
        'value' => $operational_competence_methodologic ?? $operational_competence['methodologic'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_methodologic'
    );
    
    $data_social = array(
        'name' => 'social',
        'value' => $operational_competence_social ?? $operational_competence['social'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_social'
    );
    
    $data_personal = array(
        'name' => 'personal',
        'value' => $operational_competence_personal ?? $operational_competence['personal'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'operational_competence_personal'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_operational_competence_'.($update ? 'update' : 'new')); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'operational_competence_form',
        'name' => 'operational_competence_form'
    );
    echo form_open(base_url('plafor/courseplan/save_operational_competence/'.($operational_competence['id'] ?? '0').'/'.($competence_domain_id>0?$competence_domain_id:'')), $attributes, [
        'id' => $operational_competence['id'] ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?php
        foreach ($errors!=null?$errors:[] as $error){ ?>
        <div class="alert alert-danger">
        <?= $error ?>
        </div>
        <?php } ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_domain'), 'competence_domain', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('competence_domain',$competence_domains,$operational_competence['fk_competence_domain']?? '','id="competence_domain" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_symbol'), 'operational_competence_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_name'), 'operational_competence_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_methodologic'), 'operational_competence_methodologic', ['class' => 'form-label']); ?>
                <?= form_textarea($data_methodologic); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_social'), 'operational_competence_social', ['class' => 'form-label']); ?>
                <?= form_textarea($data_social); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_personal'), 'operational_competence_personal', ['class' => 'form-label']); ?>
                <?= form_textarea($data_personal); ?>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('plafor/courseplan/view_competence_domain/'.$operational_competence['fk_competence_domain']) ?>"><?= lang('common_lang.btn_cancel'); ?></a>
                <?= form_submit('save', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
