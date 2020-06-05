<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$update = !is_null($apprentice_link);
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('apprentice_link_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'apprentice_link_form',
        'name' => 'apprentice_link_form'
    );
    echo form_open('apprentice/link_apprentice', $attributes, [
        'id' => $apprentice->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <?= form_label(lang('field_apprentice_username'), 'apprentice_link', ['class' => 'form-label']); ?>
                <p><?=$apprentice->username?></p>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('field_formator_link'), 'formator', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('formator',$formators,$formators->id ?? '','id="formator" class="form-control"')?>
            </div>   
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('apprentice'); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
