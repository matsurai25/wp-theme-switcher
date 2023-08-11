<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . "style.css"; ?>">

<div class="wrap mt-switcher-page">

    <form action="" method="post">
        <h1 class="wp-heading-inline">時限切り替え設定</h1>
        <?php
        $option = get_option(ThemeSwitcher::EVENT_HOOK);
        if ($option) { ?>
            <section>
                <?php
                $data = json_decode($option, true);
                $theme = wp_get_theme($data["template"]);
                ?>
                <h2>以下の切り替えが設定されています</h2>
                <div class="scheduled-item">
                    <div class="theme-item" for="<?php echo $theme->template ?>">
                        <img class="item-screenshot" src="<?php echo $theme->get_screenshot(); ?>" alt="">
                        <div class="item-name">
                            <span><?php echo $theme->name ?><br /></span>
                            <?php echo $theme->template ?>
                        </div>
                    </div>
                </div>
                <div class="note-wrapper">
                    <div class="note">
                        Switching date and time: <?php echo wp_date("Y-m-d H:i:s T", $data["unixtime"]) ?> (残り: <?php echo ThemeSwitcher::computeRemainingTime($data["unixtime"] - wp_date("U")); ?>) <br />
                        Switching destination: <?php echo $data["template"] ?><br />
                    </div>
                </div>

                <input type="hidden" name="mode" value="delete">
                <button class="button button-secondary button-large">このイベントを削除する</button>
            </section>
        <?php } else { ?>
            <section>
                <h2>切り替え先テーマ</h2>
                <div class="items">
                    <?php
                    $current_theme = wp_get_theme();
                    $themes = wp_get_themes();
                    foreach ($themes as $theme) {
                        $tmp[$theme->name] = $theme;
                    }
                    krsort($tmp);
                    $i = 0;
                    foreach ($tmp as $theme) {
                        if ($current_theme->template === $theme->template) {
                            continue;
                        }
                    ?>
                        <input class="hidden" type="radio" name="template" id="<?php echo $theme->template ?>" value="<?php echo $theme->template ?>" <?php echo $i === 0 ? "checked" : "" ?>>
                        <label class="theme-item" for="<?php echo $theme->template ?>">
                            <img class="item-screenshot" src="<?php echo $theme->get_screenshot(); ?>" alt="">
                            <div class="item-name">
                                <span><?php echo $theme->name ?><br /></span>
                                <?php echo $theme->template ?>
                            </div>
                        </label>
                    <?php
                        $i++;
                    } ?>
                </div>
                <div class="note-wrapper">
                    <div class="note">
                        Current Theme Name: <?php echo $current_theme->name; ?>
                        / Current Theme Template: <?php echo $current_theme->template; ?>
                    </div>
                </div>

            </section>
            <section>
                <h2>切り替え時刻</h2>
                <input class="datetime" type="datetime-local" name="time" value="<?php echo wp_date("Y-m-d H:i", strtotime("10 minutes")); ?>" min="<?php echo wp_date("Y-m-d H:i", strtotime("10 minutes")); ?>" />
                <div class="note-wrapper">
                    <div class="note">
                        Server Timezone: <?php echo wp_timezone_string(); ?> /
                        Current Server Time: <?php echo wp_date("Y-m-d H:i:s T"); ?>
                    </div>
                </div>
            </section>
            <section>
                <input type="hidden" name="mode" value="create">
                <button class="button button-primary button-large">切り替えを設定する</button>
            </section>
        <?php } ?>
    </form>
</div>

<?php /*

<div class="wrap">
    <h1 class="wp-heading-inline">MT Theme Switcher</h1>
    <h2>これはなに？ / What is this?</h2>
    <p>
        時間と切り替え先テーマを指定することで、ダウンタイムなしのテーマ切り替えを行います。<br />
        By specifying the time and the theme to switch to, we will perform a seamless theme switch without any downtime.
        <br />
        contact: <a href=" https://twitter.com/matsurai25" target="_blank" rel="noopener noreferrer">https://twitter.com/matsurai25</a>
    </p>

    <h2>現在のテーマ / Current Theme</h2>
    <?php $current_theme = wp_get_theme(); ?>
    Template: <?php echo $current_theme->template; ?><br />
    Version: <?php echo $current_theme->version; ?><br />

    <?php
    $option = get_option(ThemeSwitcher::EVENT_HOOK);
    if ($option) { ?>
        <?php $data = json_decode($option, true); ?>
        <h2>以下のイベントが設定されています / The following events have been set.</h2>
        切り替え日時 / Switching date and time: <?php echo wp_date("Y-m-d H:i:s T", $data["unixtime"]) ?> (残り: <?php echo floor(($data["unixtime"] - wp_date("U")) / 360) * 0.1; ?>時間) <br />
        切り替え先 / Switching destination: <?php echo $data["template"] ?><br />
        <br />
        <form action="" method="post">
            <input type="hidden" name="mode" value="delete">
            <button class="button button-secondary button-large">このイベントを削除する</button>
        </form>

    <?php } else { ?>
        <form action="" method="post">
            <input type="hidden" name="mode" value="create">
            <h2>いつ / When</h2>
            Server Timezone: <?php echo wp_timezone_string(); ?><br />
            Server Time: <?php echo wp_date("Y-m-d H:i:s T"); ?><br />
            <br />
            <input type="datetime-local" name="time" id="">
            <h2>どれに / Which</h2>
            <select name="template" id="">
                <?php
                $themes = wp_get_themes();
                foreach ($themes as $theme) { ?>
                    <option value="<?php echo $theme->template ?>"><?php echo $theme->template == $current_theme->template ? "CURRENT: " :  "" ?><?php echo $theme->template ?> : Version: <?php echo $theme->version ?></option>
                <?php } ?>

            </select>
            <br />
            <br />
            <button class="button button-primary button-large">切り替えを設定する / Set up a switch.</button>
        </form>
    <?php } ?>
</div>

*/ ?>