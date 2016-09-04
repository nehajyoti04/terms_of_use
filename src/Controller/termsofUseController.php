<?php


namespace Drupal\terms_of_use\Controller;

use Drupal\Core\Controller\ControllerBase;

class termsofUseController extends ControllerBase {
  public function autocomplete() {
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Hello World!'),
    );
    return $build;
  }
}
