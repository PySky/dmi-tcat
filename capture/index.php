<html>
<head>
	<title>DMI Twitter Analytics Status</title>

	<style type="text/css">

	body,html { font-family:Arial, Helvetica, sans-serif; font-size:11px; }

	table {
		/* border: 1px solid black; */
		font-size:12px;
	}

	th {
		background-color: #ccc;
		padding:5px;
	}

	td {
		background-color: #eee;
		padding:5px;
	}

	</style>

</head>

<body>

<h1>DMI Twitter Analytics Status</h1>

<table>

<?php

include "../config.php";						// load base config file
include "../querybins.php";						// load base config file
include "../common/functions.php";				// load base functions file
include "common/functions.php";

dbconnect();

$sql = "SHOW TABLES";
$sqlresults = mysql_query($sql);

$tabledata = array();

while($data = mysql_fetch_assoc($sqlresults)) {

	if(preg_match("/_tweets/",$data["Tables_in_twittercapture"])) {

		$sql2 = "SELECT count(*) as count,MIN(created_at) as mindate,MAX(created_at) as maxdate FROM " . $data["Tables_in_twittercapture"];

		//echo $sql2;

		$sql2results = mysql_query($sql2);

		$tabledata[preg_replace("/_tweets/", "", $data["Tables_in_twittercapture"])] = mysql_fetch_assoc($sql2results);

		//print_r($data2);
	}
}

//print_r($tabledata);

/*

 *
include_once('config.php');

list($count_q,$count_qb) = count_bins($querybins);
print "Active: $count_q queries, $count_qb bins\n";
list($count_q,$count_qb) = count_bins($queryarchives);
print "Archived: $count_q queries, $count_qb bins\n";

function count_bins($query_bins) {
	$count_q = 0;
	foreach($query_bins as $bin => $queries) {
		$count_q+=count(explode(",",$queries));
	}
	return array($count_q,count($query_bins));
}

 */



echo '<tr>';
echo "<th>Binname</th>";
echo "<th>Bindetails</th>";
echo "</tr>";

foreach($querybins as $binname => $binterms) {

	echo "<tr>";
	echo "<td>" . $binname . "</td>";
	echo "<td>";

		echo "<table>";
		echo "<tr>";
		echo "<td>active:</td>";
		echo "<td>yes</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>keywords:</td>";
		echo "<td>" . preg_replace("/,/", ", ", $binterms) . "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>daterange:</td>";
		echo '<td>'.$tabledata[$binname]["mindate"].' - '.$tabledata[$binname]["maxdate"].'</td>';
		echo "</tr>";

		echo "<tr>";
		echo "<td>tweets captured:</td>";
		echo '<td>'.$tabledata[$binname]["count"].'</td>';
		echo "</tr>";
		echo "</table>";

	echo "</td>";
	echo "</tr>";
}

?>

</table>

</body>
</html>