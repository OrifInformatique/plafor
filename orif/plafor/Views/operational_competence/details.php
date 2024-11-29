<?php

/**
 * Shows the details of an operational competence.
 *
 * Called by \Views/operational_comptence/view
 *           \Views/objective/view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $operational_competence Operational competence.
 *
 */



/**
 * No data is sent by this view.
 *
 */

?>

<div class="row mb-3">
    <div class="col-md-12">
        <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_operational_competence') ?></p>
    </div>

    <div class="col-md-12">
        <p><strong><?= $operational_competence['symbol'] ?></strong> : <?= $operational_competence['name'] ?></p>
    </div>

    <div class="col-md-4 text-center">
        <p><strong><?= lang('plafor_lang.field_operational_competence_methodologic') ?></strong></p>
        <?= $operational_competence['methodologic'] ?>
    </div>

    <div class="col-md-4 text-center border-left border-right border-secondary">
        <p><strong><?= lang('plafor_lang.field_operational_competence_social') ?></strong></p>
        <?= $operational_competence['social'] ?>
    </div>

    <div class="col-md-4 text-center">
        <p><strong><?= lang('plafor_lang.field_operational_competence_personal') ?></strong></p>
        <?= $operational_competence['personal'] ?>
    </div>
</div>