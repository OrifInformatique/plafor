<?php
helper('form');
/**
 * Users List View
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_operational_competence_list'); ?></h1>
        </div>
    </div>
    <div class="row" style="justify-content:space-between;">
        <div class="col-sm-3 text-left">
            <a href="<?= base_url('plafor/admin/save_operational_competence/0/'.($id_competence_domain??'')) ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_new_f'); ?>
            </a>
        </div>
        <div style="align-self:flex-end;">
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
                <th><?= lang('plafor_lang.field_operational_competence_name'); ?></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="operational_competenceslist">
            <?php foreach($operational_competences as $operational_competence) { ?>
                <tr>
                    <td><a href="<?= base_url('plafor/admin/list_objective/'.$operational_competence['id']); ?>"><span class="font-weight-bold"><?= $operational_competence['symbol']?></span> <?= $operational_competence['name']; ?></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id'])?>"><?= lang('common_lang.btn_details')?></a></td>
                    <td><a href="<?= base_url('plafor/admin/save_operational_competence/'.$operational_competence['id'].'/'.($id_competence_domain??'')); ?>"><?= lang('common_lang.btn_edit')?></a></td>
                    <td><a href="<?= base_url('plafor/admin/delete_operational_competence/'.$operational_competence['id']); ?>" class="<?=$operational_competence['archive']==null?'bi bi-trash':'bi bi-reply-all-fill'?>"></td>
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
		$.post('<?php
        echo base_url("plafor/admin/list_operational_competence").'/'.($id_competence_domain??'0').'/';?>'+(+checked), {}, data => {
            $('#operational_competenceslist').empty();
            $('#operational_competenceslist')[0].innerHTML = $(data).find('#operational_competenceslist')[0].innerHTML;
        });
    });
});
</script>
