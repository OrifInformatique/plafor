<?php
/**
 * Fichier de vue pour save_operational_competence
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<?php
helper('form');
?>
<div class="container">
    <?=view('\Plafor\templates\navigator',['title'=>lang('plafor_lang.title_view_operational_competence')])?>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_course_plan')?></p>
        </div>
        <?php if(isset($course_plan)): ?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_formation_number')?></p>
            <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['formation_number']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_course_plan_official_name')?></p>
            <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan['id'])?>"><?=$course_plan['official_name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_competence_domain')?></p>
        </div>
        <?php if(isset($competence_domain)):?>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_symbol')?></p>
            <a href="<?= base_url('plafor/courseplan/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['symbol']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_competence_domain_name')?></p>
            <a href="<?= base_url('plafor/courseplan/view_competence_domain/'.$competence_domain['id']) ?>"><?=$competence_domain['name']?></a>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php if(isset($operational_competence)):?>
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_operational_competence')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_symbol')?></p>
            <p><?=$operational_competence['symbol']?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_name')?></p>
            <p><?=$operational_competence['name']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_methodologic')?></p>
            <p><?=$operational_competence['methodologic']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_social')?></p>
            <p><?=$operational_competence['social']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_operational_competence_personal')?></p>
            <p><?=$operational_competence['personal']?></p>
        </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.field_linked_objectives')?></p>
        </div>
        <div class="col-md-12">
            <div class="col-sm-12 text-right d-flex justify-content-between">
            <?php if (service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin): ?>
            <a href="<?=base_url('plafor/courseplan/save_objective/0/'.$operational_competence['id']) ?>" class="btn btn-primary"><?= lang('common_lang.btn_new_m')?></a>
            <?php endif;?>
                <span>
                <?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label','style'=>'padding-right:30px']);?>
                <?=form_checkbox('toggle_deleted', '', isset($with_archived)?$with_archived:false, [
                    'id' => 'toggle_deleted', 'class' => 'form-check-input'
                ]);?>
                </span>
            </div>
            <table class="responsiveTable table table-striped table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_objectives_symbols')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_objectives_taxonomies')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('plafor_lang.field_objectives_names')?></span></th>
                    <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin):?>
                        <th></th>
                        <th></th>
                    <?php endif;?>
                </tr>
            </thead>
            <tbody><?php
            if (isset($objectives)):
            foreach ($objectives as $objective){
                ?><tr>
                    <td>
                        <a><?=$objective['symbol']?></a>

                    </td>
                    <td>
                        <span class="font-weight-bold descTitle" style="display: none"><?=lang('plafor_lang.field_taxonomy')?></span>
                        <a><?=$objective['taxonomy']?></a>

                    </td>
                    <td><?=$objective['name']?></td>

                <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin):?>
                    <td><a href="<?= base_url('plafor/courseplan/save_objective/'.$objective['id'].'/'.$operational_competence['id']); ?>"><i class="bi bi-pencil"></i></a></td>
                    <td><a href="<?= base_url('plafor/courseplan/delete_objective/'.$objective['id']); ?>" class="<?=$operational_competence['archive']==null?'bi bi-trash':'bi bi-reply-all-fill'?>"></td>
                <?php endif;?>
                <?php
                }?></tr>
            <?php endif; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>
<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;
            history.replaceState(null,null,'<?=base_url('/plafor/courseplan/view_operational_competence/'.$operational_competence['id']);?>?wa='+(checked?1:0))
            $.get('<?=base_url('/plafor/courseplan/view_operational_competence/')."/${operational_competence['id']}";?>?wa='+(checked?1:0),(datas)=>{
                let parser=new DOMParser();
                parser.parseFromString(datas,'text/html').querySelectorAll('table').forEach((domTag)=>{
                    document.querySelectorAll('table').forEach((thisDomTag)=>{
                        thisDomTag.innerHTML=domTag.innerHTML;
                    })
                })
            })
        })
    });
</script>
<script defer type="text/javascript">
    window.addEventListener('resize',()=>{

        if (window.innerWidth<490){

            if (document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)!==null?window.getComputedStyle(document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)).display!=="none":false)
                document.querySelectorAll('.responsiveTable td:nth-child(2)').forEach((element)=>{
                let tax=document.createElement('span');
                tax.innerHTML=`<span class='taxonomyResponsive'>${element.innerHTML}<span>`

                element.previousElementSibling.appendChild(tax);
                element.remove();

            })
        }
        else{
            document.querySelectorAll('.responsiveTable td:nth-child(1) .taxonomyResponsive').forEach((element)=>{
                let td=document.createElement('td');
                td.innerHTML=element.innerHTML;
                if (element.parentElement.parentElement.nextElementSibling.querySelector('.descTitle')==null)
                element.parentElement.parentElement.after(td);
                element.remove();
            })
        }

    });
    if (window.innerWidth<490){

        if (document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)!==null?window.getComputedStyle(document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)).display!=="none":false)
            document.querySelectorAll('.responsiveTable td:nth-child(2)').forEach((element)=>{
                let tax=document.createElement('span');
                tax.innerHTML=`<span class='taxonomyResponsive'>${element.innerHTML}<span>`

                element.previousElementSibling.appendChild(tax);
                element.remove();

            })
    }
    else{
        document.querySelectorAll('.responsiveTable td:nth-child(1) .taxonomyResponsive').forEach((element)=>{
            let td=document.createElement('td');
            td.innerHTML=element.innerHTML;
            if (element.parentElement.parentElement.nextElementSibling.querySelector('.descTitle')==null)
                element.parentElement.parentElement.after(td);
            element.remove();
        })
    }
</script>