<?php

namespace Drupal\weather;

/**
 * Interface WeatherServiceInterface
 */
interface WeatherServiceInterface {

  /**
   * @param $city
   *
   * @return mixed
   */
  public function getWeather($city);

}
