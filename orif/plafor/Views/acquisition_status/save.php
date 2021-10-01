<?php
helper('form');
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_acquisition_status_edit'); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'edit_acquisition_status',
        'name' => 'edit_acquisition_status'
    );
    echo form_open('plafor/apprentice/save_acquisition_status/'.$id, $attributes);
	?>

		<!-- ERROR MESSAGES -->
		<?php
        foreach ($errors!=null?$errors:[] as $error) { ?>
            <div class="alert alert-danger">
            <?= $error; ?>
            </div>
        <?php } ?>

		<!-- FIELDS -->
		<div class="row form-group">
			<div class="col-6">
				<?= lang('plafor_lang.field_acquisition_level', ['class'=>'form-label']); ?>
			</div>
			<div class="col-6">
				<?= form_dropdown('field_acquisition_level',$acquisition_levels, $acquisition_level,['id' => 'field_acquisition_level', 'class' => 'form-control'] ); ?>
			</div>
		</div>

        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$id); ?>"><?= lang('common_lang.btn_cancel'); ?></a>
                <?= form_submit('save', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
	<?= form_close(); ?>
</div>
