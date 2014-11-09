<?php

namespace Drupal\weather;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Http\Client;
use Drupal\Core\State\StateInterface;

/**
 * Class WeatherService
 *
 * Uses the open Weather Map API to retrieve a city weather.
 */
class WeatherService implements WeatherServiceInterface {

  const LAST_RETRIEVED_TIMESTAMP = 'weather_last_retrieved_timestamp';

  /**
   * @var \Drupal\Core\Http\Client
   */
  private $client;

  /**
   * @var \Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  private $cache;

  /**
   * @param \Drupal\Core\Http\Client $client
   * @param \Drupal\Core\State\StateInterface $state
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   */
  public function __construct(Client $client, StateInterface $state, CacheBackendInterface $cache) {
    $this->client = $client;
    $this->state = $state;
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeather($city) {
    if ($data = $this->cache->get("weather_data")) {
      return $data->data;
    }
    else {
      // $this->client is an instance of Guzzle Client
      $response = $this->client->get('http://api.openweathermap.org/data/2.5/weather?q=' . $city . ',it&units=metric');
      $data = $response->json();

      // puts data in cache with "city:$city" cache tag
      $this->cache->set("weather_data", $data, Cache::PERMANENT, array("city:$city"));

      // saves the last retrieved timestamp with the State API
      $this->state->set(WeatherService::LAST_RETRIEVED_TIMESTAMP, time());

      return $data;
    }
  }

}
