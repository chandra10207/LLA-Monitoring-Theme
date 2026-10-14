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


function lla_build_monioring_agreement_data($entry){


$commencement_date = $entry['11'];
$formatted_commencement_date = '';
if(!empty($commencement_date)){
    $date_obj = DateTime::createFromFormat('!d F Y', $commencement_date);
    $formatted_commencement_date = $date_obj ? $date_obj->format('Y-m-d') : '';
}




            $GF_Entry = [
      'entry_id'          => $entry['id'],
      'form_id'           => $entry['form_id'],
      'date_created'      => $entry['date_created'],
      'enquiry_id'     => $entry['8'] ?? '',
      'ip'     => $entry['ip'] ?? '',

      // Prescriber Details
      'client_organisation_name' => $entry['9'] ?? '',
      'client_organisation_street_address'  => $entry['10.1'] ?? '',
      'client_organisation_city'  => $entry['10.3'] ?? '',
      'client_organisation_state'  => $entry['10.4'] ?? '',
      'client_organisation_postcode'  => $entry['10.5'] ?? '',
      'client_organisation_country'  => $entry['10.6'] ?? '',
      'client_organisation_full_address'  => $entry['16'] ?? '',

      'prescriber_phone'    => $entry['88'] ?? '',
      'prescriber_email'    => $entry['24'] ?? '',
      'email'    => $entry['3'] ?? '',
      'phone'  => $entry['4'] ?? '',
      'commencement_date'     => $formatted_commencement_date ?? '',
      'term'    => $entry['12'] ?? '',
      'fullname_of_signee' => $entry['13'] ?? '',
      'signing_on_behalf'  => $entry['14'] ?? '',

    ];

    return $GF_Entry;
}

function lla_add_monitoring_agreement_data_sf($data, $entry, $object_name = null)
{
    if (!empty($data) && $entry['form_id'] != '1') {
        return $data;
    }

    $GF_Entry = lla_build_monioring_agreement_data($entry);

    $salesforce_data = [
        'Client_Organisation_Name__c' =>  $GF_Entry['client_organisation_name'],
        'Commencement_Date__c'        => $GF_Entry['commencement_date'],             // Commencement Date (date)
        'Client_Organisation_Address__c'                  => $GF_Entry['client_organisation_full_address'],                // Country (string 255)
        'Email__c'                    => $GF_Entry['email'],                // Email (email 80)
        'Enquiry_ID__c'               => $GF_Entry['enquiry_id'],                // Enquiry ID (string 255)
        'Full_Name_of_Signee__c'      => $GF_Entry['fullname_of_signee'],                // Full name of Signee (string 255)
        'Phone__c'                    => $GF_Entry['phone'],                // Phone (phone 40)
        'Signing_on_Behalf_of__c'     => $GF_Entry['signing_on_behalf'],                // Signing on Behalf of (string 255)
        'Source__c'                   => 'Woo',                // Source (picklist 255)
        'Term__c'                     => $GF_Entry['term'],                // Term (picklist 255)
    ];
    return $salesforce_data;

}
add_filter('csp_sfc_gf_generic_form_data', 'lla_add_monitoring_agreement_data_sf', 10,3);
