<?php

/**
 * Shows an operational competence and its linked objectives.
 *
 * Called by CoursePlan/view_operational_competence($operational_comp_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed by this view ***
 *
 * // TODO : Directly put the title in the view, then delete this param
 * @param string $title Page title.
 *
 * @param array $course_plan Course plan containing the operational competence.
 * All fields from table.
 *
 * @param array $competence_domain Competence domain containing the operational competence.
 * All fields from table.
 *
 * @param array $operational_competence Operational competence.
 * All fields from table.
 *
 * @param array $objectives Objectives linked to the operational competence.
 * All fields from table.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action CoursePlan/view_operational_competence($operational_comp_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form');

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_operational_competence')]) ?>

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Course plan details -->
    <?= view('\Plafor/course_plan/details', $course_plan) ?>

    <!-- Competence domain details -->
    <?= view('\Plafor/competence_domain/details', $competence_domain) ?>

    <!-- Operational competence details -->
    <?= view('\Plafor/operational_competence/details', $operational_competence) ?>

    <!-- Linked objectives -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.field_linked_objectives')?></p>
        </div>

        <div class="col-md-12">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_admin): ?>
                    <a href="<?=base_url('plafor/courseplan/save_objective/0/'.$operational_competence['id']) ?>" class="btn btn-primary">
                        <?= lang('common_lang.btn_new_m')?>
                    </a>
                <?php endif ?>

                <div>
                    <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted',
                        ['class' => 'form-check-label', 'style' => 'padding-right: 30px;']) ?>

                    <?= form_checkbox('toggle_deleted', '', $with_archived ?? false,
                        ['class' => 'form-check-input', 'id' => 'toggle_deleted']) ?>
                </div>
            </div>

            <?= view('Common\Views\items_list',
            [
                'columns' =>
                [
                    'symbol'    => lang('plafor_lang.field_objectives_symbols'),
                    'taxonomy'  => lang('plafor_lang.field_objectives_taxonomies'),
                    'name'      => lang('plafor_lang.field_objectives_names')
                ],
                'items'             => $objectives,
                'primary_key_field' => 'id',
                'url_update'        => 'plafor/courseplan/save_objective/',
                'url_delete'        => 'plafor/courseplan/delete_objective/',
                'url_detail'        => 'plafor/courseplan/view_objective/',
            ])
            ?>
        </div>
    </div>
</div>

<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;

            history.replaceState(null, null, '<?= base_url('/plafor/courseplan/view_operational_competence/'.$operational_competence['id']) ?>?wa='+(checked ? 1 : 0))

            $.get('<?= base_url('/plafor/courseplan/view_operational_competence/'.$operational_competence['id']) ?>?wa='+(checked ? 1 : 0), (datas) => {
                let parser=new DOMParser();

                parser.parseFromString(datas,'text/html').querySelectorAll('table').forEach((domTag) => {
                    document.querySelectorAll('table').forEach((thisDomTag) => {
                        thisDomTag.innerHTML=domTag.innerHTML;
                    })
                })
            })
        })
    });
</script>

<script type="text/javascript" defer>
    window.addEventListener('resize', () => {
        if (window.innerWidth<490){
            if (document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0) !== null ? window.getComputedStyle(document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)).display !== "none" : false)
                document.querySelectorAll('.responsiveTable td:nth-child(2)').forEach((element) => {
                let tax=document.createElement('span');
                tax.innerHTML=`<span class='taxonomyResponsive'>${element.innerHTML}<span>`;

                element.previousElementSibling.appendChild(tax);
                element.remove();

            })
        }
        else {
            document.querySelectorAll('.responsiveTable td:nth-child(1) .taxonomyResponsive').forEach((element) => {
                let td  =document.createElement('td');
                td.innerHTML = element.innerHTML;
                if (element.parentElement.parentElement.nextElementSibling.querySelector('.descTitle') == null)
                element.parentElement.parentElement.after(td);
                element.remove();
            })
        }

    });
    if (window.innerWidth<490){
        if (document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0) !== null ? window.getComputedStyle(document.querySelectorAll('.responsiveTable td:nth-child(2) .descTitle').item(0)).display !== "none" : false)
            document.querySelectorAll('.responsiveTable td:nth-child(2)').forEach((element) => {
                let tax = document.createElement('span');
                tax.innerHTML=`<span class='taxonomyResponsive'>${element.innerHTML}<span>`;

                element.previousElementSibling.appendChild(tax);
                element.remove();

            })
    }
    else {
        document.querySelectorAll('.responsiveTable td:nth-child(1) .taxonomyResponsive').forEach((element) => {
            let td = document.createElement('td');
            td.innerHTML = element.innerHTML;
            if (element.parentElement.parentElement.nextElementSibling.querySelector('.descTitle') == null)
                element.parentElement.parentElement.after(td);
            element.remove();
        })
    }
</script>