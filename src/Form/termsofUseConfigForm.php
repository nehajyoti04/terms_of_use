<?php

/**
 * @file
 * Contains \Drupal\time_spent\Form\TimeSpentConfigForm.
 */

namespace Drupal\terms_of_use\Form;

use Drupal\Core\Form\ConfigFormBase;
//use Drupal\node\Entity\NodeType;
use Drupal\Core\Form\FormStateInterface;
//use Symfony\Component\HttpFoundation\Request;

class termsofUseConfigForm extends ConfigFormBase {
  public function getFormId() {
    return 'terms_of_use_admin_settings';
  }
  public function getEditableConfigNames() {
    return [
      'terms_of_use.settings',
    ];

  }
  public function buildForm(array $form, FormStateInterface $form_state) {

  // Adding the fieldset for node specification.
  $form['terms_of_use_text'] = array(
    '#type' => 'fieldset',
    '#prefix' => '<div id="fieldset-wrapper">',
    '#suffix' => '</div>',
  );

  $form['terms_of_use_text']['terms_of_use_node_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title of the post where your Terms of Use are published'),
    '#default_value' => \Drupal::state()->get('terms_of_use_node_title', ''),
    '#description' => t('Node <em>title</em> of the page or story (or blog entry or book page) where your Terms of Use are published.'),
    '#autocomplete_path' => 'terms_of_use/autocomplete',
  );
  $form['terms_of_use_text']['terms_of_use_pick_node_id'] = array(
    '#type' => 'button',
    '#value' => t('I prefer to specify the node id'),
    '#weight' => 10,
    '#ajax' => array(
      'callback' => 'terms_of_use_js',
      'wrapper' => 'fieldset-wrapper',
    ),
  );
//  // Adding the fieldset for form specification.
  $form['terms_of_use_form'] = array(
    '#type' => 'fieldset',
  );
  $form['terms_of_use_form']['terms_of_use_fieldset_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Label for the fieldset'),
    '#default_value' => \Drupal::state()->get('terms_of_use_fieldset_name', t('Terms of Use')),
    '#description' => t('The text for the Terms of Use and the [x] checkbox are contained in a fieldset. Type here the title for that fieldset.'),
  );
  $form['terms_of_use_form']['terms_of_use_checkbox_label'] = array(
    '#type' => 'textfield',
    '#title' => t('Label for the checkbox'),
    '#default_value' => \Drupal::state()->get('terms_of_use_checkbox_label', t('I agree with these terms')),
    '#description' => t('Type here something like "I agree with these terms." or "I CERTIFY THAT I AM OVER THE AGE OF 18 YEARS OLD.", without quotes. You can use the token @link to insert a link to the Terms in this label. For example, the label can be: "I agree with the @link.", without quotes. You may want to link to the Terms if you prefer not to show the full text of the Terms in the registration form. If you use the token, the Terms will not be shown.'),
  );


    return parent::buildForm($form, $form_state);

  }

  // @TODO: Validate function
  // @TODO: Helper function
  // terms_of_use_js
  /**
   * Helper function for autocompletion
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Set values in variables.
   w, $form_state->getValues()['terms_of_use_checkbox_label']);
    $config = $this->config('terms_of_use.settings');
    $config->set('terms_of_use_node_title', $form_state->getValues()['terms_of_use_node_title']);
    $config->set('terms_of_use_fieldset_name', $form_state->getValues()['terms_of_use_fieldset_name']);
    $config->set('terms_of_use_checkbox_label', $form_state->getValues()['terms_of_use_checkbox_label']);
    $config->save();
    parent::submitForm($form, $form_state);
  }
}
