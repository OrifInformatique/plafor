<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.details_acquisition_status')?></p>
        </div><div class="col-md-2">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_objective_symbol')?></p>
            <a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?=$objective['symbol']?></a>
        </div><div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_objective_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?=$objective['name']?></a>
        </div><div class="col-md-2">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_objective_taxonomy')?></p>
            <a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?=$objective['taxonomy']?></a>
        </div>
        <div class="col-md-2">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_acquisition_level')?></p>
            <a href="<?= base_url('plafor/apprentice/save_acquisition_status/'.$acquisition_status['id'])?>"><?=$acquisition_level['name']?></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.field_linked_comments')?></p>
		</div>
		<?php if($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer) { ?>
		<a href="<?= base_url('plafor/apprentice/add_comment/'.$acquisition_status['id']); ?>" class="btn btn-primary"><?= lang('plafor_lang.title_comment_new'); ?></a>
		<?php } ?>
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?= lang('plafor_lang.field_comment'); ?></th>
                        <th><?= lang('plafor_lang.field_comment_creater'); ?></th>
                        <th><?= lang('plafor_lang.field_comment_date_creation'); ?></th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$trainersSorted = [];
				foreach ($trainers as $trainer) {
					$trainersSorted[$trainer['id']] = $trainer;
				}
				foreach ($comments as $comment):
				?>
                    <tr>
                        <td><a href="<?= base_url('plafor/apprentice/add_comment/'.$acquisition_status['id'].'/'.$comment['id'])?>"><?= $comment['comment']; ?></a></td>
						<?php
						if (isset($trainersSorted[$comment['fk_trainer']])): ?>
                        <th><?= $trainers[$comment['fk_trainer']]['username']; ?></th>
						<?php endif; ?>
                        <td><?= $comment['date_creation']; ?></td>
                        <td><a class="bi bi-trash" id="<?=$comment['id']?>" onClick="
                        let obj={yes: '<?= lang('common_lang.yes')?>',no: '<?=lang('common_lang.no')?>',message: '<?=lang('plafor_lang.comment_delete')?>',url: '<?=base_url('plafor/apprentice/delete_comment/'.$comment['id'].'/'.($acquisition_status['id']??''))?>'};
                        displayNotif(event.pageX, event.pageY,obj)"></a></td>

                    </tr> 
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?=base_url('notif.js')?>">
</script>