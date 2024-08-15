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
 * 'entry'             => string, required, Entry being deleted
 * 'type'              => string, required, Type of the entry being deleted
 * 'linked_entries'    => array, required, Entries that are linked with the entry being deleted
 * 'cancel_btn_url'    => string, required, Url of the cancel button    
 * 'primary_action'    => array, required, Action we ask a confirmation for
 *     [
 *         'name'  => string, Text of the primary action button
 *         'url'   => string, Url of the primary action button
 *     ],
 * 'secondary_action' => same thing as primary button, but not required
 * 
 */

?>

<div id="page-content-wrapper">
    <div class="container">
        <h1><?= lang('plafor_lang.title_delete_entry') ?></h1>

        <p><?= lang('plafor_lang.delete_entry_confirmation') ?></p>

        <div>
            <strong><?= $type ?></strong>
            <p><?= $entry ?></p>
        </div>

        <?php if(isset($linked_entries) && !empty($linked_entries)): ?>
            <div>
                <h2><?= lang('plafor_lang.entries_linked_to_entry_being_deleted') ?></h2>

                <div>
                <?php foreach($linked_entries as $linked_entry): ?>
                    <p><?= $linked_entry ?></p>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>

        <div class="text-right">
            <a class="btn btn-primary" href="<?= $cancel_btn_url ?>">
                <?= lang('common_lang.btn_cancel'); ?>
            </a>

            <?php if(isset($secondary_action) && !empty($secondary_action)): ?>
                <a class="btn btn-secondary" href="<?= $secondary_action['url'] ?>">
                    <?= $secondary_action['name'] ?>
                </a>
            <?php endif ?>

            <a class="btn btn-danger" href="<?= $primary_action['url'] ?>">
                <?= $primary_action['name'] ?>
            </a>

        </div>
    </div>
</div>
