<?php

/**
 * Lists all teaching modules.
 *
 * Called by TeachingDomainController/getAllDomainsTitle($with_archived)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $domains_title List of all domains title.
 * All fields from table.
 *
 */



/**
 * No data is sent by this view.
 *
 */

helper('form');

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => lang('Grades.domains_title_list')]) ?>

    <div class="row">
        <div class="col">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <div>
                    <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin): ?>
                        <a href="<?= base_url('plafor/teachingdomain/saveTeachingDomainTitle') ?>" class="btn btn-primary">
                            <?= lang('common_lang.btn_new_m') ?>
                        </a>
                    <?php endif ?>
                </div>

                <div>
                    <!-- TODO : Display deleted modules when checked -->
                    <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted',
                        ['class' => 'form-check-label', 'style' => 'padding-right: 30px;']) ?>

                    <?=form_checkbox('toggle_deleted', '', $with_archived ?? false,
                        ['class' => 'form-check-input', 'id' => 'toggle_deleted']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?= view('Common\Views\items_list',
        [
            'columns' =>
            [
                'domain_title'        => lang('Grades.domain_title'),
            ],
            'items'             => $domains_title,
            'primary_key_field' => 'id',
            'url_update'        => 'plafor/teachingdomain/saveTeachingDomainTitle/',
            'url_delete'        => 'plafor/teachingdomain/deleteTeachingDomainTitle/'
        ])
        ?>
    </div>
</div>

<script defer>
    $(document).ready(function(){
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;

            history.replaceState(null, null, '<?= base_url('/plafor/teachingdomain/getAllDomainsTitle/') ?>?wa=' + (checked ? 1 : 0))

            $.get('<?= base_url('/plafor/teachingdomain/getAllDomainsTitle/') ?>?wa=' + (checked ? 1 : 0), (datas) => {
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