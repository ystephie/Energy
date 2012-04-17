<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet/energy.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Energy Calculator</title>
</head>
<body style="background: rgb(255, 255, 255) url(images/012008_background.jpg) repeat scroll top left; margin: 0pt;">

<h1 align="center"> Results are in </h1>

<h2 align="center">Basic Information</h2>

<div align="center"class="result">
	
<?php
$con = mysql_connect("lager.cs.berkeley.edu","jeffrey.nieh","housedata");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("housedata", $con);

$result = mysql_query("SELECT * FROM house_data H, location_data L WHERE H.zpid=L.zpid AND L.longitude=$longitude AND L.latitude=$latitude");
$first=true;
$alternate=true;
while($row = mysql_fetch_array($result)) {
	if ($first) {
		echo "<table>
			<tr>
				<th>Latitude</th>
           		<th>Longitude</th>
           		<th>Address</th>
				<th>House Size (Sq ft)</th>
           		<th>Lot Size (Sq ft)</th>
            	<th>Bedrooms</th>
            	<th>Bathrooms</th>
            	<th>Year Built</th>
			</tr>
		";
	}
	if ($alternate) {
  		echo "<tr>
        	<td>" . $row["latitude"] . "</td>
            <td>" . $row["longitude"] . "</td>
            <td>" . $row["address"] . "</td>
            <td>" . $row["house_size"] . "</td>
            <td>" . $row["lot_size"] . "</td>
            <td>" . $row["bedrooms"] . "</td>
            <td>" . $row["bathrooms"] . "</td>
            <td>" . $row["year_built"] . "</td>
		</tr>";
	} else {
		echo "<tr class='alt'>
        	<td>" . $row["latitude"] . "</td>
            <td>" . $row["longitude"] . "</td>
            <td>" . $row["address"] . "</td>
            <td>" . $row["house_size"] . "</td>
            <td>" . $row["lot_size"] . "</td>
            <td>" . $row["bedrooms"] . "</td>
            <td>" . $row["bathrooms"] . "</td>
            <td>" . $row["year_built"] . "</td>
		</tr>";
	}
	$alternate=!$alternate;
}
if ($first){
	echo "No results came back for Longitude " . $longitude . " and Latitude " . $latitude;
}
mysql_close($con);
?>
	</table>
</div>

<center><a href="index.html">Lets do it again!</a></center>


</body>
</html>