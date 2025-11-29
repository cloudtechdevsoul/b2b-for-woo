<?php
if (!defined('WPINC')) { die; }

/**
 * Plugin Name: Wholesale for woocommerce - B2B & B2C- B2b King - B2B For Woocommerce
 * Description: Get your whole store ready with B2B functionalities!
 * Plugin URI: https://b2bextension.store/b2b-for-woo/
 * Version: 1.0.0
 * Author: b2bextension
 * Developed By: b2bextension
 * Author URI: https://b2bextension.store/
 * Support: https://b2bextension.store/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * Text Domain: b2b-for-woo
 * WC requires at least: 3.0.9
 * WC tested up to: 9.*.*
 * Requires Plugins: woocommerce
 * @package b2bextension
 */

if (!class_exists('b2b_extension_for_woocommerce')) {

class b2b_extension_for_woocommerce {

    /** @var array realpath => true map of included files */
    protected static $included = [];

    /** @var array root subfolders to skip for auto-loading shared code */
    protected static $exclude_top = [
        'includes','assets','languages','vendor','node_modules','tests','.git','.github'
    ];

    /** @var string plugin root path with trailing slash */
    protected $root = '';

    public function __construct() {

        $this->afreg_global_constents_vars();
        $this->root = rtrim(defined('CT_RBPAQP_PLUGIN_DIR') ? CT_RBPAQP_PLUGIN_DIR : plugin_dir_path(__FILE__), '/\\') . DIRECTORY_SEPARATOR;

        // Start WC session for guests (frontend only)
        add_action('woocommerce_init', function () {
            if (is_user_logged_in() || is_admin()) return;
            if (function_exists('WC') && WC()->session) {
                if (!WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
            }
        });

        // Admin menu
        add_action('admin_menu', [$this, 'b2bking_add_submenu']);

        add_action('after_setup_theme', [$this, 'afreg_init']);
        add_action('init',                [$this, 'afreg_custom_post_type']);

        // HOPS compatibility
        add_action('before_woocommerce_init', [$this, 'afcf__HOPS_Compatibility']);

        // Assets
        add_action('wp_enqueue_scripts',    [$this, 'ct_rbpaqp_enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'ct_rbpaqp_enqueue_scripts']);

        // ------------------------------
        // AUTO-LOAD: include PHP files automatically
        // ------------------------------

        // A) Shared includes (exclude admin/front/assets/languages) + module shared code
        add_action('plugins_loaded', function () {
            self::include_dir($this->root . 'includes', ['admin','front','assets','languages']);

            // Top-level module folders: include all PHP recursively except admin/front
            foreach (glob($this->root . '*', GLOB_ONLYDIR) as $dir) {
                $name = basename($dir);
                if (in_array($name, self::$exclude_top, true)) continue;
                self::include_dir($dir, ['admin','front','assets','languages','tests']);
            }
        }, 1);

        // B) Admin-only code (loads when admin APIs are ready)
        add_action('admin_init', function () {
            self::include_dir($this->root . 'includes/admin');
            foreach (glob($this->root . '*', GLOB_ONLYDIR) as $dir) {
                if (in_array(basename($dir), self::$exclude_top, true)) continue;
                if (is_dir($dir . '/admin')) self::include_dir($dir . '/admin');
            }
        }, 1);

        // C) Frontend-only code
        add_action('init', function () {
            if (is_admin()) return;
            self::include_dir($this->root . 'includes/front');
            foreach (glob($this->root . '*', GLOB_ONLYDIR) as $dir) {
                if (in_array(basename($dir), self::$exclude_top, true)) continue;
                if (is_dir($dir . '/front')) self::include_dir($dir . '/front');
            }
        }, 1);

        // WooCommerce order action to generate invoice PDF (if function exists)
        add_filter('woocommerce_order_actions', [$this, 'register_invoice_pdf_order_action']);
        add_action('woocommerce_order_action_b2b_generate_invoice_pdf', [$this, 'handle_invoice_pdf_order_action']);
    }

    /**
     * Recursively include .php files in $dir excluding by directory name.
     */
    protected static function include_dir($dir, $exclude_dirs = []) {
        if (empty($dir) || !is_dir($dir)) return;

        $exclude_dirs = array_map(function ($d) { return trim($d, '/\\'); }, (array)$exclude_dirs);

        $it = new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator(
                new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
                function ($file, $key, $iterator) use ($exclude_dirs) {
                    if ($iterator->hasChildren()) {
                        return !in_array(basename($file->getPathname()), $exclude_dirs, true);
                    }
                    return true;
                }
            ),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $files = [];
        foreach ($it as $f) {
            if ($f->isFile() && strtolower($f->getExtension()) === 'php') {
                $files[] = $f->getPathname();
            }
        }

        natcasesort($files);

        foreach ($files as $php) {
            $real = realpath($php);
            if (!$real) continue;
            if ($real === realpath(__FILE__)) continue; // don't include self
            if (empty(self::$included[$real])) {
                self::$included[$real] = true;
                require_once $real;
            }
        }
    }

    // === Invoice PDF order action (safe with function_exists) ===

    public function register_invoice_pdf_order_action($actions) {
        if (function_exists('generate_invoice_pdf_using_order_id')) {
            $actions['b2b_generate_invoice_pdf'] = __('Generate Invoice PDF (B2B)', 'b2b-for-woo');
        }
        return $actions;
    }

    public function handle_invoice_pdf_order_action($order) {
        if (is_numeric($order) && function_exists('wc_get_order')) {
            $order = wc_get_order((int)$order);
        }
        if (!$order || (is_object($order) && !is_a($order, 'WC_Order'))) return;

        $order_id = is_object($order) && method_exists($order, 'get_id') ? (int)$order->get_id() : (int)$order;

        if (function_exists('generate_invoice_pdf_using_order_id')) {
            try {
                generate_invoice_pdf_using_order_id($order_id);
                if (is_object($order) && method_exists($order, 'add_order_note')) {
                    $order->add_order_note(__('Invoice PDF generated via B2B order action.', 'b2b-for-woo'));
                }
            } catch (Throwable $e) {
                if (is_object($order) && method_exists($order, 'add_order_note')) {
                    $order->add_order_note(sprintf(__('Invoice PDF generation failed: %s', 'b2b-for-woo'), $e->getMessage()));
                }
            }
        } else {
            if (is_object($order) && method_exists($order, 'add_order_note')) {
                $order->add_order_note(__('Invoice PDF function not found: generate_invoice_pdf_using_order_id', 'b2b-for-woo'));
            }
        }
    }

    public function b2bking_menu_callback() { /* admin page render */ }

    public function afcf__HOPS_Compatibility() {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    }

    public function afreg_global_constents_vars() {

        if (!defined('CT_RBPAQP_URL')) {
            if (function_exists('plugin_dir_url')) {
                define('CT_RBPAQP_URL', plugin_dir_url(__FILE__));
            } else {
                define('CT_RBPAQP_URL', '');
            }
        }

        if (!defined('CT_RBPAQP_BASENAME')) {
            if (function_exists('plugin_basename')) {
                define('CT_RBPAQP_BASENAME', plugin_basename(__FILE__));
            } else {
                define('CT_RBPAQP_BASENAME', basename(__FILE__));
            }
        }

        if (!defined('CT_RBPAQP_PLUGIN_DIR')) {
            if (function_exists('plugin_dir_path')) {
                define('CT_RBPAQP_PLUGIN_DIR', plugin_dir_path(__FILE__));
            } else {
                define('CT_RBPAQP_PLUGIN_DIR', __DIR__ . '/');
            }
        }

        if (!defined('CT_RBPAQP_UPLOAD_DIR')) {
            $upload_dir = function_exists('wp_upload_dir') ? wp_upload_dir() : ['basedir' => WP_CONTENT_DIR . '/uploads'];
            $basedir    = isset($upload_dir['basedir']) ? $upload_dir['basedir'] : (WP_CONTENT_DIR . '/uploads');
            define('CT_RBPAQP_UPLOAD_DIR', $basedir . '/addify-custom-fields/');
            if (!is_dir(CT_RBPAQP_UPLOAD_DIR)) { @mkdir(CT_RBPAQP_UPLOAD_DIR); }
        }

        if (!defined('AF_CF_UPLOAD_URL')) {
            $upload_dir = function_exists('wp_upload_dir') ? wp_upload_dir() : ['baseurl' => content_url('uploads')];
            $baseurl    = isset($upload_dir['baseurl']) ? $upload_dir['baseurl'] : content_url('uploads');
            define('AF_CF_UPLOAD_URL', $baseurl . '/addify-custom-fields/');
        }
    } // end afreg_global_constents_vars()

    public function afreg_init() {
        if (function_exists('load_plugin_textdomain') && function_exists('plugin_basename')) {
            load_plugin_textdomain('cloud_tech_rbpaqpfw', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }
    } // end afreg_init()

    public function b2bking_add_submenu() {
        if (function_exists('add_menu_page')) {
            add_menu_page(
                'B2b King',
                'B2b King',
                'manage_options',
                'b2bking',
                [$this, 'b2bking_menu_callback'],
                'dashicons-admin-generic'
            );
        }
    }

    public function afreg_custom_post_type() {
        if (!function_exists('register_post_type')) return;

        $labels = array(
            'name'                  => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'singular_name'         => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'add_new'               => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
            'add_new_item'          => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
            'edit_item'             => esc_html__('Edit Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'new_item'              => esc_html__('New Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'view_item'             => esc_html__('View Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'search_items'          => esc_html__('Search Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'exclude_from_search'   => true,
            'not_found'             => esc_html__('No Role Base Pricing found', 'cloud_tech_rbpaqpfw'),
            'not_found_in_trash'    => esc_html__('No Role Base Pricing found in trash', 'cloud_tech_rbpaqpfw'),
            'parent_item_colon'     => '',
            'all_items'             => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
            'menu_name'             => esc_html__('Role Base Pricing', 'cloud_tech_rbpaqpfw'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'b2bking',
            'query_var'          => true,
            'capability_type'    => 'post',
            'menu_position'      => 4,
            'has_archive'        => true,
            'hierarchical'       => false,
            'rewrite'            => array('slug' => 'ct_role_base_pricing', 'with_front' => false),
            'supports'           => array('title', 'page-attributes')
        );

        register_post_type('ct_role_base_pricing', $args);

        // Customer base pricing hidden post.
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'rewrite'            => array('slug' => 'ct_set_role_for_cbp', 'with_front' => false),
            'supports'           => array('title', 'page-attributes')
        );

        register_post_type('ct_set_role_for_cbp', $args);

        // Role base pricing hidden post.
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'rewrite'            => array('slug' => 'ct_set_role_for_rbp', 'with_front' => false),
            'supports'           => array('title', 'page-attributes')
        );

        register_post_type('ct_set_role_for_rbp', $args);

        // ----------------------------------------------

        $def_labels = array(
            'name'                  => esc_html__('Default Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'singular_name'         => esc_html__('Default Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'edit_item'             => esc_html__('Edit Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'new_item'              => esc_html__('New Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'view_item'             => esc_html__('View Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'search_items'          => esc_html__('Search Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'exclude_from_search'   => true,
            'not_found'             => esc_html__('No Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'not_found_in_trash'    => esc_html__('No Role Base Min Max Quantity in trash', 'cloud_tech_rbpaqpfw'),
            'parent_item_colon'     => '',
            'all_items'             => esc_html__('Min Max Quantity', 'cloud_tech_rbpaqpfw'),
            'menu_name'             => esc_html__('Default Role Base Min Max Quantity', 'cloud_tech_rbpaqpfw'),
        );

        $args = array(
            'labels'             => $def_labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'b2bking',
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'rewrite'            => array('slug' => 'ct_min_max_qty_role', 'with_front' => false),
            'supports'           => array('title')
        );

        register_post_type('ct_min_max_qty_role', $args);

        // Hide Price And Add To Cart Button.
        $labels = array(
            'name'                  => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'singular_name'         => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'add_new'               => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
            'add_new_item'          => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
            'edit_item'             => esc_html__('Edit Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'new_item'              => esc_html__('New Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'view_item'             => esc_html__('View Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'search_items'          => esc_html__('Search Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'exclude_from_search'   => true,
            'not_found'             => esc_html__('No Hide Price And To Cart Button found', 'cloud_tech_rbpaqpfw'),
            'not_found_in_trash'    => esc_html__('No Hide Price And To Cart Button found in trash', 'cloud_tech_rbpaqpfw'),
            'parent_item_colon'     => '',
            'all_items'             => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
            'menu_name'             => esc_html__('Hide Price And To Cart Button', 'cloud_tech_rbpaqpfw'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'b2bking',
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'rewrite'            => array('slug' => 'ct_hide_p_nd_a_t_c_b', 'with_front' => false),
            'supports'           => array('title', 'page-attributes')
        );

        register_post_type('ct_hide_p_nd_a_t_c_b', $args);

        // Hide products and variation.
        $labels = array(
            'name'                  => esc_html__('Hide products and variation', 'cloud_tech_rbpaqpfw'),
            'singular_name'         => esc_html__('Hide products and variation', 'cloud_tech_rbpaqpfw'),
            'add_new'               => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
            'add_new_item'          => esc_html__('Add New Field', 'cloud_tech_rbpaqpfw'),
            'edit_item'             => esc_html__('Edit Hide products and variation', 'cloud_tech_rbpaqpfw'),
            'new_item'              => esc_html__('New Hide products and variation', 'cloud_tech_rbpaqpfw'),
            'view_item'             => esc_html__('View Hide products and variation', 'cloud_tech_rbpaqpfw'),
            'search_items'          => esc_html__('Search Hide products and variation', 'cloud_tech_rbpaqpfw'),
            'exclude_from_search'   => true,
            'not_found'             => esc_html__('No Hide products and variation found', 'cloud_tech_rbpaqpfw'),
            'not_found_in_trash'    => esc_html__('No Hide products and variation found in trash', 'cloud_tech_rbpaqpfw'),
            'parent_item_colon'     => '',
            'all_items'             => esc_html__('Hide Products and Variation', 'cloud_tech_rbpaqpfw'),
            'menu_name'             => esc_html__('Hide products and variation', 'cloud_tech_rbpaqpfw'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'b2bking',
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'rewrite'            => array('slug' => 'ct_hide_prdct_nd_var', 'with_front' => false),
            'supports'           => array('title', 'page-attributes')
        );
        register_post_type('ct_hide_prdct_nd_var', $args);

        // Hide payment and shipping method.
        $labels = array(
            'name'              => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
            'singular_name'     => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
            'edit_item'         => esc_html__('Edit Hide Shipping and Payment Method ', 'hide_payment_method'),
            'new_item'          => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
            'view_item'         => esc_html__('View Hide Shipping and Payment Method Cart', 'hide_payment_method'),
            'search_items'      => esc_html__('Search Hide Shipping and Payment Method', 'hide_payment_method'),
            'not_found'         => esc_html__('No Hide Shipping and Payment Method found', 'hide_payment_method'),
            'not_found_in_trash'=> esc_html__('No bestprice found in trash', 'hide_payment_method'),
            'menu_name'         => esc_html__('Hide Shipping and Payment Method', 'hide_payment_method'),
            'item_published'    => esc_html__('Hide Shipping and Payment Method published', 'hide_payment_method'),
            'item_updated'      => esc_html__('Hide Shipping and Payment Method updated', 'hide_payment_method'),
        );
        $supports = array('title');
        $options = array(
            'supports'           => $supports,
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => false,
            'query_var'          => true,
            'capability_type'    => 'post',
            'can_export'         => true,
            'show_ui'            => true,
            'show_in_admin_bar'  => true,
            'exclude_from_search'=> true,
            'show_in_menu'       => 'b2bking',
            'has_archive'        => true,
            'rewrite'            => array(
                'slug'       => 'mao_makeoffer',
                'with_front' => false,
            ),
            'show_in_rest'       => true,
        );
        register_post_type('city_hide', $options);

    } // end afreg_custom_post_type()

    public function ct_rbpaqp_enqueue_scripts() {
        if (is_admin()) {
            wp_enqueue_script('ct-rbpaqp', CT_RBPAQP_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', false);
            wp_enqueue_style('admin-css', CT_RBPAQP_URL . 'assets/css/admin.css', array(), '1.0.0');
        } else {
            wp_enqueue_script('ct-rbpaqp', CT_RBPAQP_URL . 'assets/js/front.js', array('jquery'), '1.0.0', false);
            wp_enqueue_style('front-css', CT_RBPAQP_URL . 'assets/css/front.css', array(), '1.0.0');
        }

        // Font Awesome CDN
        wp_enqueue_style('font-awesome-lib', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', [], '4.7.0', 'all');

        // jQuery UI
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-sortable');

        // Woo Select2 – prefer registered handles, fallback to WC files if available
        if (function_exists('wp_style_is') && wp_style_is('select2', 'registered')) {
            wp_enqueue_style('select2');
        } elseif (defined('WC_PLUGIN_FILE')) {
            wp_enqueue_style('wc-select2-css', plugins_url('assets/css/select2.css', WC_PLUGIN_FILE), [], '5.7.2');
        }

        if (function_exists('wp_script_is') && wp_script_is('select2', 'registered')) {
            wp_enqueue_script('select2');
            if (wp_script_is('wc-enhanced-select', 'registered')) {
                wp_enqueue_script('wc-enhanced-select');
            }
        } elseif (defined('WC_PLUGIN_FILE')) {
            wp_enqueue_script('wc-select2-js', plugins_url('assets/js/select2/select2.min.js', WC_PLUGIN_FILE), ['jquery'], '4.0.3', false);
        }

        $af_c_f_data = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => function_exists('wp_create_nonce') ? wp_create_nonce('cloud-tech-rbpaqpfw-nonce') : '',
        );
        wp_localize_script('ct-rbpaqp', 'ct_rbpaqp_var', $af_c_f_data);
    }

} // end class

new b2b_extension_for_woocommerce();

}
