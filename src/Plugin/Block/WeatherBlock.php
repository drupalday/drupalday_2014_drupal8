<?php

namespace Drupal\weather\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Drupal\weather\WeatherService;
use Drupal\weather\WeatherServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WeatherBlock
 *
 * @Block(
 *   id = "weather",
 *   admin_label = @Translation("Current weather"),
 *   category = @Translation("Other")
 * )
 */
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\weather\WeatherServiceInterface
   */
  private $weatherService;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * @var \Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('weatherService'),
      $container->get('config.factory'),
      $container->get('state')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\weather\WeatherServiceInterface $weatherService
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\State\StateInterface $state
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WeatherServiceInterface $weatherService, ConfigFactoryInterface $configFactory, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->weatherService = $weatherService;
    $this->configFactory = $configFactory;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $city = $this->configFactory->get('weather.config')->get('city');
    $weather = $this->weatherService->getWeather($city);

    $timestamp = $this->state->get(WeatherService::LAST_RETRIEVED_TIMESTAMP);

    return array(
      '#theme' => 'weather',
      '#data' => $weather,
      '#timestamp' => $timestamp,
    );
  }
}
