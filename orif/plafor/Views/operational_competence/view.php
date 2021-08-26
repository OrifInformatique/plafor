<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <?php if(isset($course_plan)): ?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_course_plan_formation_number')?></p>
            <a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['formation_number']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_course_plan_official_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['official_name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_competence_domain')?></p>
        </div>
        <?php if(isset($competence_domain)):?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_competence_domain_symbol')?></p>
            <a href="<?= base_url('plafor/apprentice/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['symbol']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_competence_domain_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if(isset($operational_competence)):?>
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_operational_competence')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_operational_competence_symbol')?></p>
            <p><?=$operational_competence['symbol']?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_operational_competence_name')?></p>
            <p><?=$operational_competence['name']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('user_lang.field_operational_competence_methodologic')?></p>
            <p><?=$operational_competence['methodologic']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('user_lang.field_operational_competence_social')?></p>
            <p><?=$operational_competence['social']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('user_lang.field_operational_competence_personal')?></p>
            <p><?=$operational_competence['personal']?></p>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('user_lang.field_linked_objectives')?></p>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=lang('user_lang.field_objectives_symbols')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('user_lang.field_objectives_taxonomies')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('user_lang.field_objectives_names')?></span></th>
                </tr>
            </thead>
            <tbody><?php
            if (isset($objectives)):
            foreach ($objectives as $objective){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?=$objective['symbol']?></a></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?=$objective['taxonomy']?></a></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_objective/'.$objective['id'])?>"><?=$objective['name']?></a></td><?php
                }?></tr>
            <?php endif; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>