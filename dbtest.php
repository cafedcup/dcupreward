<?php
// Connecting, selecting database
#$dbconn = pg_connect("host=localhost dbname=publishing user=www password=foo")
$dbconn = pg_connect("postgres://iesaxpzthmoosu:2985fd62590b6987485efe84c96dc5c22a5eb989f6da8e9aa746c30d8395f97a@ec2-54-225-200-15.compute-1.amazonaws.com:5432/d8rrl8e93ni01r")
    or die('Could not connect: ' . pg_last_error());

// Performing SQL query
#$query = 'SELECT * FROM dcup_customer_tbl';
$line_id = 'U0f8ed013f50650deb6a9e0a95042d4b0';
$query = "SELECT tel FROM dcup_customer_tbl WHERE line_id = '" . $line_id . "'";
echo $query;
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
echo "<table>\n";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    #echo "\t<tr>\n";
    foreach ($line as $col_value) {
        #echo "\t\t<td>$col_value</td>\n";
		$tel = $col_value;
		echo $tel;
    }
    #echo "\t</tr>\n";
}
#echo "</table>\n";
if ($tel == ''){
	echo 'Null';
}
	
// Free resultset
pg_free_result($result);

// Closing connection 
pg_close($dbconn);
?>
