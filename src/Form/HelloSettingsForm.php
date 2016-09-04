<?php

/**
 * @file
 * Contains \Drupal\hello\helloSettingsForm
 */
namespace Drupal\terms_of_use\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure hello settings for this site.
 */
class helloSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'hello_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'terms_of_use.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('terms_of_use.settings');

    $form['hello_thing'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('thing'),
      '#default_value' => $config->get('things'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('terms_of_use.settings')
      ->set('things', $form_state->getValue('hello_thing'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}