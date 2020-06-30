<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_acquisition_status')?></p>
        </div><div class="col-md-2">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_symbol')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$acquisition_status->objective->id)?>"><?=$acquisition_status->objective->symbol?></a>
        </div><div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_name')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$acquisition_status->objective->id)?>"><?=$acquisition_status->objective->name?></a>
        </div><div class="col-md-2">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_taxonomy')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$acquisition_status->objective->id)?>"><?=$acquisition_status->objective->taxonomy?></a>
        </div>
        <div class="col-md-2">
            <p class="font-weight-bold"><?=$this->lang->line('field_acquisition_level')?></p>
            <a href="<?= base_url('apprentice/view_objective/'.$acquisition_status->objective->id)?>"><?=$acquisition_status->acquisition_level->name?></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('field_linked_comments')?></p>
        </div>
        <a href="<?= base_url('apprentice/add_comment/'.$acquisition_status->id); ?>" class="btn btn-primary"><?= $this->lang->line('title_comment_new'); ?></a>
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
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?= $comment->comment; ?></td>
                        <?php
                        foreach($trainers as $trainer):
                            if($trainer->id == $comment->fk_trainer):
                        ?>
                        <th><?= $trainer->username; ?></th>
                        <?php 
                            endif;
                        endforeach;
                        ?>
                        <td><?= $comment->date_creation; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<pre><?= var_dump($trainers)?></pre>