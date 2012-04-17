<?php
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$range = $_POST["radius"];
if (!$range) {
	$range = 0;
}
$bedrooms = $_POST["bedrooms"] == "true"? true: false; 
$houses = $_POST["houses"] == "true"? true: false;
$address = $_POST["address"] == "true"? true: false;
$house_size = $_POST["house_size"] == "true"? true: false;
$lot_size = $_POST["lot_size"] == "true"? true: false;
$br = $_POST["br"] == "true"? true: false;
$bathrooms = $_POST["bathrooms"] == "true"? true: false;
$year_built = $_POST["year_built"] == "true"? true: false;
$con = mysql_connect("lager.cs.berkeley.edu","jeffrey.nieh","housedata");
if (!$con) {
	die('Could not connect: ' . mysql_error());
}
$html = "";
mysql_select_db("housedata", $con);
if ($house_size || $address || $lot_size || $br || $bathrooms || $year_built) {
	$result = mysql_query("SELECT * FROM house_data as H, location_data as L WHERE H.zpid=L.zpid ORDER BY ((ACOS(SIN($latitude * PI() / 180) * SIN(latitude * PI() / 180) + COS($latitude * PI() / 180) * COS(latitude * PI() / 180) * COS(($longitude - longitude ) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) LIMIT 1");
	
	$row = mysql_fetch_array($result);
	if ($address) {
		$html .= "Address: " . $row["address"] . "<br>";
	}  
	if ($house_size) {
		$html .= "House Size: " . $row["house_size"] . "<br>";
	} 
	if ($lot_size) {
		$html .= "Lot Size: " . $row["lot_size"] . "<br>";
	}
	if ($br) {
		$html .= "Bedrooms: " . $row["bedrooms"] . "<br>";
	}  
	if ($bathrooms) {
		$html .= "Bathrooms: " . $row["bathrooms"] . "<br>";
	}  
	if ($year_built) {
		$html .= "Year Built: " . $row["year_built"] . "<br>";
	}
}


if ($bedrooms || $houses) {
}

$result = mysql_query("SELECT SUM(bedrooms) as cnt FROM house_data AS H, location_data AS L WHERE H.zpid=L.zpid AND ((ACOS(SIN($latitude * PI() / 180) * SIN(latitude * PI() / 180) + COS($latitude * PI() / 180) * COS(latitude * PI() / 180) * COS(($longitude - longitude ) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)<=$range");

$row = mysql_fetch_array($result);
if (!is_null($row["cnt"])) {
	echo $html . "Surrounding Bedrooms: " . $row["cnt"];
} else {
	echo 0;	
}
mysql_close($con);
?>
