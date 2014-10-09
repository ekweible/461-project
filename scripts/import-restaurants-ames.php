<?php
$places = file_get_contents('https://maps.googleapis.com/maps/api/place/radarsearch/json?location=42.02322,-93.6455&radius=8000&types=restaurant&key=AIzaSyBKOH0lhT0hmyBw-2j-X0CvYEVr1nmiCUU');

$places = json_decode($places)->results;

$connect = mysql_connect('localhost:3307','restaurant','se329');
	
if (!$connect)
{
	die('Could not connect: ' . mysql_error());
}

if (!mysql_select_db('restaurant'))
{
	die('Error selecting database:' . mysql_error());
}


$count = 0;

foreach($places as $place)
{
	$count++;
	$lat = $place->geometry->location->lat;
	$lng = $place->geometry->location->lng;
	$placeId = $place->place_id;
	$place = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=AIzaSyBKOH0lhT0hmyBw-2j-X0CvYEVr1nmiCUU");
	$place = json_decode($place)->result;
	$name = addslashes($place->name);
	$query = "INSERT INTO food(name, lat, lng) 
		values
		('$name', $lat, $lng);";

	// var_dump($place->name, $query, '<br>');

	if (!mysql_query($query))
	{
		die('Error: ' . mysql_error());
	}

	// echo "Name: $place->name <br>
	// 	Lat: $lat <br>
	// 	Lng: $lng <br>";
}

echo "Entered " . $count . " restaurants.";

mysql_close($connect);

?>
