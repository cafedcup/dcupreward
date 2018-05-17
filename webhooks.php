<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');
$dbconn = pg_connect("postgres://iesaxpzthmoosu:2985fd62590b6987485efe84c96dc5c22a5eb989f6da8e9aa746c30d8395f97a@ec2-54-225-200-15.compute-1.amazonaws.com:5432/d8rrl8e93ni01r")
    or die('Could not connect: ' . pg_last_error());

$access_token = '4Qu7kgrFlDwTEszsj7jmLBOiQZlJ8VPm0Cl6cgPBD68TguSuDKlCO7fb/hQojMXf9elSUa6VQ6iAm0SiVmUxQlRbnOFN38rCMclfZ/2EfLH1O4mzPPEG8RiF3yv99r2+aRHOS+usOHxGQ882dov5owdB04t89/1O/w1cDnyilFU=';
$channelSecret = '225c1cb58f767eaf6b61053c1346727f';
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
$isPhoneText = false;
$isUpdate = false;
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$str_mes = $event['message']['text']);
			$cus_line_id = $event['source']['userId'];

			if (isPhone($str_mes)
			{
				$isPhoneText = true;
				$cus_tel = $str_mes;
			}
			else if (!strcmp(strtolower(substr($str_mes,0,strpos($str_mes,':'))),"update"))
			{
				$isPhoneText = true;
				$isUpdate = true;
				$cus_tel = $str_mes;
			}
			

			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $cus_line_id
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
			#$result = curl_exec($ch);
			curl_close($ch);
			
			echo $result . "\r\n";
		}
	}
}

function isPhone($string) {
    return preg_match("/^[0-9]{10}$/", $string);
}

function insert_customer($dbconn,$cus_line_id,$cus_name){
    $result = pg_insert($dbconn,'dcup_customer_mst',array('cus_id' => '','cus_line_id' => $cus_line_id,'cus_name' => $cus_name)) or die('Query failed: ' . pg_last_error());
    
    pg_free_result($result);     
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
}

function is_custel_exist($dbconn,$cus_line_id){
    $query = "SELECT cus_tel FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $custel = $col_value;
        }
    }
    return $custel != '';
    // Free resultset
    pg_free_result($result);
}

function update_custel($dbconn,$cus_tel,$cus_line_id){
    $result = pg_update($dbconn,'dcup_customer_mst',array('cus_tel' => $cus_tel),array('cus_line_id' => $cus_line_id)) or die('Query failed: ' . pg_last_error());
     // Free resultset  
    pg_free_result($result);
}

$cus_name = 'test';

$hello = 'Hello';
if (!is_lineid_exist($dbconn,$cus_line_id))
{
    #insert_customer($dbconn,$cus_line_id,$cus_name);
    $hello = 'Welcome the frist time';
    $tel = 'Plese enter your phone number';
}
else
{
	if ($isPhoneText)
	{
		#update_custel($dbconn,$cus_tel,$cus_line_id);
		if ($isUpdate)
		{
			$tel = 'Your phone number ' . $cus_tel . '  is updated';
		}
		else
		{
			$tel = 'Your phone number ' . $cus_tel . ' is registed already';
		}
	}
	else
	{
		$tel = 'Sorry it is not your phone number. Plese try again';
	}
}
$response = $bot->getProfile($cus_line_id);
if ($response->isSucceeded()) {
    $profile = $response->getJSONDecodedBody();
    $cus_name = $profile['displayName'];
}

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($hello . ' ' . $cus_name . ' ' . $tel . '.');
$response = $bot->pushMessage($cus_line_id, $textMessageBuilder);
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

pg_close($dbconn);
