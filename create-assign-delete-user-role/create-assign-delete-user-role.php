<?php
if (!defined('WPINC')) {
    die;
}


class Create_Assign_Delete_USer_Role
{

    public function __construct()
    {

        $this->global_constents_vars();

        include CT_CAADU_PLUGIN_DIR . 'includes/ajax-controller.php';
        include CT_CAADU_PLUGIN_DIR . 'includes/general-functions.php';

        if (is_admin()) {
            include CT_CAADU_PLUGIN_DIR . 'includes/admin.php';
        }

    } //end __construct()


    public function global_constents_vars()
    {

        if (!defined('CT_CAADU_URL')) {
            define('CT_CAADU_URL', plugin_dir_url(__FILE__));
        }

        if (!defined('CT_CAADU_BASENAME')) {
            define('CT_CAADU_BASENAME', plugin_basename(__FILE__));
        }

        if (!defined('CT_CAADU_PLUGIN_DIR')) {
            define('CT_CAADU_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }
    }
    public function afcmfw_init()
    {

        if (function_exists('load_plugin_textdomain')) {

            load_plugin_textdomain('cloud_tech_caadu', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }
    }
}

new Create_Assign_Delete_USer_Role();