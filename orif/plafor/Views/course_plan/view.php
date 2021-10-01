<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_formation_number')?></p>
            <p><?=$course_plan['formation_number']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_official_name')?></p>
            <p><?=$course_plan['official_name']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_date_begin')?></p>
            <p><?=$course_plan['date_begin']?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_competence_domains_linked')?></p>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_competence_domains_symbols')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_competence_domains_names')?></span></th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($competence_domains as $competence_domain){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('plafor/apprentice/view_competence_domain/'.$competence_domain['id'])?>"><?=$competence_domain['symbol']?></a></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_competence_domain/'.$competence_domain['id'])?>"><?=$competence_domain['name']?></a></td><?php
                }?></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>