<?php

/**
 * @file
 * Contains \Drupal\weather\Form\ConfigForm.
 */

namespace Drupal\weather\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weather_config';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('weather.config');

    $form_state->set('old_city', $config->get('city'));

    $form['city'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#description' => $this->t('Insert the city to get weather information.'),
      '#required' => TRUE,
      '#default_value' => $config->get('city'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('weather.config')
      ->set('city', $form_state->getValue('city'))
      ->save();

    $city = $form_state->get('old_city');
    Cache::invalidateTags(array("city:$city"));

    parent::submitForm($form, $form_state);
  }

}
