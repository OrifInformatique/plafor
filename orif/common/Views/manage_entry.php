<?php

/**
 * Common view for entry management
 * This view can also be used for displaying an error
 * when trying to do an action.
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

/**
 * *** Data needed for this view ***
 *
 * @param string|null $type => Defines the type of the action. Required. Possible values below.
 * - 'delete'     => for a hard delete confirmation
 * - 'disable'    => for a soft delete confirmation
 * - 'reactivate' => for a reactivation confirmation
 * - 'NULL'       => for other kind of confirmation, where the text and buttons are configurable.
 *
 * @param ?string $confirmation_title => Title of the page. Can be empty. Required if $type === NULL.
 *
 * @param ?string $confirmation_subtitle => Subtitle of the page. Can be empty. Required if $type === NULL.
 *
 * @param array $entry            => The entry being managed. Required.
 *     [
 *          'type'               => string, Type of the entry being managed. Required.
 *          'name'               => string, Name of the entry being managed. Required.
 *          'message'            => string, Addidional info about the entry about to be managed. Can be empty.
 *          'data'               => array,  Additional data about the entry being managed. Can be empty. Structure of one data below.
 *          [
 *              'name'       => string, Name of the additional data. Required.
 *              'value'      => string, Value of the additional data. Required.
 *          ]
 *     ]
 *
 * @param ?array $linked_entries   => Entries that are linked with the entry being managed. Can be empty.
 *     [
 *         'type'                => string, Type of the linked entry. Required.
 *         'name'                => string, Name of the linked entry. Required.
 *         'data'                => array,  Additional data about the linked entry. Can be empty. Structure of one data below.
 *         [
 *             'name'       => Name of the additional data. Required.
 *             'value'      => Value of the additional data. Required.
 *         ]
 *     ]
 *
 * @param string $cancel_btn_url  => Url of the cancel button. Required.
 *
 * @param ?array $primary_action   => Action we ask a confirmation for. Can be empty. Required if $type === NULL.
 *     [
 *         'name'                => string, Text of the primary action button. Required.
 *         'url'                 => string, Url of the primary action button. Required.
 *     ]
 *
 * @param ?array $secondary_action => Alternative action to do. Can be empty. Same structure as primary button.
 *
 */



/**
 * No data is sent by this view.
 *
 */



/**
 * Action type management
 *
 */
switch($type)
{
    case 'disable':
        $confirmation_title    = $confirmation_title    ?? lang('common_lang.title_disable_entry');
        $confirmation_subtitle = $confirmation_subtitle ?? lang('common_lang.subtitle_disable_entry');

        $primary_action = $primary_action ??
        [
            'name' => lang('common_lang.btn_disable'),
            'url'  => base_url(uri_string().'/1')
        ];

        break;

    case 'delete':
        $confirmation_title    = $confirmation_title    ?? lang('common_lang.title_delete_entry');
        $confirmation_subtitle = $confirmation_subtitle ?? lang('common_lang.subtitle_delete_entry');

        $primary_action = $primary_action ??
        [
            'name' => lang('common_lang.btn_delete'),
            'url'  => base_url(uri_string().'/2')
        ];

        break;

    case 'reactivate':
        $confirmation_title    = $confirmation_title    ?? lang('common_lang.title_reactivate_entry');
        $confirmation_subtitle = $confirmation_subtitle ?? lang('common_lang.subtitle_reactivate_entry');

        $primary_action = $primary_action ??
        [
            'name' => lang('common_lang.btn_reactivate'),
            'url'  => base_url(uri_string().'/3')
        ];

        break;

    default:
        $confirmation_title    = $confirmation_title    ?? lang('common_lang.title_manage_entry');
        $confirmation_subtitle = $confirmation_subtitle ?? lang('common_lang.subtitle_manage_entry');
}

?>

<div id="page-content-wrapper">
    <div class="container">
        <h1><?= $confirmation_title ?></h1>

        <p><?= $confirmation_subtitle ?></p>

        <!-- Entry details -->
        <div class="alert alert-primary">
            <p class="mb-0">
                <strong>
                    <?= $entry['type'] ?>
                </strong>
                <br>
                <?= $entry['name'] ?>
            </p>

            <?php if(isset($entry['data']) && !empty($entry['data'])): ?>
                <p class="mt-2 mb-0">
                    <?php foreach($entry['data'] as $additional_data): ?>
                        <?= $additional_data['name'] ?> : <?= $additional_data['value'] ?><br>
                    <?php endforeach ?>
                </p>
            <?php endif ?>
        </div>

        <!-- Linked entries -->
        <?php if(isset($linked_entries) && !empty($linked_entries)): ?>
            <div>
                <h2><?= lang('common_lang.entries_linked') ?></h2>

                <div>
                    <?php foreach($linked_entries as $linked_entry): ?>
                        <div class="alert alert-secondary">
                            <p class="mb-0">
                                <strong>
                                    <?= $linked_entry['type'] ?>
                                </strong>
                                <br>
                                <?= $linked_entry['name'] ?>
                            </p>

                            <?php if(isset($linked_entry['data']) && !empty($linked_entry['data'])): ?>
                                <p class="mt-2 mb-0">
                                    <?php foreach($linked_entry['data'] as $additional_data): ?>
                                        <?= $additional_data['name'] ?> : <?= $additional_data['value'] ?><br>
                                    <?php endforeach ?>
                                </p>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>

        <!-- Additionnal message -->
        <?php if(isset($entry['message']) && !empty($entry['message'])): ?>
            <p class="alert alert-info">
                <?= $entry['message'] ?>
            </p>
        <?php endif ?>

        <!-- Actions -->
        <div class="text-right">
            <!-- Cancel button -->
            <a class="btn btn-secondary" href="<?= $cancel_btn_url ?>">
                <?= lang('common_lang.btn_cancel'); ?>
            </a>

            <!-- Secondary action -->
            <?php if(isset($secondary_action) && !empty($secondary_action)): ?>
                <a class="btn btn-primary" href="<?= $secondary_action['url'] ?>">
                    <?= $secondary_action['name'] ?>
                </a>
            <?php endif ?>

            <!-- Primary action -->
            <?php if(isset($primary_action) && !empty($primary_action)): ?>
                <a class="btn btn-danger" href="<?= $primary_action['url'] ?>">
                    <?= $primary_action['name'] ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</div>