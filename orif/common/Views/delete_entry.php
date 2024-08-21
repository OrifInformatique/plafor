<?php
/**
 * Common view for entry deltetion
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

/**
 * Values needed
 *
 * @param array entry            => The entry being deleted. Required.
 *     [
 *          'type'               => string, required, Type of the entry being deleted.
 *          'name'               => string, required, Name of the entry being deleted. Can be empty.
 *          'message'            => string, optional. Addidional info about the entry about to be deleted.
 *          'data'               => array, optional, Additional data about the entry being deleted.
 *              [
 *                  'name'       => Name of the additional data
 *                  'value'      => Value of the additional data
 *              ]
 *     ]
 * @param array linked_entries   => Entries that are linked with the entry being deleted. Required.
 *     [
 *         'type'                => string, required, Type of the linked entry
 *         'name'                => string, required, Name of the linked entry
 *         'data'               => array, optional, Additional data about the linked entry.
 *              [
 *                  'name'       => Name of the additional data
 *                  'value'      => Value of the additional data
 *              ]
 *     ]
 * @param string cancel_btn_url  => Url of the cancel button. Required
 * @param array primary_action   => Action we ask a confirmation for. Required.
 *     [
 *         'name'                => string, required, Text of the primary action button
 *         'url'                 => string, required, Url of the primary action button
 *     ]
 * @param array secondary_action => Alternative action to do. Same structure as primary button. Optional.
 *
 * NB :  Optional values won't be displayed if they are not provided.
 *
 */

?>

<div id="page-content-wrapper">
    <div class="container">
        <h1><?= lang('plafor_lang.title_delete_entry') ?></h1>

        <p><?= lang('plafor_lang.delete_entry_confirmation') ?></p>

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

        <?php if(isset($linked_entries) && !empty($linked_entries)): ?>
            <div>
                <h2><?= lang('plafor_lang.entries_linked_to_entry_being_deleted') ?></h2>

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

        <?php if(isset($entry['message']) && !empty($entry['message'])): ?>
            <p class="alert alert-info">
                <?= $entry['message'] ?>
            </p>
        <?php endif ?>

        <div class="text-right">
            <a class="btn btn-secondary" href="<?= $cancel_btn_url ?>">
                <?= lang('common_lang.btn_cancel'); ?>
            </a>

            <?php if(isset($secondary_action) && !empty($secondary_action)): ?>
                <a class="btn btn-primary" href="<?= $secondary_action['url'] ?>">
                    <?= $secondary_action['name'] ?>
                </a>
            <?php endif ?>

            <a class="btn btn-danger" href="<?= $primary_action['url'] ?>">
                <?= $primary_action['name'] ?>
            </a>
        </div>
    </div>
</div>