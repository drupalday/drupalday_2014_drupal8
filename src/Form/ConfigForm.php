<?php

/**
 * @file
 * Contains \Drupal\webprofiler\Form\ConfigForm.
 */

namespace Drupal\weather\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webprofiler\Profiler\ProfilerStorageManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * Class ConfigForm
 */
class ConfigForm extends ConfigFormBase {

  /**
   * @var \Symfony\Component\HttpKernel\Profiler\Profiler
   */
  private $profiler;

  /**
   * @var array
   */
  private $templates;

  /**
   * @var \Drupal\webprofiler\Profiler\ProfilerStorageManager
   */
  private $storageManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('profiler'),
      $container->get('profiler.storage_manager'),
      $container->getParameter('data_collector.templates')
    );
  }

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Symfony\Component\HttpKernel\Profiler\Profiler $profiler
   * @param \Drupal\webprofiler\Profiler\ProfilerStorageManager $storageManager
   * @param array $templates
   */
  public function __construct(ConfigFactoryInterface $config_factory, Profiler $profiler, ProfilerStorageManager $storageManager, $templates) {
    parent::__construct($config_factory);

    $this->profiler = $profiler;
    $this->templates = $templates;
    $this->storageManager = $storageManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webprofiler_config';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('weather.config');

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

    parent::submitForm($form, $form_state);
  }

}
