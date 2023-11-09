<?php
/**
 * Fichier de vue pour la navigation
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
/**
 * Params $reset
 * Call reset on navigation root
 */
?>
<?php
if (isset($reset)){
    service('session')->remove('navigator');
}
else{
$navigatorLink=service('session')->get('navigator');
if (isset($navigatorLink)){
    $index=null;
    for ($i=count($navigatorLink)-1;$i>=0;$i--){
        if ($navigatorLink[$i]['link']==((current_url().(strlen($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:'')))){
            $index=$i;
        }
    }
    if($index===null){
        $navigatorLink[]=['link'=>current_url().(strlen($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:''),'title'=>$title];
        service('session')->set('navigator',$navigatorLink);

    }
    else{
        for($i=count($navigatorLink);$i>$index;$i--){
            array_pop($navigatorLink);
        }
        $navigatorLink[]=['link'=>current_url().(strlen($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:''),'title'=>$title];
        service('session')->set('navigator',$navigatorLink);
    }

}
else{

    service('session')->set('navigator',[['link'=>service('session')->get('_ci_previous_url'),'title'=>''],['link'=>current_url().(strlen($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:''),'title'=>$title]]);


}
?>
<span id="navigator">
    <?php 
    $navigatorLink=service('session')->get('navigator');
    if(count($navigatorLink)>2):?>
        <a id="navigator-back" class="btn btn-outline-primary mr-2 bi bi-arrow-left-circle" href="<?=$navigatorLink[count($navigatorLink)-2]['link']?>">
            <?= lang("common_lang.btn_back") ?>
        </a>
        <?php for($i=0;$i<count($navigatorLink);$i++) {?>
            <a href="<?=$navigatorLink[$i]['link']?>"><?=$navigatorLink[$i]['title']?></a>
            <?php echo count($navigatorLink)>1 && $i!=count($navigatorLink)-1 ? '<span class="text-primary"><i class="bi bi-arrow-right-short" ></i></span>' : ''?>
        <?php } ?>
    <?php else: ?>
        <a id="navigator-back" class="btn btn-outline-primary mr-2 bi bi-arrow-left-circle" href="<?= $navigatorLink[0]['link'] ?>">
            <?= lang("common_lang.btn_back") ?>
        </a>
    <?php endif;?>
</span>
<?php } ?>