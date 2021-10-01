<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <?php if(isset($course_plan)): ?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_formation_number')?></p>
            <a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['formation_number']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_official_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['official_name']?></a>
        </div>
        <?php endif;?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_competence_domain')?></p>
        </div>
        <?php if (isset($competence_domain)): ?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_symbol')?></p>
            <a href="<?= base_url('plafor/apprentice/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['symbol']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_operational_competence')?></p>
        </div>
        <?php if (isset($operational_competence)): ?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_symbol')?></p>
            <a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id']) ?>"><?=$operational_competence['symbol']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id']) ?>"><?=$operational_competence['name']?></a>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_methodologic')?></p>
            <a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id']) ?>"><?=$operational_competence['methodologic']?></a>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_social')?></p>
            <a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id']) ?>"><?=$operational_competence['social']?></a>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_personal')?></p>
            <a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id']) ?>"><?=$operational_competence['personal']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_objective')?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_objective_symbol')?></p>
            <p><?=$objective['symbol']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_objective_taxonomy')?></p>
            <p><?=$objective['taxonomy']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_objective_name')?></p>
            <p><?=$objective['name']?></p>
        </div>
    </div>
</div>