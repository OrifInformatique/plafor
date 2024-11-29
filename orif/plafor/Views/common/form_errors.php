<?php

/**
 * Shows the errors after submitting an invalid form.
 *
 * Called by all save views.
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param ?array $errors Form validation errors.
 * If empty, nothing will be displayed.
 *
 */



/**
 * No data is sent by this view
 *
 */

?>

<?php if(!empty($errors)): ?>
    <div class="row">
        <div class="col">
            <div class="alert alert-danger">
                <?php foreach($errors as $error): ?>
                    <?= $error ?><br>
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?php endif ?>