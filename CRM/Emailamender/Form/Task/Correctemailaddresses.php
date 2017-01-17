<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Emailamender_Form_Task_Correctemailaddresses extends CRM_Contact_Form_Task {
  function buildQuickForm() {

    $this->assign('contactIdCount', count($this->_contactIds));
    
    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Correct Email Addresses'),
        'isDefault' => TRUE,
      ),
    ));

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  function postProcess() {
    $emailAmender = new CRM_Emailamender();

    $contactCount = 0;
    $correctionCount = 0;
    
    foreach($this->_contactIds as $eachContactId) {
      $contactCount++;
      
      $updateParam = array(
        "version" => 3,
        "contact_id" => $eachContactId,
      );

      $emailAddresses = civicrm_api('Email', 'get', $updateParam);

      foreach($emailAddresses['values'] as $eachEmailAddress) {
        if ($emailAmender->check_for_corrections($eachEmailAddress['id'], $eachEmailAddress['contact_id'], $eachEmailAddress['email'])) {
          $correctionCount++;
        }
      }
    }

    CRM_Core_Session::setStatus(ts("Corrected $correctionCount email addresses across $contactCount contacts."));

    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}