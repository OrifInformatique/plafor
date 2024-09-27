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
        <?= view('Common\Views\items_list',
        [
            'items'   => $domains_title,
            'columns' =>
            [
                'domain_title'        => lang('Grades.domain_title'),
            ],
            'with_deleted'      => true,
            'allow_hard_delete' => true,
            'url_create'        => 'plafor/teachingdomain/saveTeachingDomainTitle',
            'url_update'        => 'plafor/teachingdomain/saveTeachingDomainTitle/',
            'url_delete'        => 'plafor/teachingdomain/deleteTeachingDomainTitle/1/',
            'url_hard_delete'   => 'plafor/teachingdomain/deleteTeachingDomainTitle/2/',
            'url_restore'       => 'plafor/teachingdomain/deleteTeachingDomainTitle/3/',
            'url_getView'       => 'plafor/teachingdomain/getAllDomainsTitle'
        ])
        ?>
    </div>
</div>