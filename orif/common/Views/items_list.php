<?php

/**
 * Generic view to display items list in a bootstrap table, optionally with links
 * for creating, reading details, updating or deleting items.
 *
 * @author      Orif, section informatique (ViDi, AeDa, PoMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?string $list_title Title of the list, displayed on top of the list.
 *
 * @param array $items Items to display, each item being a subarray with multiple properties.
 *
 * @param array $columns Columns to display in the list.
 * Key is the name of the corresponding items property in items subarrays.
 * Value is the header to display for each column.
 *
 * @param ?string $btn_create_label Label for the "create" button.
 * If not set, default label is used.
 *
 * @param ?bool $with_deleted Defines whether to display the soft deleted items of the list.
 * If null, the "Display disabled items" checkbox won't be displayed.
 *
 * @param ?string $display_deleted_label Label displayed near the soft delete checkbox.
 * If not set, lang('common_lang.btn_show_disabled') is used by default.
 *
 * @param ?string $deleted_field Name of the field that is used as a "soft delete key".
 * If $with_deleted === true and $deleted_field is not set, $deleted_field default value is 'archive'.
 *
 * @param ?bool $allow_hard_delete Defines whether we can hard delete entries.
 *
 * @param ?string $primary_key_field Name of the primary key of the items.
 * To construct the links to details/update/delete controllers.
 * If not set, "id" is used by default.
 *
 * @param ?string $url_detail Link to the controller method that displays item's details.
 * If not set, no "detail" link will be displayed.
 *
 * @param ?string $url_create Link to the controller method that displays a form to create a new item.
 * If not set, no "create" button will be displayed.
 *
 * @param ?string $url_update Link to the controller method that displays a form to update the item.
 * If not set, no "update" link will be displayed.
 *
 * @param ?string $url_delete Link to the controller method that deletes the item.
 * If not set, no "delete" link will be displayed.
 *
 * @param ?string $url_getView Link used to dynamically update the view's content with javascript.
 * It should call a method that returns the view's content.
 * If not set, the "Display disabled items" checkbox won't be displayed.
 *
 * @param ?string $url_restore Link to the controller method that restores a soft deleted item.
 * If not set, no "restore" button will be displayed.
 *
 * @param ?string $url_duplicate Link to the controller method that gives the possibility to duplicate an item.
 * If not set, no "copy" button will be displayed.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method GET
 *
 * action $url_getView
 *
 * @param bool $wa Defines whether to show deleted entries
 *
 */



/**
 * *** Example call method ***
 *
 * public function display_items($with_deleted = false) {
 *   $data['list_title'] = "Test items_list view";
 *
 *   $data['columns'] = ['name' => 'Name',
 *                       'inventory_nb' => 'Inventory nb',
 *                       'buying_date' => 'Buying date',
 *                       'warranty_duration' => 'Warranty duration'];
 *
 *   // Assume these are active items
 *   $data['items'] = [
 *       ['id' => '1', 'name' => 'Item 1', 'inventory_nb' => 'ITM0001', 'buying_date' => '01/01/2020', 'warranty_duration' => '12 months', 'deleted' => ''],
 *       ['id' => '2', 'name' => 'Item 2', 'inventory_nb' => 'ITM0002', 'buying_date' => '01/02/2020', 'warranty_duration' => '12 months', 'deleted' => ''],
 *       ['id' => '3', 'name' => 'Item 3', 'inventory_nb' => 'ITM0003', 'buying_date' => '01/03/2020', 'warranty_duration' => '12 months', 'deleted' => '']
 *   ];
 *
 *   if ($with_deleted) {
 *       // Assume these are soft_deleted items
 *       $data['items'] = array_merge($data['items'], [
 *           ['id' => '10', 'name' => 'Item 10', 'inventory_nb' => 'ITM0010', 'buying_date' => '01/01/2020', 'warranty_duration' => '12 months', 'deleted' => '2000-01-01'],
 *           ['id' => '11', 'name' => 'Item 11', 'inventory_nb' => 'ITM0011', 'buying_date' => '01/02/2020', 'warranty_duration' => '12 months', 'deleted' => '2000-01-01'],
 *           ['id' => '12', 'name' => 'Item 12', 'inventory_nb' => 'ITM0012', 'buying_date' => '01/03/2020', 'warranty_duration' => '12 months', 'deleted' => '2000-01-01']
 *       ]);
 *   }
 *
 *   $data['primary_key_field']  = 'id';
 *   $data['btn_create_label']   = 'Add an item';
 *   $data['with_deleted']       = $with_deleted;
 *   $data['deleted_field']      = 'deleted';
 *   $data['url_detail'] = "items_list/detail/";
 *   $data['url_update'] = "items_list/update/";
 *   $data['url_delete'] = "items_list/delete/";
 *   $data['url_create'] = "items_list/create/";
 *   $data['url_getView'] = "items_list/display_item/";
 *   $data['url_restore'] = "items_list/restore_item/";
 *   $data['url_duplicate'] = "items_list/duplicate_item/";
 *
 *	 return $this->display_view('Common\Views\items_list', $data);
 * }
 */

/**
 * Parameters default values
 *
 */
if(!isset($btn_create_label))
    $btn_create_label = lang('common_lang.btn_add');

if(isset($with_deleted) && $with_deleted)
{
    if(!isset($display_deleted_label))
        $display_deleted_label = lang('common_lang.btn_show_disabled');
}

else
{
    $with_deleted = false;
    unset($display_deleted_label);
}

if(!isset($deleted_field))
    $deleted_field = 'archive';

if(!isset($allow_hard_delete))
    $allow_hard_delete = false;

if(!isset($primary_key_field))
    $primary_key_field = 'id';

helper('form');

?>

