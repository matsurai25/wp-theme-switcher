<?php

/*
Plugin Name: MT Theme Switcher
Description: 指定の時間に指定のテーマに切り替える / A WordPress plugin that changes the theme from the current one to a specified one at a designated time.
Plugin URL: https://github.com/matsurai25/wp-theme-switcher
Version: 1.0
Update URI: mt-theme-switcher
Author: matsurai25
Author URL: https://twitter.com/matsurai25
*/


// 直接アクセスが来た場合にファイルを実行しない
if (!defined('ABSPATH')) die();

if (!class_exists('ThemeSwitcher')) {
    class ThemeSwitcher
    {
        const EVENT_HOOK = "mt-theme-switcher-scheduled-task";
        private static $instance;

        public static function instance()
        {

            if (!isset(self::$instance) && !(self::$instance instanceof ThemeSwitcher)) {
                self::$instance = new ThemeSwitcher;
            }

            // if (!wp_next_scheduled("mt-theme-switcher-scheduled-task")) {
            //     wp_schedule_single_event(strtotime("2023-06-30 00:00:00 JST"), 'mt-theme-switcher-scheduled-task', ["okashinatensei-pr"]);
            // }

            add_action('plugins_loaded', [self::$instance, 'bindHooks']);

            return self::$instance;
        }

        // フックに関数を紐づけ
        public static function bindHooks()
        {
            // wp_schedule_single_eventで飛ばされてきたhookにメイン処理を紐づける
            add_action('mt-theme-switcher-scheduled-task', [self::$instance, 'runner']);

            // 管理画面にメニューを表示
            add_action('admin_menu', [self::$instance, 'addMenu']);
        }

        // 処理を実行
        public static function runner($args)
        {
            switch_theme($args);
            delete_option(self::EVENT_HOOK);
        }

        // メニューを表示
        public static function addMenu()
        {
            add_submenu_page(
                'tools.php',
                'MT Theme Switcher',
                'MT Theme Switcher',
                'manage_options',
                'mt-theme-switcher',
                [self::$instance, 'displayPage'],
                'dashicons-admin-users',
                10
            );
        }

        public static function displayPage()
        {
            if (isset($_POST["mode"]) && $_POST["mode"] === "create") {
                self::createEvent();
            } else if (isset($_POST["mode"]) && $_POST["mode"] === "delete") {
                self::deleteEvent();
            } else {
                include plugin_dir_path(__FILE__) . "page.php";
            }
        }

        public static function createEvent()
        {
            // バリデーション
            if (!$_POST["template"]) {
                throw new Exception("paramater 'template' is not found");
            }
            $template = $_POST["template"];
            if (!$_POST["time"]) {
                throw new Exception("paramater 'time' is not found");
            }
            $unixtime = strtotime($_POST["time"] . " " . wp_timezone_string());

            $option = get_option(self::EVENT_HOOK);
            if ($option) {
                throw new Exception("event was already created");
            }

            wp_schedule_single_event($unixtime, self::EVENT_HOOK, [$template]);
            update_option(self::EVENT_HOOK, json_encode([
                "unixtime" => $unixtime,
                "template" => $template
            ]));
            include plugin_dir_path(__FILE__) . "page-created.php";
        }

        public static function deleteEvent()
        {
            $data = get_option(self::EVENT_HOOK);
            if (!$data) {
                throw new Exception("event was not set");
            }
            $data = json_decode($data, true);
            wp_unschedule_event($data["unixtime"], self::EVENT_HOOK, [$data["template"]]);
            delete_option(self::EVENT_HOOK);
            include plugin_dir_path(__FILE__) . "page-deleted.php";
        }
    }
}

if (class_exists('ThemeSwitcher')) {
    if (!function_exists('exec_theme_switcher')) {
        function exec_theme_switcher()
        {
            return ThemeSwitcher::instance();
        }
    }
    exec_theme_switcher();
}
