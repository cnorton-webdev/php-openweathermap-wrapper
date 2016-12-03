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

// Require our current_weather.php file which contains our class and functions
require_once '../cnortonwebdev/current_weather.php';

// Place your API key between the single quote marks below
$api_key = '';

// Create a new current_weather object
$weather = new cnorton_webdev\open_weather\current_weather($api_key);

// Fetch the weather by city for Charlotte, NC using default of metric units
$weather->city('Charlotte', 'NC');

// Set this to your timezone. See: http://www.php.net/manual/en/timezones.php
date_default_timezone_set('America/New_York');

// Output the sunset for the current city in format: H:MM:SS AM/PM For full format information see: http://www.php.net/manual/en/function.date.php
$sunset = date( 'g:i:s a', $weather->sunset() );

$sunrise = date( 'g:i:s a', $weather->sunrise() );

echo "Sunrise is at: {$sunrise}<br />\n";

echo "Sunset is at: {$sunset}<br />\n";

echo "The current temperature is: {$weather->temp()}<br />\n";

echo "The current pressure is: {$weather->pressure()}<br />\n";

echo "The current wind speed is: {$weather->wind_speed()} and wind direction is: {$weather->wind_direction()}<br />\n";

echo "The current cloud cover is: {$weather->clouds()}%<br />\n";

echo "The weather currently is: {$weather->description()}<br />\n";

echo "Did you know that I can also give you the wind in km/h? It is currently: {$weather->wind_km()} km/h<br />\n";

// Below are some examples of other supported ways of looking up weather data:

// By zip code, you must supply the country code as well.
// $weather->zip('28201', 'US');

// By Longitude and Latitude
// $weather->coord(-80.84,35.23);

// By OpenWeatherMap city ID
// $weather->city_id('4460243');


// Every call has the following options after required parameters: unit of measurement, language
// Example using the above city lookup: $weather->city('Charlotte, 'NC', 'imperial', 'en');
// Units of measure are: imperial, metric or kelvin. Default: metric

// If you would like to parse the raw json from the API directly, you can by calling:
// $weather->json() to return the raw json string.