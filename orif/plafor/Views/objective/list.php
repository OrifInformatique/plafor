<?php
/**
 * Users List View
 *
 * @author      Orif, section informatique (UlSi, ViDi,HeMa)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
helper('form');
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_objective_list'); ?></h1>
        </div>
    </div>
    <div class="row">
    	<?php if($_SESSION['user_access'] == config('User\Config\UserConfig')->access_lvl_admin): ?>
        <div class="col-sm-3 text-left">
            <a href="<?= base_url('plafor/admin/save_objective/0/'.($operational_competence_id??'')); ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_add'); ?>
            </a>
        </div>
    	<?php endif; ?>
		<div class="col-sm-3 offset-6">
			<?=form_checkbox('toggle_deleted', '', $with_archived, [
				'id' => 'toggle_deleted', 'class' => 'form-check-input'
			]);?>
			<?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label']);?>
		</div>
    </div>
    <div class="row mt-2">
        <table class="table table-hover">
        <thead>
            <tr>
                <th><?= lang('plafor_lang.field_objective_name'); ?></th>
                <th></th>
                <?php if($_SESSION['user_access'] == config('User\Config\UserConfig')->access_lvl_admin): ?>
                <th></th>
                <th></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody id="objectiveslist">
            <?php foreach($objectives as $objective) { ?>
                <tr>
                    <td><a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id']); ?>"><span class="font-weight-bold"><?= $objective['symbol']?></span> <?= $objective['name']; ?></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?= lang('common_lang.btn_details')?></a></td>
                    <?php if($_SESSION['user_access'] == config('User\Config\UserConfig')->access_lvl_admin): ?>
                    <td><a href="<?= base_url('plafor/admin/save_objective/'.$objective['id']); ?>"><?= lang('common_lang.btn_edit')?></a></td>
                    <td><a href="<?= base_url('plafor/admin/delete_objective/'.$objective['id']); ?>" class="<?=$objective['archive']==null?'bi bi-trash':'bi bi-reply-all-fill'?>"></td>
                    <?php endif; ?>
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#toggle_deleted').change(e => {
        let checked = e.currentTarget.checked;
		$.post('<?php echo base_url("plafor/admin/list_objective").'/'.($operational_competence_id??'0').'/';?>'+(+checked), {}, data => {
            $('#objectiveslist').empty();
            $('#objectiveslist')[0].innerHTML = $(data).find('#objectiveslist')[0].innerHTML;
        });
    });
});
</script>
