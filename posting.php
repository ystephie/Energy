<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet/energy.css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Residential Data Mapping Tool</title>
</head>
<body style="background: rgb(255, 255, 255) url(images/012008_background.jpg) repeat scroll top left; margin: 0pt;">
<!--
<div id="options">
   <h3 align="center"><br><br></h3>
   		
</div>
-->
<div id="main_result">
	<h1 align="center">Residential Data Mapping Tool </h1>
	<p align="center"> Results are in</p>
    <center><a href="index.html">Lets do it again!</a></center>
	<div align="center" class="result">
	
<?php
$con = mysql_connect("lager.cs.berkeley.edu","jeffrey.nieh","housedata");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("housedata", $con);

$result = mysql_query("SELECT * FROM house_data as H, location_data as L LEFT JOIN energy_consump as E ON L.zpid=E.zpid WHERE H.zpid=L.zpid AND longitude=$longitude AND latitude=$latitude");
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
				<th>Annual Energy (kWh)</th>
			</tr>
		";
		$first=false;
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
			<td>" . $row["annual_energy"] . "</td>
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
			<td>" . $row["annual_energy"] . "</td>
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
</div>


</body>
</html>