<?php
$update = !is_null($comment_id);

    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_comment = array(
        'name' => 'comment',
        'max' => config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'comment',
        'value' => ($commentValue??'')
    );
    helper('form');
    $validation=\CodeIgniter\Config\Services::validation()
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('title_comment_'.($update ? 'update' : 'new')); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'comment_form',
        'name' => 'comment_form'
    );
    echo form_open(base_url('plafor/apprentice/add_comment/'.$acquisition_status['id'].'/'.($comment_id??'')));
    ?>

        <!-- ERROR MESSAGES -->
        <?php
        foreach ($errors!=null?$errors:[] as $error) { ?>
    <div class="alert alert-danger">
            <?= $error; ?>
    </div>
        <?php } ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('user_lang.field_comment'), 'comment', ['class' => 'form-label']); ?>
                <?= form_textarea($data_comment); ?>
            </div>
        </div>

        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status['id']); ?>"><?= lang('common_lang.btn_cancel'); ?></a>
                <?= form_submit('save', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
