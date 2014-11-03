<?php

/**
 * @file
 * Contains \Drupal\weather\Controller\WeatherController.
 */

namespace Drupal\weather\Controller;
use Drupal\Core\Controller\ControllerBase;
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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('weatherService')
    );
  }

  /**
   * @param \Drupal\weather\WeatherServiceInterface $weatherService
   */
  public function __construct(WeatherServiceInterface $weatherService) {
    $this->weatherService = $weatherService;
  }

  /**
   * @return array
   */
  public function view() {
    $city = $this->config('weather.config')->get('city');
    $weather = $this->weatherService->getWeather($city);

    return array(
      '#theme' => 'weather',
      '#data' => $weather,
    );
  }

}
