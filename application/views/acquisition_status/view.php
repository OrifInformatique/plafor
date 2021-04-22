<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_acquisition_status')?></p>
        </div><div class="col-md-2">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_symbol')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$objective->id)?>"><?=$objective->symbol?></a>
        </div><div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_name')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$objective->id)?>"><?=$objective->name?></a>
        </div><div class="col-md-2">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_taxonomy')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$objective->id)?>"><?=$objective->taxonomy?></a>
        </div>
        <div class="col-md-2">
            <p class="font-weight-bold"><?=$this->lang->line('field_acquisition_level')?></p>
            <a href="<?= base_url('apprentice/save_acquisition_status/'.$acquisition_status->id)?>"><?=$acquisition_level->name?></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('field_linked_comments')?></p>
		</div>
		<?php if($_SESSION['user_access'] >= ACCESS_LVL_TRAINER) { ?>
		<a href="<?= base_url('apprentice/add_comment/'.$acquisition_status->id); ?>" class="btn btn-primary"><?= $this->lang->line('title_comment_new'); ?></a>
		<?php } ?>
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?= $this->lang->line('field_comment'); ?></th>
                        <th><?= $this->lang->line('field_comment_creater'); ?></th>
                        <th><?= $this->lang->line('field_comment_date_creation'); ?></th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$trainersSorted = [];
				foreach ($trainers as $trainer) {
					$trainersSorted[$trainer->id] = $trainer;
				}
				foreach ($comments as $comment):
				?>
                    <tr>
                        <td><?= $comment->comment; ?></td>
						<?php
						if (isset($trainersSorted[$comment->fk_trainer])): ?>
                        <th><?= $trainer->username; ?></th>
						<?php endif; ?>
                        <td><?= $comment->date_creation; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<pre><?= var_dump($trainers)?></pre>
