<?php
/**
 * php-openweathermap-wrapper — PHP wrapper for the OpenWeatherMap.org API.
 *
 * @license MIT
 *
 * Please see the LICENSE file distributed with this source code for further
 * information regarding copyright and licensing.
 *
 * Please visit the following links to read about the usage policies and the license of
 * OpenWeatherMap before using this PHP wrapper:
 *
 * @see http://www.openweathermap.org
 * @see http://www.openweathermap.org/terms
 * @see http://www.openweathermap.org/appid
 */

namespace cnorton_webdev\open_weather;

class current_weather {
    
    // Ugly but needed.
    private $lat, $lon, $country, $sunrise, $sunset, $wx_id, $description, $icon, $temp, $humidity, $pressure, $temp_min, $temp_max, $wind_speed, $wind_deg, $rain, $snow, $clouds, $dt, $cityid, $city_name, $unit, $token;
    private $raw_json;
    private $api_endpoint = 'http://api.openweathermap.org/data/2.5/';
    
    /**
     * Create a new current_weather object
     * @private
     * @param string $token OpenWeatherMap API token
     */
    public function __construct($token) {
        if (is_string(trim($token))) {
            $this->token = trim($token);
        } else {
            throw new \Exception('You must provide your API token key.');
        }
    }
    
    /**
     * Return Latitude of weather data fetched
     * @return float Latitude
     */
    public function lat() {
        return $this->lat;
    }
    
    /**
     * Return Longitude of weather data fetched
     * @return float Longitude
     */
    public function lon() {
        return $this->lon;
    }
    
    /**
     * Return the weather ID
     * @return string Weather ID
     */
    public function weather_id() {
        return $this->wx_id;
    }
    
    /**
     * Returns main weather type
     * @return string Weather main
     */
    public function main() {
        return $this->main;
    }
    
    /**
     * Return weather description
     * @return string Weather description
     */
    public function description() {
        return $this->description;
    }
    /**
     * Return weather icon
     * @return string Weather Icon
     */
    public function icon() {
        return $this->icon;
    }
    
    /**
     * Return current temperature
     * @return float Current temperature
     */
    public function temp() {
        return $this->temp;
    }
    
    /**
     * Return current humidity
     * @return integer Current humidity
     */
    public function humidity() {
        return $this->humidity;
    }
    
    /**
     * Return current pressure
     * @return integer Current pressure
     */
    public function pressure() {
        return $this->pressure;
    }
    
    /**
     * Returns minimum temperature at the moment, unsure if used.
     * @return float Minimum temperature
     */
    public function temp_min() {
        return $this->temp_min;
    }
    
    /**
     * Returns maximum temperature at the moment, unsure if used.
     * @return float Maximum temperature
     */
    public function temp_max() {
        return $this->temp_max;
    }
    
    /**
     * Returns current wind speed
     * @return float Current wind speed
     */
    public function wind_speed() {
        return $this->wind_speed;
    }
    
    /**
     * Returns current wind direction in degrees
     * @return integer Wind direction in degrees
     */
    public function wind_direction() {
        return $this->wind_deg;
    }
    
    /**
     * Returns current rain volume
     * @return integer Rain volume
     */
    public function rain() {
        return $this->rain;
    }
    
    /**
     * Returns current snow volume
     * @return integer Snow volume
     */
    public function snow() {
        return $this->snow;
    }
    
    /**
     * Returns cloud coverage percentage
     * @return integer Cloud coverage
     */
    public function clouds() {
        return $this->clouds;
    }
    
    /**
     * Returns Unix timestamp of when data was calculated, UTC
     * @return integer Data calculation time as Unix timestamp
     */
    public function data_time() {
        return $this->dt;
    }
    
    /**
     * Returns country code for location of weather data
     * @return string Country code of location
     */
    public function country_code() {
        return $this->country;
    }
    
    /**
     * Returns sunrise time as Unix timestamp, UTC
     * @return integer Sunrise time as Unix timestamp, UTC
     */
    public function sunrise() {
        return $this->sunrise;
    }
    
    /**
     * Returns sunset time as Unix timestamp, UTC
     * @return integer Sunset time as Unix timestamp, UTC
     */
    public function sunset() {
        return $this->sunset;
    }
    
    /**
     * Returns city ID
     * @return integer City ID
     */
    public function cityid() {
        return $this->cityid;
    }
    
    /**
     * Returns city name
     * @return string City name
     */
    public function city_name() {
        return $this->city_name;
    }
    
    /**
     * Returns data as a raw json string
     * @return string Raw json string
     */
    public function json() {
        return $this->raw_json;
    }
    
    /**
     * Converts windspeed into km/h
     * @return integer calculated windspeed in km/h
     */
    public function wind_km() {
        if (isset($this->wind_speed) && $this->unit == 'metric') {
            return round($this->wind_speed * 3.6, 2);
        } elseif (isset($this->wind_speed) && $this->unit == 'imperial') {
            return round($this->wind_speed * 1.61, 2);
        }
    }
    
