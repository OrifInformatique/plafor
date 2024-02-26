<?php
/**
 * Footer view
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

?>

<!-- Script to update favicon in some browsers -->
<script defer type="text/javascript">
    let icolink=document.querySelector("link:first-of-type");
    document.getElementsByTagName("head")[0].append(icolink);
</script>
<script type="text/babel" src="<?= base_url('jsComponents/progressbar.js') ?>" defer></script>
<script type="text/babel" src="<?= base_url('jsComponents/progressStats.js') ?>" defer></script>
<script type="text/babel" src="<?= base_url('jsComponents/progressView.js') ?>" defer></script>
<script type="text/babel" src="<?= base_url('commonUtils.js') ?>"></script>
<script src="https://unpkg.com/react@16/umd/react.production.min.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js" crossorigin></script>
<!-- for using JSX syntax -->
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
</body>
</html>
