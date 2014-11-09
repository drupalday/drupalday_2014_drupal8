<?php

/**
 * @file
 * Contains \Drupal\weather\Controller\WeatherController.
 */

namespace Drupal\weather\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\State\StateInterface;
use Drupal\weather\WeatherService;
use Drupal\weather\WeatherServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WeatherController
 */
class WeatherController extends ControllerBase {

  /**
   * @var \Drupal\weather\WeatherServiceInterface
   */
  private $weatherService;

  /**
   * @var \Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * {@inheritdoc}
   *
   * Uses late static binding to create an instance of this class with
   * injected dependencies.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('weatherService'),
      $container->get('state')
    );
  }

  /**
   * @param \Drupal\weather\WeatherServiceInterface $weatherService
   * @param \Drupal\Core\State\StateInterface $state
   */
  public function __construct(WeatherServiceInterface $weatherService, StateInterface $state) {
    $this->weatherService = $weatherService;
    $this->state = $state;
  }

  /**
   * @return array
   */
  public function view() {
    $city = $this->config('weather.config')->get('city');
    $weather = $this->weatherService->getWeather($city);

    $timestamp = $this->state->get(WeatherService::LAST_RETRIEVED_TIMESTAMP);

    return array(
      '#theme' => 'weather',
      '#data' => $weather,
      '#timestamp' => $timestamp,
    );
  }

}
