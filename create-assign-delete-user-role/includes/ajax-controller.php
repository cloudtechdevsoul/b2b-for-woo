<?php

add_action('wp_ajax_ct_assign_new_user_role', 'ct_assign_new_user_role');

function ct_assign_new_user_role()
{


    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : 0;
    if (wp_verify_nonce($nonce, 'ct_caadu_nonce') && isset($_POST['form'])) {
        $ct_user_ids = isset($_POST['ct_user_ids']) ? sanitize_meta('', $_POST['ct_user_ids'], '') : [];
        parse_str(sanitize_text_field($_POST['form']), $form);
        $assign_user_role = isset($form['assign_user_role']) ? $form['assign_user_role'] : '';
        $selected_new_user_role = isset($form['selected_new_user_role']) ? $form['selected_new_user_role'] : '';


        foreach ($ct_user_ids as $user_id) {
            $user = new WP_User($user_id);

            if ($user_id && $user) {

                $user_data = get_userdata($user_id);
                if (!$user_data) {
                    return false;
                }
                $current_role = current($user_data->roles); // Assuming the user has only one role at a time
                $old_rules_array = $user_data->roles;

                if ('switch_to' == $assign_user_role) {
                    if ($selected_new_user_role !== $current_role) {

                        $user_data->set_role($selected_new_user_role);
                        wp_update_user($user_data);
                        continue;
                    }
                } else if ('secondary' == $assign_user_role) {

                    if (in_array($selected_new_user_role, $old_rules_array)) {

                        $get_role_index = array_search($selected_new_user_role, $old_rules_array);
                        unset($old_rules_array[$get_role_index]);

                    }

                    $old_rules_array[] = $selected_new_user_role;

                    $user->set_role('');

                    $user->set_role(current($old_rules_array));

                    unset($old_rules_array[current(array_keys($old_rules_array))]);

                    foreach ($old_rules_array as $new_role) {
                        if ($new_role) {
                            $user->add_role($new_role);
                        }
                    }


                } else if ('primary' == $assign_user_role) {

                    $old_rules_array = $user_data->roles;

                    $user->set_role('');

                    $user->set_role($selected_new_user_role);

                    if (in_array($selected_new_user_role, $old_rules_array)) {

                        $get_role_index = array_search($selected_new_user_role, $old_rules_array);
                        unset($old_rules_array[$get_role_index]);
                    }


                    foreach ($old_rules_array as $old_role) {
                        if ($old_role) {
                            $user->add_role($old_role);
                        }
                    }

                }

            }

        }
    }

    wp_send_json(['success' => true]);
}
add_action('wp_ajax_ct_caadu_create_new_user_role', 'ct_caadu_create_new_user_role');

function ct_caadu_create_new_user_role()
{

    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'ct_caadu_nonce')) {
        wp_die(esc_html__('Security Violated!', 'addify_customer_manager'));
    }

    $get_default_wp_roles = wp_roles();


    if (!isset($get_default_wp_roles)) {
        $get_default_wp_roles = new WP_Roles();
    }


    global $wp_roles;
    if (isset($_POST['form_data'])) {

        parse_str(sanitize_text_field($_POST['form_data']), $form_data);

        $user_role_name = isset($form_data['user_role_name']) ? $form_data['user_role_name'] : '';
        $user_role_key = isset($form_data['user_role_key']) ? $form_data['user_role_key'] : '';
        $capabilitites_type = isset($form_data['capabilitites_type']) ? $form_data['capabilitites_type'] : 'user_role';
        $selected_user_role = isset($form_data['selected_user_role']) ? $form_data['selected_user_role'] : 'administrator';


        $all_capabilities = array();

        if ('select_custom_capabilities' == $capabilitites_type && isset($_POST['selected_capabilites']) && !empty($_POST['selected_capabilites'])) {

            $originalArray = sanitize_meta('', $_POST['selected_capabilites'], '');
            $all_capabilities = array_combine($originalArray, array_fill(1, count($originalArray), null));


        } else {



            foreach ($get_default_wp_roles->role_objects as $role_slug => $current_role) {

                if (isset($current_role->name) && $selected_user_role == $current_role->name) {

                    $all_capabilities = (array) $current_role->capabilities;

                }
            }
        }

        foreach ($all_capabilities as $cap_type => $value) {
            $all_capabilities[$cap_type] = true;
        }

        $wp_all_created_roles = $wp_roles->get_names();
        if (isset($wp_all_created_roles[$user_role_key])) {
            ob_start();
            ?>
            <div id="message" class="error">
                <p>
                    <strong>
                        <?php echo esc_html__('Role Already Created', 'addify_customer_manager'); ?>
                    </strong>
                </p>
            </div>
            <?php
            $result = ob_get_clean();

            wp_send_json_success(array('error' => $result));

        } else if (!empty($user_role_name) && !empty($user_role_key)) {

            $role = add_role($user_role_key, $user_role_name, $all_capabilities);

            $old_roles = (array) get_option('af_cmfw_created_user_role_from_our_plugin');

            if (is_wp_error($role)) {
                ob_start();

                $errors = $role->get_error_messages();

                foreach ($errors as $error) {

                    ?>
                        <br>
                        <div id="message" class="error">
                            <p>
                                <strong>
                                <?php echo esc_attr($error); ?>
                                </strong>
                            </p>
                        </div>
                    <?php
                }

                $result = ob_get_clean();

                wp_send_json_success(array('error' => $result));

            } else {

                $old_roles[] = $user_role_key;
                $old_roles = array_unique($old_roles);
                update_option('af_cmfw_created_user_role_from_our_plugin', $old_roles);

                ob_start();
                ?>
                    <div id="message" class="success">
                        <p><strong>
                            <?php esc_html_e('User Role created Successfully.', 'addify_customer_manager'); ?>
                            </strong></p>
                    </div>
                    <?php

                    $result = ob_get_clean();

                    wp_send_json_success(array('success_message' => $result));
            }


        }


    }
}

add_action('wp_ajax_ct_caadu_update_capabilities', 'ct_caadu_update_capabilities');
function ct_caadu_update_capabilities()
{

    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'ct_caadu_nonce')) {
        wp_die(esc_html__('Security Violated!', 'addify_customer_manager'));
    }

    $get_default_wp_roles = wp_roles();


    if (!isset($get_default_wp_roles)) {
        $get_default_wp_roles = new WP_Roles();
    }



    if (isset($_POST['form_data'])) {

        parse_str(sanitize_text_field($_POST['form_data']), $form_data);

        if ($form_data['user_role']) {

            $user_role = $form_data['user_role'];

            $current_user_role = get_role($user_role);

            if ($current_user_role) {
                $old_capabilities = $current_user_role->capabilities;

                foreach ($old_capabilities as $cap_type => $true_or_false) {

                    $current_user_role->remove_cap($cap_type);

                }

            }

            foreach ($form_data as $key => $value) {

                if (str_contains($key, 'current_user_role_capabilities')) {
                    $current_user_role->add_cap($value);
                }
            }
        }

        wp_send_json_success(array('success_message' => 'Capabilities Update Successfully!'));

    }
}