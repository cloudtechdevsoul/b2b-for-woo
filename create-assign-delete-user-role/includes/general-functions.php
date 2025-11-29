<?php


function devsoul_caadu_get_country_full_name($country_code)
{

    $all_countries = new WC_Countries();
    $all_countries = (array) $all_countries->get_countries();

    return isset($all_countries[$country_code]) ? $all_countries[$country_code] : '';

}

function devsoul_caadu_get_state_full_name($country_code = '', $state_code = '')
{

    $wc_countries = new WC_Countries();
    $states = $wc_countries->get_states($country_code);
    return isset($states[$state_code]) ? $states[$state_code] : '';
}