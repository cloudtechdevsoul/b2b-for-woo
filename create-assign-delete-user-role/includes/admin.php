<?php
// Add submenu to WooCommerce
function ct_caadu_custom_woocommerce_submenu()
{
    add_submenu_page(
        'b2bking',
        __('Create/Assign/Delete User Role', 'cloud_tech_caadu'),
        __('Create/Assign/Delete User Role', 'cloud_tech_caadu'),
        'manage_options', // Change the capability according to your needs
        'custom-user-management',
        'ct_caadu_custom_user_management_callback'
    );
}
add_action('admin_menu', 'ct_caadu_custom_woocommerce_submenu');

// Callback function for the submenu page
function ct_caadu_custom_user_management_callback()
{
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'all-users';

    ?>
    <div class="wrap">
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=custom-user-management&tab=all-users"
                class="nav-tab <?php echo ($current_tab == 'all-users') ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html__('All Users', 'cloud_tech_caadu'); ?>
            </a>
            <a href="?page=custom-user-management&tab=create-delete-role"
                class="nav-tab <?php echo ($current_tab == 'create-delete-role') ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html__('Create/Delete User Role', 'cloud_tech_caadu'); ?>
            </a>
        </h2>

        <div class="tab-content">
            <?php
            // Display content based on the selected tab

            switch ($current_tab) {
                case 'all-users':
                    ct_caadu_get_all_users();
                    break;

                case 'create-delete-role':
                    display_user_roles_table();
                    break;

                default:
                    // Default tab content
                    echo __('Default Content', 'cloud_tech_caadu');
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}


function ct_caadu_get_all_users()
{
    global $wp_roles;

    ?>
    <div class="ct-all-user-container">
        <form action="post" class="ct-all-user-assign-form">

            <table class="wp-list-table widefat fixed striped">
                <tr>
                    <td>
                        <?php echo esc_html__('Assign', 'cloud_tech_caadu'); ?>
                    </td>
                    <td>
                        <select name="assign_user_role" id="">
                            <option value="switch_to"><?php echo esc_html__('Switch', 'cloud_tech_caadu'); ?></option>
                            <option value="secondary"><?php echo esc_html__('Secondard', 'cloud_tech_caadu'); ?></option>
                            <option value="primary"><?php echo esc_html__('Primary', 'cloud_tech_caadu'); ?></option>
                        </select>
                    </td>
                    <td>
                        <?php echo esc_html__('Select User Role', 'cloud_tech_caadu'); ?>
                    </td>
                    <td>
                        <select name="selected_new_user_role" id="">
                            <?php foreach ($wp_roles->get_names() as $key => $label) { ?>
                                <option value="<?php echo esc_attr($key); ?>">
                                    <?php echo esc_attr($label); ?>
                                </option>
                            <?php } ?>

                        </select>
                    </td>
                    <td>
                        <input type="submit" name="assign_new_user_role" value="Submit" class="button button-primary" />
                    </td>
                </tr>
            </table>
        </form>

        <table class="ct-all-user-list wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="ct_user_ids" class="ct_user_all_users_ids" />
                        <?php echo esc_html__('Profile Picture', 'cloud_tech_caadu'); ?>
                    </th>
                    <th>
                        <?php echo esc_html__('Name', 'cloud_tech_caadu'); ?>
                    </th>
                    <th>
                        <?php echo esc_html__('Logins', 'cloud_tech_caadu'); ?>
                    </th>
                    <th>
                        <?php echo esc_html__('User Role', 'cloud_tech_caadu'); ?>
                    </th>
                    <th>
                        <?php echo esc_html__('Country', 'cloud_tech_caadu'); ?>
                    </th>
                    <th>
                        <?php echo esc_html__('State', 'cloud_tech_caadu'); ?>
                    </th>
                    <th>
                        <?php echo esc_html__('Total Spend', 'cloud_tech_caadu'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = get_users();
                foreach ($users as $user) {

                    $billing_country = get_user_meta($user->ID, 'billing_country', true);
                    $billing_state = get_user_meta($user->ID, 'billing_state', true);
                    $user_roles = $user->roles;
                    $user_roles_labels = array_map('translate_user_role', $user_roles);
                    $user_roles_display = implode(', ', $user_roles_labels);


                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="ct_user_ids" class="ct_user_ids" value="<?php echo ($user->ID); ?>" />
                            <?php echo wp_kses_post(get_avatar($user->ID, 32)); ?>
                        </td>
                        <td>
                            <?php echo esc_attr($user->display_name); ?>
                        </td>
                        <td>
                            <?php echo esc_attr($user->user_login); ?>
                        </td>
                        <td>
                            <?php echo esc_attr($user_roles_display); ?>
                        </td>
                        <td>
                            <?php echo esc_attr(devsoul_caadu_get_country_full_name($billing_country)); ?>
                        </td>
                        <td>
                            <?php echo esc_attr(devsoul_caadu_get_state_full_name($billing_country, $billing_state)); ?>
                        </td>
                        <td>
                            <?php echo esc_attr(wc_format_decimal(wc_get_customer_total_spent($user->ID), 2)); ?>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
    <?php
}


add_action('admin_enqueue_scripts', 'ct_caadu_enqueue_scripts');

function ct_caadu_enqueue_scripts()
{
    wp_enqueue_style('admincss', CT_CAADU_URL . 'assets/css/admin.css', false, '1.0', false);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', false);

    wp_enqueue_style('dataTables-style', 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css', array(), '1.11.5');
    wp_enqueue_style('select2css', plugins_url('assets/css/select2.css', WC_PLUGIN_FILE), array(), '5.7.2');
    wp_enqueue_script('select2js', plugins_url('assets/js/select2/select2.min.js', WC_PLUGIN_FILE), array('jquery'), '4.0.3', true);
    wp_enqueue_script('admin-js', CT_CAADU_URL . 'assets/js/admin.js', array('jquery'), '1.0.2', false);
    wp_enqueue_script('datatable', CT_CAADU_URL . 'assets/js/data-table.js', array('jquery'), '1.0.1', false);
    wp_enqueue_script('dataTables', 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js', array('jquery'), '1.11.5', false);

    $aurgs = array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ct_caadu_nonce'),
    );

    wp_localize_script('admin-js', 'php_var', $aurgs);
}



function display_user_roles_table()
{
    global $wp_roles;
    

        $get_default_wp_roles = wp_roles();

        if (!isset($get_default_wp_roles)) {
            $get_default_wp_roles = new WP_Roles();
        }

        $all_capabilities = array();

        foreach ($get_default_wp_roles->role_objects as $role_slug => $current_role_object) {

            foreach ($current_role_object->capabilities as $capability => $value) {

                $all_capabilities[$capability] = true;

            }

        }

        ?>
    <div class="ct-caadu-create-new-user-role-container ct-caadu-create-user-role-capabilities" style="display:none;">
        <i class="fa fa-close ct-caadu-close-send-bulk-email-popup-main-container" style="top:-18px;right:-20px;"></i>
        <div>
            <form method="post" class="ct-caadu-create-new-user-role">
                <?php wp_nonce_field('addify_customer_manager', 'addify_customer_manager'); ?>
                <table class="wp-list-table widefat fixed striped table-view-list customers dataTable">
                    <tbody>
                        <tr>
                            <th>
                                <?php echo esc_html__('Select User Role Name', 'addify_customer_manager'); ?>
                            </th>
                            <td>
                                <input type="text" required name="user_role_name">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo esc_html__('Select User Role Key', 'addify_customer_manager'); ?>
                            </th>
                            <td>
                                <input type="text" required name="user_role_key">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo esc_html__('Capabilities Type', 'addify_customer_manager'); ?>
                            </th>
                            <td>
                                <select name="capabilitites_type" class="capabilitites_type">
                                    <option value="user_role">
                                        <?php echo esc_html__('Copy User Role Capabilities', 'addify_customer_manager'); ?>
                                    </option>
                                    <option value="select_custom_capabilities">
                                        <?php echo esc_html__('Add Custom Capabilities', 'addify_customer_manager'); ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr class="af-select-user-role-for-capabilities">
                            <th>
                                <?php echo esc_html__('Select User Role', 'addify_customer_manager'); ?>
                            </th>
                            <td>
                                <select style="width: 50%;" name="selected_user_role">
                                    <?php foreach ($get_default_wp_roles->get_names() as $key => $value) { ?>

                                            <option value="<?php echo esc_attr($key); ?>">
                                                <?php echo esc_attr($value); ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr class="af-select-custom-capabilities">
                            <th>
                                <?php echo esc_html__('Select Custom Capabilities', 'addify_customer_manager'); ?>
                            </th>
                            <td>
                                <select class="ct-caadu-live-search selected_capabilites" style="width: 50%;"
                                    name="selected_capabilites[]">
                                    <?php foreach ($all_capabilities as $key => $value) { ?>

                                            <option value="<?php echo esc_attr($key); ?>">
                                                <?php echo esc_attr(ucfirst(str_replace('_', ' ', $key))); ?>
                                            </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <input type="submit" name="submit" value="Create" class="af-cm-create-new-user-role button button-primary">
            </form>
        </div>
    </div>

    <div class="tablenav top">
        <div class="alignleft actions bulkactions">

            <input type="submit" class="ct-caadu-show-popup button button-primary"
                data-show_class="ct-caadu-create-new-user-role-container" value="Create User Role">
        </div>
    </div>
    <table class="wp-list-table widefat ct-all-user-list fixed striped table-view-list customers dataTable">
        <thead>
            <thead>
                <th>
                    <?php echo esc_html__('User Role', 'addify_customer_manager'); ?>
                </th>
                <th>
                    <?php echo esc_html__('Role Key', 'addify_customer_manager'); ?>
                </th>
                <th>
                    <?php echo esc_html__('Assign Customer ', 'addify_customer_manager'); ?>
                </th>
                <th>
                    <?php echo esc_html__('Customer List ', 'addify_customer_manager'); ?>
                    </th>
                    <th>
                    <?php echo esc_html__('Action', 'addify_customer_manager'); ?>
                </th>


            </thead>
        </thead>
        <tbody>
            <?php


            foreach ($get_default_wp_roles->roles as $current_user_role_key => $value) {

                    ?>
                    <tr class="">
                        <th>
                            <?php echo esc_attr($value['name']); ?>
                        </th>
                        <td>
                            <?php echo esc_attr($current_user_role_key); ?>
                        </td>
                        <td>
                            <?php

                            $args = array(
                                'role' => $current_user_role_key,
                                'number' => -1,
                                'fields' => 'ids',
                            );
                            echo esc_attr(count(get_users($args)));

                            ?>
                        </td>
                        <td>
                           

                            <div class="ct-caadu-user-and-customer-detail ct-caadu-view-user-role-list"
                                style="display:none;">
                                <i class="fa fa-close ct-caadu-close-send-bulk-email-popup-main-container"
                                    style="top:-18px;right:-20px;"></i>

                                <div style="height: 356px;width: 1000px;overflow: scroll;padding: 18px;background: #fff;">

                                    <form class="ct-caadu-view-user-role-list-form" method="post">
                                        <ul>
                                            <?php foreach (get_users(array('role' => $current_user_role_key)) as $user): ?>
                                                <li>
                                                    <?php echo esc_html($user->user_login); ?>
                                                </li>
                                    
                                            <?php endforeach; ?>
                                        </ul>
                                                      
                                    </form>
                                </div>
                        
                            </div>
                                    
                                    
                                            <a data-popup_class="ct-caadu-view-user-role-list" href="#"
                                                class="ct-caadu-edit-or-view-capabilities af-tips fa fa-list"><span>
                                                    <?php echo esc_html__('View User Role List', 'addify_cog'); ?>
                                                </span></a>

                                        </td>

                                        <td>

                                            <div class="ct-caadu-user-and-customer-detail ct-caadu-create-view-user-role-capabilities ct-caadu-create-user-role-capabilities"
                                                style="display:none;">
                                                <i class="fa fa-close ct-caadu-close-send-bulk-email-popup-main-container"
                                                    style="top:-18px;right:-20px;"></i>

                                                <div style="height: 356px;width: 1000px;overflow: scroll;padding: 18px;background: #fff;">
                                                        <h4><?php echo esc_attr($value['name']); ?>
                                                                </h4>

                                                            <form>

                                                                <ul>
                                            <?php foreach ($all_capabilities as $key => $true_or_false) { ?>
                                                    <li>
                                                        <input type="checkbox"
                                                            name="current_user_role_capabilities[<?php echo esc_attr($current_user_role_key); ?>][<?php echo esc_attr($key); ?>]"
                                                            <?php
                                                            if (isset($value['capabilities']) && in_array($key, array_keys((array) $value['capabilities']))) {
                                                                ?> checked <?php } ?>>

                                                        <label>
                                                            <?php echo esc_attr(ucfirst(str_replace('_', ' ', $key))); ?>
                                                        </label>

                                                    </li>
                                            <?php } ?>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <div class="ct-caadu-user-and-customer-detail ct-caadu-create-edit-user-role-capabilities"
                                style="display:none;">
                                <i class="fa fa-close ct-caadu-close-send-bulk-email-popup-main-container"
                                    style="top:-18px;right:-20px;"></i>

                                <div style="height: 356px;width: 1000px;overflow: scroll;padding: 18px;background: #fff;">
                                    <form class="ct-caadu-create-edit-user-role-capabilities-form" method="post">
                                                        <h4><?php echo esc_attr($value['name']); ?>
                                                                </h4>
                                            <ul>
                                            <?php foreach ($all_capabilities as $key => $true_or_false) { ?>
                                                    <li>
                                                        <input type="checkbox"
                                                            name="current_user_role_capabilities[<?php echo esc_attr($key); ?>]"
                                                            value="<?php echo esc_attr($key); ?>" <?php
                                                               if (isset($value['capabilities']) && in_array($key, array_keys((array) $value['capabilities']))) {
                                                                   ?> checked <?php } ?>>

                                                        <label>
                                                            <?php echo esc_attr(ucfirst(str_replace('_', ' ', $key))); ?>
                                                        </label>

                                                    </li>
                                            <?php } ?>
                                        </ul>
                                        <input type="submit" name="submit" value="Update" class="button button-primary">
                                        <input type="hidden" name="user_role"
                                            value="<?php echo esc_attr($current_user_role_key); ?>">

                                    </form>
                                </div>

                            </div>

                            <a data-popup_class="ct-caadu-create-view-user-role-capabilities"
                                class="ct-caadu-edit-or-view-capabilities af-tips fa fa-eye" target="_blank" href="#"><span>
                                    <?php echo esc_html__('View Capabilities', 'addify_cog'); ?>
                                </span></a>

                            <a data-popup_class="ct-caadu-create-edit-user-role-capabilities" href="#"
                                class="ct-caadu-edit-or-view-capabilities af-tips fa fa-pencil"><span>
                                    <?php echo esc_html__('Edit Capabilities', 'addify_cog'); ?>
                                </span></a>

                        </td>
                    </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php

}