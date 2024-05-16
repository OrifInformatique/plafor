<?php
/**
 * Fichier de vue pour effectuer la migration
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<style>
    .header{
        color: white;
        text-align: center;
        padding-block: 5px;
    }
    .section-title{
        padding-left: 20px;
    }
    #spinnerContainer{
        position: absolute;
        top: 50%;
        left: 50%;
        display: flex;
        background-color: #ae9b70;
        width: 100px;
        height: 100px;
        border-radius: 10px;
        justify-content: center;
        align-items: center;
        box-shadow: 5.1px 10.2px 10.2px hsl(0deg 0% 0% / 0.34);
        box-sizing: border-box;
        border: #897754 4px solid;
    }
    .background{
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 80%;
        height: 80%;
        border-radius: 100%;
        box-sizing: border-box;
        padding-block-start: 10px;
        padding: 15% 10%;
        border: 8px #bfa78a solid;
        animation: rotate 0.9s linear infinite;

    }
    .item{
        position: relative;
        align-self: start;
        display: block;
        border-radius: 100%;
        width: 10px;
        height: 10px;
        bottom: 63%;

    }
    .item:nth-child(1){
        background-color: #958259;

    }

    .item:nth-child(2){
        background-color: white;
        position: relative;
        bottom: 73%;
    }
    .item:nth-child(3){
        background-color: #958259;

    }

    @keyframes rotate {
    0%{
        transform:rotate(0deg);
    }
    100%{
        transform:rotate(360deg);

    }

}



</style>
<header class="header bg-primary"><h1 class="card-title"><?= lang('common_lang.initialization') ?>  <?=lang('common_lang.app_title')?></h1></header>
<body>
<span style="display: flex;flex-direction: column"><h3 class="alert-primary section-title"><?= lang('common_lang.initialization') ?> <?=lang('common_lang.app_title')?></h3>
    <div id="message" class="alert-danger" style="max-width: 600px;border-radius: 10px;padding-left: 20px"></div>
    <p style="padding-left: 20px"><?php echo lang('common_lang.for').' '.lang('common_lang.initialize') ?> <?=lang('common_lang.app_title')?> <?=lang('common_lang.click_on_the_button')?> <?=lang('common_lang.above')?></p>
    <label for="migpassword" class="section-title"><?=lang('user_lang.field_password')?></label>
    <input type="password" id="migpassword" class="form-control" style="max-width: 300px;margin-left: 15px;margin-bottom: 5px" required>
    <button class="btn btn-primary" style="max-width: 150px;margin-left: 160px" onclick="executeMigration()"><?= lang('common_lang.initialization') ?></button>
</span>




</div>
</body>
<link rel="stylesheet" href="<?= base_url("css/bootstrap.min.css"); ?>" />
<script type="text/javascript">
    function displaySpinner(){
        let spinner=document.createElement('div');
        spinner.id='spinnerContainer';
        let background=document.createElement('div');
        background.className='background';
        for (let i=0;i<3;i++){
            let element=document.createElement('div');
            element.className='item';
            background.appendChild(element);
        }
        spinner.appendChild(background);
        document.body.appendChild(spinner);
    }

        function removeSpinner(){
            document.getElementById('spinnerContainer')!==null?document.getElementById('spinnerContainer').remove():null;
        }

        function executeMigration(){
        let formdata=new FormData();
        formdata.append('password',document.getElementById('migpassword').value);
        displaySpinner();
        fetch("<?= base_url('migration/init')?>",{
            method:'POST',
            body:formdata,
        }).then((response)=>{
            if (response.ok){

                window.location.href='<?=base_url()?>';

            }
            else{
                removeSpinner();
                let error=document.createElement('p');
                error.style.paddingLeft='20px';
                if (response.status===401){
                    error.innerText="le mot de passe n\'est pas valide";
                }
                else if (response.status===400){
                    error.innerText="Une erreur est survenue";

                }
                document.getElementById('message').innerHTML=error.innerHTML;
            }
        });
    }
</script>