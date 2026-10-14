<?php

add_filter('csp_sfc_gf_form_object_map', function ($map) {

    return [
        1 => 'Monitoring_Agreement_Form__c',
        2 => 'Setup_Form__c',
    ];
});

add_filter('gform_countries', 'lla_limit_countries_to_au_nz');
function lla_limit_countries_to_au_nz($countries)
{
    $countries = ['Australia'];
    return $countries;
}

add_filter('gform_field_validation_2_46', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_47', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_48', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_50', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_49', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_51', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_54', 'lla_validate_numeric_phone_field', 10, 5);
add_filter('gform_field_validation_2_58', 'lla_validate_numeric_phone_field', 10, 5);

function lla_validate_numeric_phone_field($result, $value, $form, $field)
{

    // Allow only digits
    // if ($result['is_valid'] && !empty($value) && !preg_match('/^[0-9\s\-]+$/', $value)) {
    //     $result['is_valid'] = false;
    //     $result['message'] = 'Please enter numbers only.';
    // }

    if ($result['is_valid'] && ! empty($value) && ! preg_match('/^\d+$/', $value)) {
        $result['is_valid'] = false;
        $result['message']  = 'Please enter numbers only. No spaces or character allowed.';
    }

    return $result;
}

add_filter('gform_signature_init_options', 'customize_signature_options', 10, 3);
function customize_signature_options($init_options, $field, $form)
{

    $init_options['minWidth'] = 1; // Thicker minimum line width
    $init_options['maxWidth'] = 1; // Thicker maximum line width

    return $init_options;
}

function lla_add_monitoring_agreement_data_sf($data, $entry, $object_name = null)
{
    // if (!empty($data) && $entry['form_id'] != '1') {
    //     return $data;
    // }

    $salesforce_data = [
        'Client_Organisation_Name__c' => 'LiveLife Alarms', // Client / Organisation Name (string 255)
        'Commencement_Date__c'        => '202',             // Commencement Date (date)
        'Country__c'                  => '',                // Country (string 255)
        'Email__c'                    => '',                // Email (email 80)
        'Enquiry_ID__c'               => '',                // Enquiry ID (string 255)
        'Full_Name_of_Signee__c'      => '',                // Full name of Signee (string 255)
        'Organisation_City__c'        => '',                // Organisation City (string 255)
        'Organisation_State__c'       => '',                // Organisation State (string 255)
        'Phone__c'                    => '',                // Phone (phone 40)
        'Organisation_Postcode__c'    => '',                // Organisation Postcode (string 255)
        'Signing_on_Behalf_of__c'     => '',                // Signing on Behalf of (string 255)
        'Source__c'                   => '',                // Source (picklist 255)
        'Organisation_Street_Name__c' => '',                // Organisation Street Name (string 255)
        'Term__c'                     => '',                // Term (picklist 255)
    ];
    return $salesforce_data;

}
add_filter('csp_sfc_gf_generic_form_data', 'lla_add_monitoring_agreement_data_sf', 10,3);
