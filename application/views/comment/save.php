<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$update = !is_null($acquisition_status);
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_comment = array(
        'name' => 'comment',
        'max' => SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'comment'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('comment_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'comment_form',
        'name' => 'comment_form'
    );
    echo form_open('apprentice/add_comment/'.$acquisition_status->id);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_comment'), 'comment', ['class' => 'form-label']); ?>
                <?= form_textarea($data_comment); ?>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('apprentice/view_acquisition_status/'.$acquisition_status->id); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
