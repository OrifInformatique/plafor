<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('title_edit_acquisition_status'); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'edit_acquisition_status',
        'name' => 'edit_acquisition_status'
    );
    echo form_open('apprentice/save_acquisition_status/'.$id, $attributes);
	?>

		<!-- ERROR MESSAGES -->
		<?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

		<!-- FIELDS -->
		<div class="row form-group">
			<div class="col-6">
				<?= lang('field_acquisition_level', 'field_acquisition_level', [
					'class' => 'form-label'
				]); ?>
			</div>
			<div class="col-6">
				<?= form_dropdown('field_acquisition_level', $acquisition_levels, $acquisition_level, [
					'id' => 'field_acquisition_level', 'class' => 'form-control'
				]); ?>
			</div>
		</div>

        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('apprentice/view_acquisition_status/'.$id); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
	<?= form_close(); ?>
</div>
