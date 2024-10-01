<?php

/**
 * Shows the details of a competence domain.
 *
 * Called by \Views/competence_domain/view
 * Called by \Views/operational_comptence/view
 * Called by \Views/objective/view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $competence_domain Competence domain.
 *
 */



/**
 * No data is sent by this view.
 *
 */

?>

<div class="row">
    <div class="col-12">
        <p class="bg-primary text-white">
            <?= lang('plafor_lang.details_competence_domain') ?>
        </p>
    </div>

    <div class="col-12">
        <p>
            <strong><?= $competence_domain['symbol'] ?></strong> : <?= $competence_domain['name'] ?>
        </p>
    </div>
</div>