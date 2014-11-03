<?php

namespace Drupal\weather;

use Drupal\Core\Http\Client;

/**
 * Class WeatherService
 */
class WeatherService implements WeatherServiceInterface {

  /**
   * @var \Drupal\Core\Http\Client
   */
  private $client;

  /**
   * @param \Drupal\Core\Http\Client $client
   */
  public function __construct(Client $client) {
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeather($city) {
    $response = $this->client->get('http://api.openweathermap.org/data/2.5/weather?q=' . $city . ',it&units=metric');

    return $response->json();
  }

}
