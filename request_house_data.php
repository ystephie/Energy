<?php
//retrieve post data
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$range = $_POST["radius"];
if (!$range) {
	$range = 0;
}
$bedrooms = $_POST["bedrooms"] == "true"? true: false; 
$houses = $_POST["houses"] == "true"? true: false;
$r_units = $_POST["r_units"] == "true"? true: false;
$address = $_POST["address"] == "true"? true: false;
$house_size = $_POST["house_size"] == "true"? true: false;
$lot_size = $_POST["lot_size"] == "true"? true: false;
$br = $_POST["br"] == "true"? true: false;
$bathrooms = $_POST["bathrooms"] == "true"? true: false;
$year_built = $_POST["year_built"] == "true"? true: false;
$s_units = $_POST["s_units"] == "true"? true: false;
$s_energy = $_POST["s_energy"] == "true"? true: false;

//connect to sql server
$con = mysql_connect("lager.cs.berkeley.edu","jeffrey.nieh","housedata");
if (!$con) {
	die('Could not connect: ' . mysql_error());
}
$html = "";
mysql_select_db("housedata", $con);

//logic

if ($house_size || $address || $lot_size || $br || $bathrooms || $year_built || $s_units || $s_energy) {
	$result = mysql_query("SELECT * FROM house_data as H, location_data as L, house_data_more_facts AS M, energy_consump as E WHERE H.zpid=L.zpid and H.zpid=M.zpid and H.zpid=E.zpid ORDER BY ((ACOS(SIN($latitude * PI() / 180) * SIN(latitude * PI() / 180) + COS($latitude * PI() / 180) * COS(latitude * PI() / 180) * COS(($longitude - longitude ) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) LIMIT 1");
	
	$html .= "<div id='s_info'><b>Info For the Closest House: </b><br></div>";
	
	$row = mysql_fetch_array($result);
	if ($address && !is_null($row["address"])) {
		$html .= "Address: " . $row["address"] . "<br>";
	}  
	if ($house_size && !is_null($row["house_size"])) {
		$html .= "House Size: " . $row["house_size"] . " sq. ft.<br>";
	} 
	if ($lot_size && !is_null($row["lot_size"])) {
		$html .= "Lot Size: " . $row["lot_size"] . " sq. ft.<br>";
	}
	if ($br && !is_null($row["bedrooms"])) {
		$html .= "Bedrooms: " . $row["bedrooms"] . "<br>";
	}  
	if ($bathrooms && !is_null($row["bathrooms"])) {
		$html .= "Bathrooms: " . $row["bathrooms"] . "<br>";
	}  
	if ($year_built && !is_null($row["year_built"])) {
		$html .= "Year Built: " . $row["year_built"] . "<br>";
	}
	if ($s_units && !is_null($row["c_unit_count"])) {
		$html .= "Units: " . $row["c_unit_count"] . "<br>";
	}
	if ($s_energy && !is_null($row["annual_energy"])) {
		$html .= "Est. Annual Energy Consumption: " . $row["annual_energy"] . " kWh<br>";
	}
}


if ($bedrooms || $houses || $r_units) {
	$result = mysql_query("SELECT SUM(bedrooms) as bedroom_cnt, count(H.zpid) as house_cnt, SUM(c_unit_count) as c_units FROM house_data AS H, location_data AS L , house_data_more_facts AS M WHERE H.zpid=L.zpid AND H.zpid=M.zpid AND ((ACOS(SIN($latitude * PI() / 180) * SIN(latitude * PI() / 180) + COS($latitude * PI() / 180) * COS(latitude * PI() / 180) * COS(($longitude - longitude ) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)<=$range");
	
	$html .= "<div id='r_info'><b>Range Info (within a " . $range . " mi radius): </b><br></div>";
	
	$row = mysql_fetch_array($result);
	if ($bedrooms) {
		$html .= "Bedrooms: " . $row["bedroom_cnt"] . "<br>";
	}
	if ($houses) {
		$html .= "Buildings: " . $row["house_cnt"] . "<br>";
	}
	if ($r_units) {
		$html .= "Units: " . $row["c_units"] . "<br>";
	}
}

if (!is_null($html)) {
	echo $html;
} else {
	echo "No results are available for the information you requested";	
}
mysql_close($con);
?>
