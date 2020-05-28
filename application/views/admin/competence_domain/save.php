<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$update = !is_null($competence_domain);
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_symbol = array(
        'name' => 'symbol',
        'value' => $competence_domain_symbol ?? $competence_domain->symbol ?? '',
        'max' => SYMBOL_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'competence_domain_symbol'
    );
    
    $data_name = array(
        'name' => 'name',
        'value' => $competence_domain_name ?? $competence_domain->name ?? '',
        'max' => COMPETENCE_DOMAIN_NAME_MAX_LENGTH,
        'class' => 'form-control', 'id' => 'competence_domain_name'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('competence_domain_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'competence_domain_form',
        'name' => 'competence_domain_form'
    );
    echo form_open('admin/save_competence_domain', $attributes, [
        'id' => $competence_domain->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_competence_domain_course_plan'), 'course_plan', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('course_plan',$course_plans,$competence_domain->fk_course_plan ?? '','id="course_plan" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('field_competence_domain_symbol'), 'competence_domain_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('field_competence_domain_name'), 'competence_domain_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('admin/list_competence_domain'); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
