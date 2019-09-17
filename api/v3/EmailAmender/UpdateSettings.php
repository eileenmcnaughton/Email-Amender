<?php

/**
 * SystemSettings.Update API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_email_amender_update_settings_spec(&$spec) {
  $spec['email_amender_enabled']['api.required'] = 1;
}

/**
 * SystemSettings.Update API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_email_amender_update_settings($params) {

  Civi::settings()->set('email_amender_enabled', $params['email_amender_enabled']);
  return civicrm_api3_create_success(NULL, $params, 'EmailAmender', 'update_settings');
}
