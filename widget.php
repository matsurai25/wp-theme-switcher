<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . "style.css"; ?>">
<div class="mt-switcher-widget">
    <?php
    $option = get_option(ThemeSwitcher::EVENT_HOOK);
    if ($option) { ?>
        <section>
            <?php
            $data = json_decode($option, true);
            $theme = wp_get_theme($data["template"]);
            ?>
            <div class="scheduled-item">
                <div class="theme-item" for="<?php echo $theme->template ?>">
                    <img class="item-screenshot" src="<?php echo $theme->get_screenshot(); ?>" alt="">
                    <div class="item-content">
                        <div>
                            <h5>切り替え日時</h5>
                            <div class="scheduled-time">
                                <?php echo wp_date("Y年m月d日 H時i分", $data["unixtime"]) ?></h4>
                            </div>
                            (残り: <?php echo floor(($data["unixtime"] - wp_date("U")) / 360) * 0.1; ?>時間) <br />
                        </div>
                        <div class="item-detail">
                            <h5>切り替え先</h5>
                            template: <?php echo $theme->template ?><br />
                            version: <?php echo $theme->version ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="actions">
                <a href="<?php admin_url(); ?>/wp-admin/customize.php?theme=<?php echo $theme->template ?>" class="button button-primary">変更先をプレビューする</a>
                <a href="<?php admin_url(); ?>/wp-admin/themes.php?page=mt-theme-switcher" class="button button-secondary">時限切り替えを変更する</a>
            </div>
        </section>
    <?php } else { ?>
        <p>
            現在予定されている時限切り替えはありません
        </p>
        <a href="<?php admin_url(); ?>/wp-admin/themes.php?page=mt-theme-switcher" class="button button-primary">時限切り替えを設定する</a>
    <?php } ?>
</div>