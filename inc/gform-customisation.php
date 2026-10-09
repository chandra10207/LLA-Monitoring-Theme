<?php 

add_filter('csp_sfc_gf_form_object_map', function ($map) {

    return [
        2  => 'Setup_Form__c',
    ];
});

add_filter('gform_countries', 'lla_limit_countries_to_au_nz');
function lla_limit_countries_to_au_nz($countries)
{ 
    $countries = array('Australia');
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


    if ($result['is_valid'] && !empty($value) && !preg_match('/^\d+$/', $value)) {
        $result['is_valid'] = false;
        $result['message'] = 'Please enter numbers only. No spaces or character allowed.';
    }

    return $result;
}






