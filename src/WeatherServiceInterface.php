<?php

namespace Drupal\weather;

/**
 * Interface WeatherServiceInterface
 */
interface WeatherServiceInterface {

  /**
   * Retrieves the weather for a specific city.
   *
   * @param string $city
   *
   * @return array
   */
  public function getWeather($city);

}
