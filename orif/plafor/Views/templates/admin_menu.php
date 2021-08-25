<div class="container">
    <div class="row">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a href="<?= base_url('plafor/apprentice/list_apprentice/') ?>" class="nav-link"><?= lang('user_lang.admin_apprentices'); ?></a>
                </li>
            </ul>
    </div>

    <script async defer>
        document.querySelectorAll(".nav-link").forEach((item)=>{
            if (window.location.toString().startsWith(item.href)) {
                item.classList.add('active');
            }
        });
    </script>