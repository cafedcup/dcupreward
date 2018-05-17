<?php
// Connecting, selecting database
#$dbconn = pg_connect("host=localhost dbname=publishing user=www password=foo")
$dbconn = pg_connect("postgres://iesaxpzthmoosu:2985fd62590b6987485efe84c96dc5c22a5eb989f6da8e9aa746c30d8395f97a@ec2-54-225-200-15.compute-1.amazonaws.com:5432/d8rrl8e93ni01r")
    or die('Could not connect: ' . pg_last_error());
$cus_line_id = '2';
$cus_tel = '888';
if (!is_lineid_exist($dbconn,$cus_line_id))
{
    insert_customer($dbconn,'',$cus_line_id,$cus_tel);
}
else {
    echo 'cus_line_id is exist';
}

function insert_customer($dbconn,$cus_id,$cus_line_id,$cus_tel){
    $result = pg_insert($dbconn,'dcup_customer_mst',array('cus_id' => '','cus_line_id' => $cus_line_id,'cus_tel' => $cus_tel)) or die('Query failed: ' . pg_last_error());
    
    pg_free_result($result);
    // Closing connection 
     
}

function getmax_id($dbconn){
    $query = "SELECT max(cus_id) FROM dcup_customer_mst";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $max_id = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    // Closing connection 
    return $max_id;
}

function is_lineid_exist($dbconn,$cus_line_id){
    $query = "SELECT * FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $line_id = $col_value;
        }
    }
    return $line_id != '';
    // Free resultset
    pg_free_result($result);
    // Closing connection 
}


#$result = pg_update($dbconn,'dcup_customer_tbl',array('entry_count' => $entry),array('name' => $username)) or die('Query failed: ' . pg_last_error());

// Printing results in HTML

 pg_close($dbconn);  
?>
