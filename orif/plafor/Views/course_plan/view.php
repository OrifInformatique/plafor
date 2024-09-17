<?php

/**
 * Shows a course plan and its linked competence domains
 * and teaching domains.
 *
 * Called by CoursePlan/view_course_plan($course_plan_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * // TODO : Directly put the title in the view, then delete this param
 * @param string $title Page title.
 *
 * @param array $course_plan Course plan we want to see its details.
 * All fields from table.
 *
 * @param array $competence_domains Competence domains linked to the course plan.
 * All fields from table.
 *
 * @param array $teaching_domains Teaching domains linked to the course plan.
 * See \Views/domain/view for data structure needed.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action CoursePlan/view_course_plan($course_plan_id)
 *
 * @param bool $wa Defines whether to show deleted entries.
 *
 */

helper('form');

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_course_plan')]) ?>

    <!-- Title -->
    <div class="row">
        <div class="col">
            <h2><?= $title ?></h2>
        </div>
    </div>

    <!-- Course plan details -->
    <?= view('\Plafor/course_plan/details', $course_plan) ?>

    <!-- Linked competence domains -->
    <div class="row">
        <div class="col-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_competence_domains_linked') ?></p>
        </div>

        <div class="col-12">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_admin): ?>
                    <a href="<?= base_url('plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/0/') ?>" class="btn btn-primary">
                        <?= lang('common_lang.btn_new_m') ?>
                    </a>
                <?php endif ?>

                <!-- TODO : Make the checkbox display deleted competence domains when checked -->
                <div>
                    <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted',
                        ['class' => 'form-check-label', 'style'=>'padding-right: 30px;']) ?>

                    <?= form_checkbox('toggle_deleted', '', $with_archived ?? false,
                        ['class' => 'form-check-input', 'id' => 'toggle_deleted']) ?>
                </div>
            </div>

            <?php

            $datas = [];

            foreach ($competence_domains as $competence_domain)
            {
                $datas[] =
                [
                    'id'      => $competence_domain['id'],
                    'symbol'  => $competence_domain['symbol'],
                    'compDom' => $competence_domain['name']
                ];
            }

            echo view('Common\Views\items_list',
            [
                'columns' =>
                [
                    'symbol'  => lang('plafor_lang.symbol'),
                    'compDom' => lang('plafor_lang.competence_domain')
                ],
                'items'             => $datas,
                'primary_key_field' => 'id',
                'url_update'        => 'plafor/courseplan/save_competence_domain/'.$course_plan['id'].'/',
                'url_delete'        => 'plafor/courseplan/delete_competence_domain/',
                'url_detail'        => 'plafor/courseplan/view_competence_domain/'
            ]);
            ?>
        </div>
    </div>

    <!-- Linked teaching domains -->
    <?= view('\Plafor/domain/view', $teaching_domains) ?>

</div>

<!-- TODO : Make the checkbox display deleted competence domains when checked -->
<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;

            history.replaceState(null, null, '<?= base_url('/plafor/courseplan/view_course_plan/'.$course_plan['id']) ?>?wa=' + (checked ? 1 : 0))

            $.get('<?= base_url('/plafor/courseplan/view_course_plan/'.$course_plan['id']) ?>?wa=' + (checked ? 1 : 0), (datas) => {
                let parser=new DOMParser();

                parser.parseFromString(datas, 'text/html').querySelectorAll('table').forEach((domTag) => {
                    document.querySelectorAll('table').forEach((thisDomTag) => {
                        thisDomTag.innerHTML=domTag.innerHTML;
                    })
                })
            })
        })
    });
</script>