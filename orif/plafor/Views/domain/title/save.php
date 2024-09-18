<?php

/**
 * Let the edition of a teaching domain title.
 *
 * Called by TeachingDomainController/saveTeachingDomainTitle($domain_title_id)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Page title.
 * Should be lang('Grades.update_domain_title') or lang('Grades.create_domain_title');
 *
 * @param ?int $domain_title_id ID of the domain title.
 * To edit an existng entry.
 *
 * @param ?string $domain_title Name of the domain.
 * To edit an existing entry or remember user input.
 *
 * @param array $errors teaching_domain_title_model errors. Can be empty.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action TeachingDomainController/saveTeachingDomain($course_plan_id, $domain_id)
 *
 * @param string $domain_title Name of the domain.
 *
 */

helper('form');

$domain_title_label = lang('Grades.name').' '.lang('Grades.of_a').' '.
    strtolower(lang('common_lang.btn_new_m').' '.lang('Grades.domain'));

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Form errors -->
    <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

    <?= form_open(base_url('plafor/teachingdomain/saveTeachingDomainTitle/'.($domain_title_id ?? 0))) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label($domain_title_label, 'domain_title',
                    ['class' => 'form-label']) ?>

                <?= form_input('domain_title', $domain_title ?? '',
                    ['class' => 'form-control', 'id' => 'domain_title']) ?>
            </div>
        </div>

        <div class="row">
            <?php if($domain_title_id > 0): ?>
                <div class="col">
                    <a href="<?= base_url('plafor/teachingdomain/deleteTeachingDomainTitle/'.$domain_title_id) ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete') ?>
                    </a>
                </div>
            <?php endif ?>

            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/teachingdomain/getAllDomainsTitle') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>