<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$update = !is_null($user_course);
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_date_begin = array(
        'name' => 'date_begin',
        'value' => $user_course_date_begin ?? $user_course->date_begin ?? date("Y-m-d"),
        'class' => 'form-control',
        'type' => 'date',
        'id' => 'user_course_date_begin'
    );

    $data_date_end = array(
        'name' => 'date_end',
        'value' => $user_course_date_end ?? $user_course->date_end ?? '',
        'class' => 'form-control', 'id' => 'competence_domain_name',
        'type' => 'date',
        'id' => 'user_course_date_begin'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('user_course_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'user_course_form',
        'name' => 'user_course_form'
    );
    echo form_open('admin/save_user_course/'.$apprentice->id, $attributes, [
        'id' => $apprentice->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <?= form_label(lang('field_user_course_course_plan'), 'course_plan', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('course_plan',$course_plans,'','id="course_plan" class="form-control"')?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('field_user_course_status'), 'status', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('status',$status,$user_course->fk_status ?? '','id="status" class="form-control"')?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('field_user_course_date_begin'), 'user_course_date_begin', ['class' => 'form-label']); ?>
                <?= form_input($data_date_begin); ?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('field_user_course_date_end'), 'user_course_date_end', ['class' => 'form-label']); ?>
                <?= form_input($data_date_end); ?>
            </div>
        </div>

        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('apprentice/view_apprentice/'.$apprentice->id); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