<div class="container">
    <div class="row mb-2">
        <?php if(isset($list_title)): ?>
            <div class="col-12">
                <h3><?= esc($list_title) ?></h3>
            </div>
        <?php endif ?>

        <?php if(isset($url_create)): ?>
            <div class="col-sm-6">
                <a class="btn btn-primary" href="<?= base_url(esc($url_create)) ?>">
                    <?= esc($btn_create_label) ?>
                </a>
            </div>
        <?php endif ?>

        <?php if ($with_deleted && isset($url_getView)): ?>
            <div class="col-sm-6 d-flex justify-content-end align-content-center">
                <?= form_label(lang($display_deleted_label), 'toggle_deleted',
                    ['class' => 'form-check-label mr-2', 'style' => 'margin-top: 5.5px;']) ?>

                <?= form_checkbox('toggle_deleted', '', $with_deleted,
                    ['class' => 'align-self-center', 'id' => 'toggle_deleted',
                    'style' => 'width: 20px; height: 20px;']) ?>
            </div>
        <?php endif ?>
    </div>

    <div id="itemsList" class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <th><?= $column ?></th>
                    <?php endforeach ?>

                    <!-- Add the "action" column (for detail/update/delete links) -->
                    <?php if(isset($url_detail) || isset($url_update) || isset($url_delete)
                            || isset($url_restore) || isset($url_duplicate)): ?>
                        <th></th>
                    <?php endif ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <!-- Only display item's properties wich are listed in "columns" variable in the order of the columns -->
                        <?php
                        foreach($columns as $columnKey => $column)
                        {
                            if(array_key_exists($columnKey, $item))
                            {
                                if(isset($deleted_field) && !empty($item[$deleted_field]))
                                    echo '<td><del>' . esc($item[$columnKey]) . '</del></td>';

                                else
                                    echo '<td>' . esc($item[$columnKey]) . '</td>';
                            }

                            else
                                echo '<td></td>';
                        }
                        ?>

                        <!-- Add the "action" column (for detail/update/delete links) -->
                        <td class="text-right">
                            <!-- Bootstrap details icon ("Card text"), redirect to url_detail, adding /primary_key as parameter -->
                            <?php if(isset($url_detail)): ?>
                                <a href="<?= base_url(esc($url_detail.$item[$primary_key_field])) ?>"
                                    class="text-decoration-none" title="<?=lang('common_lang.btn_details') ?>" >
                                    <i class="bi bi-card-text" style="font-size: 20px;"></i>
                                </a>
                            <?php endif ?>

                            <!-- Bootstrap edit icon ("Pencil"), redirect to url_update, adding /primary_key as parameter -->
                            <?php if(isset($url_update)): ?>
                                <a href="<?= base_url(esc($url_update.$item[$primary_key_field])) ?>"
                                    class="text-decoration-none" title="<?=lang('common_lang.btn_edit') ?>" >
                                    <i class="bi bi-pencil" style="font-size: 20px;"></i>
                                </a>
                            <?php endif ?>

                            <!-- Bootstrap copy icon ("files") , redirect to url_duplicate, adding /primary_key as parameter -->
                            <?php if(isset($url_duplicate)): ?>
                                <a href="<?= base_url(esc($url_duplicate.$item[$primary_key_field])) ?>"
                                    class="text-decoration-none" title="<?=lang('common_lang.btn_copy') ?>" >
                                    <i class="bi bi-files" style="font-size: 20px;"></i>
                                </a>
                            <?php endif ?>

                            <!-- Bootstrap delete icon ("Trash"), redirect to url_delete, adding /primary_key as parameter -->
                            <?php if(isset($url_delete) && $allow_hard_delete): ?>
                                <a href="<?= base_url(esc($url_delete.$item[$primary_key_field])) ?>"
                                    class="text-decoration-none" title="<?=lang('common_lang.btn_hard_delete') ?>" >
                                    <i class="bi bi-trash text-danger" style="font-size: 20px;"></i>
                                </a>

                            <?php elseif(isset($url_delete) && empty($item[$deleted_field])): ?>
                                <a href="<?= base_url(esc($url_delete.$item[$primary_key_field])) ?>"
                                    class="text-decoration-none" title="<?=lang('common_lang.btn_delete') ?>" >
                                    <i class="bi bi-trash" style="font-size: 20px;"></i>
                                </a>
                            <?php endif ?>

                            <!-- Bootstrap restore icon ("arrow-counterclockwise"), redirect to url_restore, adding/primary_key as parameter -->
                            <?php if(isset($url_restore) && !empty($item[$deleted_field])) : ?>
                                <a href="<?= base_url(esc($url_restore . $item[$primary_key_field])) ?>"
                                    class="text-decoration-none" title="<?=lang('common_lang.btn_restore') ?>" >
                                    <i class="bi bi-arrow-counterclockwise" style="font-size: 20px;"></i>
                                </a>
                            <?php endif ?>

                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JQuery script to refresh items list after user action -->
<?php if(isset($url_getView)): ?>
    <script>
        $(document).ready(() =>
        {
            $('#toggle_deleted').change(e =>
            {
                let checked = e.currentTarget.checked;

                history.replaceState(null, null, '<?= base_url($url_getView) ?>?wa=' + (checked ? 1 : 0))
                $.get('<?= base_url($url_getView) ?>?wa=' + (checked ? 1 : 0), (datas) => {
                    let parser = new DOMParser();

                    parser.parseFromString(datas, 'text/html').querySelectorAll('#itemsList').forEach((domTag) =>
                    {
                        document.querySelectorAll('#itemsList').forEach((thisDomTag) =>
                        {
                            thisDomTag.innerHTML = domTag.innerHTML;
                        })
                    })
                })
            })
        });
    </script>
<?php endif ?>