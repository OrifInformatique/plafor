<div class="container">
    <div class="row">
        <div class="col">
            <?php foreach (config('\Common\Config\AdminPanelConfig')->views as $view){?>
            <a href="<?=base_url($view['pageLink'])?>" class="btn btn-primary <?= (isset($view['active'])?'active':'')?> adminnav" style="min-width: 90px"><?=lang($view['label'])?></a>

            <?php } ?>
        </div>
    </div>
</div>
<script defer>
    document.querySelectorAll('.adminnav').forEach((nav)=>{
        if (window.location.toString().includes(nav.href)){
            nav.classList.add('active')
        }
        else{
            nav.classList.remove('active')
        }
    })
</script>