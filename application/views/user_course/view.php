<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_user_course')?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_user_course_date_begin')?></p>
            <p><?=$user_course->date_begin?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_user_course_date_end')?></p>
            <p><?=$user_course->date_end?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_user_course_status')?></p>
            <p><?=$status->name?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?= $this->lang->line('apprentice') ?></p>
            <a href="<?= base_url('apprentice/view_apprentice/'.$apprentice->id)?>"><?=$apprentice->username?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?= $this->lang->line('course_plan') ?></p>
            <a href="<?= base_url('apprentice/view_course_plan/'.$course_plan->id)?>"><span class="font-weight-bold"><?=$course_plan->formation_number?> </span><?=$course_plan->official_name?></a>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-primary text-white" href="<?= base_url('apprentice/save_user_course/'.$apprentice->id."/".$user_course->id)?>"><?= $this->lang->line('title_user_course_update') ?></a>
                <a class="btn btn-danger text-white" href="<?= base_url('apprentice/delete_user_course/'.$user_course->id)?>"><?= $this->lang->line('title_user_course_delete') ?></a>
            </div>
        </div>
    </div>
</div>