    /**
     * Returns weather data for the city provided
     * @param string $city               Name of city
     * @param string $country     Country or state, default: blank
     * @param string $units Unit of measurement, default: metric
     * @param string $lang      Language of data, default: en
     */
    public function city($city, $country = '', $units = 'metric', $lang = 'en') {
        if ( empty(trim($city)) ) throw new \Exception('City can not be left blank.');
        if ( trim($country != '') ) {
            $params = http_build_query( array('q' => urlencode($city . ',' . $country), 'units' => $units, 'lang' => $lang) );
        } else {
            $params = http_build_query( array('q' => urlencode($country), 'units' => $units, 'lang' => $lang) );
        }
        $this->raw_json = $this->web( 'weather?', $params);
        $api_data = json_decode($this->raw_json);
        if (!is_object($api_data)) throw new \Exception('Error parsing Open Weather API data.');
        $this->save_data($api_data, $units);
    }
    
    /**
     * Retrieves API data based on internal OpenWeatherMap City ID
     * @param integer $cid                OpenWeatherMap City ID
     * @param string $units Unit of measurement, default: metric
     * @param string $lang      Language of data, default: en
     */
    public function city_id($cid, $units = 'metric', $lang = 'en') {
        if ( empty(trim($cid)) ) throw new \Exception('Latitude or Longitude can not be left blank.');
        $params = http_build_query( array('id' => $cid, 'units' => $units, 'lang' => $lang) );
        $this->raw_json = $this->web( 'weather?', $params);
        $api_data = json_decode($this->raw_json);
        if (!is_object($api_data)) throw new \Exception('Error parsing Open Weather API data.');
        $this->save_data($api_data, $units);
    }
    
    /**
     * Retrieves API data based on latitude and longitude
     * @param integer $lon Longitude
     * @param integer $lat Latitude
     * @param string $units Unit of measurement, default: metric
     * @param string $lang      Language of data, default: en
     */
    public function coord($lon, $lat, $units = 'metric', $lang = 'en') {
        if ( empty(trim($lon)) || empty(trim($lat)) ) throw new \Exception('Latitude or Longitude can not be left blank.');
        $params = http_build_query( array('lat' => $lat, 'lon' => $lon, 'units' => $units, 'lang' => $lang) );
        $this->raw_json = $this->web( 'weather?', $params);
        $api_data = json_decode($this->raw_json);
        if (!is_object($api_data)) throw new \Exception('Error parsing Open Weather API data.');
        $this->save_data($api_data, $units);
    }
    
    /**
     * Retrieves API data based on zipcode
     * @param integer $zip Zipcode
     * @param string $country     Country code
     * @param string $units Unit of measurement, default: metric
     * @param string $lang      Language of data, default: en
     */
    public function zip($zip, $country, $units = 'metric', $lang = 'en') {
        if ( empty(trim($zip)) || empty(trim($country)) ) throw new \Exception('Zipcode or Country code can not be left blank.');
        $params = http_build_query( array('zip' => $zip . ',' . $country, 'units' => $units, 'lang' => $lang) );
        $this->raw_json = $this->web( 'weather?', $params);
        $api_data = json_decode($this->raw_json);
        if (!is_object($api_data)) throw new \Exception('Error parsing Open Weather API data.');
        $this->save_data($api_data, $units);
    }
    /**
     * Saves data returned from API to individual variables for ease of access.
     * @param object $api_data json object of API data
     * @param string $units    Unit of measure
     */
    private function save_data($api_data, $units) {
        $this->unit = trim($units);
        $this->lat = $api_data->coord->lat;
        $this->lon = $api_data->coord->lon;
        $this->wx_id = $api_data->weather[0]->id;
        $this->main = $api_data->weather[0]->main;
        $this->description = $api_data->weather[0]->description;
        $this->icon = $api_data->weather[0]->icon;
        $this->temp = $api_data->main->temp;
        $this->pressure = $api_data->main->pressure;
        $this->humidity = $api_data->main->humidity;
        $this->temp_min = $api_data->main->temp_min;
        $this->temp_max = $api_data->main->temp_max;
        $this->wind_speed = $api_data->wind->speed;
        $this->wind_deg = $api_data->wind->deg;
        $this->clouds = $api_data->clouds->all;
        $this->rain = (property_exists($api_data, 'rain') ? $api_data->rain->{'3h'} : '');
        $this->snow = (property_exists($api_data, 'rain') ? $api_data->snow->{'3h'} : '');
        $this->dt = $api_data->dt;
        $this->country = $api_data->sys->country;
        $this->sunrise = $api_data->sys->sunrise;
        $this->sunset = $api_data->sys->sunset;
        $this->cityid = $api_data->id;
        $this->city_name = $api_data->name;
    }
    
	/**
	 * Performs API call operations to the Mashape API server
	 * @param  string $method API method
	 * @param  string $params API URL parameters
	 * @return string Server response string
	 */
	private	function web($method, $params)
	{
		try
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->api_endpoint . $method . $params . '&appid=' . $this->token);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$source = curl_exec($ch);
			return $source;
		}

		catch(\Exception $e)
		{
            throw $e->getMessage();
		}
	}

}