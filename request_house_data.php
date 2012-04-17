<?php
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
$range = .5;

$con = mysql_connect("lager.cs.berkeley.edu","jeffrey.nieh","housedata");
if (!$con) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("housedata", $con);

$nearby_listings = mysql_query("SELECT zpid,((ACOS(SIN(".$latitude." * PI() / 180) * SIN(latitude * PI() / 180) + COS(".$latitude.
	" * PI() / 180) * COS(latitude * PI() / 180) * COS((".$longitude." - longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS 'distance' 
	FROM location_data WHERE 'distance'<=".$range);
$query = "SELECT SUM(*) AS 'cnt' FROM house_data WHERE";
$first = true;
while($row = mysql_fetch_array($nearby_listings)) { 
	if ($first) {
		$query=$query." zpid=".$row["zpid"];
		$first = false;
	} else {
		$query=$query." or zpid=".$row["zpid"];
	}
}
$result = mysql_query(query);
$row = mysql_fetch_row($result);
echo $row["cnt"];
?>
