<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_formation_number')?></p>
            <a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['formation_number']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_official_name')?></p>
            <a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['official_name']?></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.details_competence_domain')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_symbol')?></p>
            <p><?=$competence_domain['symbol']?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_name')?></p>
            <p><?=$competence_domain['name']?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_operational_competence_linked')?></p>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_operational_competences_symbols')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_operational_competences_names')?></span></th>
                </tr>
            </thead>
            <tbody><?php
            foreach (\Plafor\Models\CompetenceDomainModel::getOperationalCompetences($competence_domain['id']) as $operational_competence){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id'])?>"><?=$operational_competence['symbol']?></a></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_operational_competence/'.$operational_competence['id'])?>"><?=$operational_competence['name']?></a></td><?php
                }?></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>