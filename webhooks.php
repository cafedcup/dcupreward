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
		if (($event['type'] == 'message' && ($event['message']['type'] == 'text'|| $event['message']['type'] == 'sticker'))||($event['type'] == 'follow')) {
			// Get text sent
			$str_mes = $event['message']['text'];
			$cus_line_id = $event['source']['userId'];
			$cus_name = get_line_displayName($cus_line_id,$bot);
			if (isPhone($str_mes))
			{
				$isPhoneText = true;
				$cus_tel = $str_mes;
			}
			else if (!strcmp(strtolower(substr($str_mes,0,strpos($str_mes,':'))),"update"))
			{
				$isUpdate = true;
				if (isPhone(substr($str_mes,strpos($str_mes,':')+1)))
				{
					$isPhoneText = true;
					$cus_tel = substr($str_mes,strpos($str_mes,':')+1);
				}
			}
			$str_message = main_function($dbconn,$cus_name,$cus_line_id,$cus_tel,$isPhoneText,$isUpdate);
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $str_message
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages]
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			#echo $result . "\r\n";


		}
	}
}

function isPhone($string) {
    return preg_match("/^[0-9]{10}$/", $string);
}

function insert_customer($dbconn,$cus_line_id,$cus_name){
    $result = pg_insert($dbconn,'dcup_customer_mst',array('cus_id' => '','cus_line_id' => $cus_line_id,'cus_name' => $cus_name)) or die('Query failed: ' . pg_last_error());
    // Free result
    pg_free_result($result);     
}

function update_custel($dbconn,$cus_tel,$cus_line_id){
    $result = pg_update($dbconn,'dcup_customer_mst',array('cus_tel' => $cus_tel),array('cus_line_id' => $cus_line_id)) or die('Query failed: ' . pg_last_error());
    // Free result  
    pg_free_result($result);
}

function is_lineid_exist($dbconn,$cus_line_id){
    $query = "SELECT * FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $line_id = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $line_id != '';
}

function get_cus_id($dbconn,$cus_line_id){
    $query = "SELECT cus_id FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $cus_id = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $cus_id;
}

function get_cus_line_id($dbconn,$cus_tel){
    $query = "SELECT cus_line_id FROM dcup_customer_mst WHERE cus_tel = '" . $cus_tel . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $cus_id = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $cus_id;
}

function is_custel_exist($dbconn,$cus_line_id){
    $query = "SELECT cus_tel FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $custel = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
	return $custel != '';
}

function get_admin_lineid($dbconn){
    $query = "SELECT admin_line_id FROM dcup_admin_mst WHERE id = 1";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $admin_lineid = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $admin_lineid;
}

function is_admin($dbconn,$admin_line_id){
    $query = "SELECT * FROM dcup_admin_mst WHERE admin_line_id = '" . $admin_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $line_id = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
	return $line_id != '';
}

function get_line_displayName($str_line_id,$bot){
	$str_line_displayName = "";
	$response = $bot->getProfile($str_line_id);
	if ($response->isSucceeded()) {
    	$profile = $response->getJSONDecodedBody();
    	$str_line_displayName = $profile['displayName'];
	}
	return $str_line_displayName; 
}


function main_function($dbconn,$cus_name,$cus_line_id,$cus_tel,$isPhoneText,$isUpdate){
	$hello = $cus_name;
	if (is_admin($dbconn,$cus_line_id)){

		$hello = "ยินดีต้อนรับ " . $hello . " คุณคือโคบาน";
		#$cus_line_id = get_cus_line_id($dbconn,$cus_tel);
	}
	else if (!is_lineid_exist($dbconn,$cus_line_id)){
	    insert_customer($dbconn,$cus_line_id,$cus_name);
	    #$hello = 'Welcome ' . $cus_name;
		$hello = "ยินดีต้อนรับ " . $cus_name . "\nเข้าสู่ระบบ dcup reward แตนแต๊น";
	    #$tel = "\nPlease enter your phone number";
		$tel = "\nกรุณาพิมพ์หมายเลขโทรศัพท์ของคุณเพื่อทำการลงทะเบียน";
	}
	else
	{
		if ($isPhoneText)
		{
			if ($isUpdate)
			{
				update_custel($dbconn,$cus_tel,$cus_line_id);
				#$tel = "Your phone number " . $cus_tel . "  is updated";
				$tel = "หมายเลขโทรศัพท์ " . $cus_tel . " ได้อัพเดทลงระบบเรียบร้อย";
			}
			else if (!is_custel_exist($dbconn,$cus_line_id))
			{
				update_custel($dbconn,$cus_tel,$cus_line_id);
				$cur_id = get_cus_id($dbconn,$cus_line_id);
				$str_cus_id = sprintf("D%04s",$cur_id);
				#$tel = "your phone number " . $cus_tel . " is registered already.\nYour ID is " . $str_cus_id;
				$tel = "หมายเลขโทรศัพท์  " . $cus_tel . " ได้ลงทะเบียนแล้วเรียบร้อย\nหมายเลขสมาชิกของคุณคือ " . $str_cus_id;
				
			}
			
			#else
			#{
			#	$tel = "your phone number is exist. If you would like to update Phone number,please type \nupdate:[Phone number] \nEx. update:08xxxxxxxx";
			#}
			
		}
		else if ($isUpdate)
		{
			$tel = "if you would like to update Phone number, please type \nupdate:[Phone number] \nEx. update:08xxxxxxxx";
		}
		else
		{	if (is_custel_exist($dbconn,$cus_line_id))
			{
				$cur_id = get_cus_id($dbconn,$cus_line_id);
				$str_cus_id = sprintf("D%04s",$cur_id);
				#$tel = "You register already.\nYour ID is " . $str_cus_id;
				$tel = "คุณได้ลงทะเบียนเรียบร้อย\nหมายเลขสมาชิกของคุณคือ " . $str_cus_id . "\nโปรดติดตามตอนต่อไปจ้า...";
			}
			else 
			{
				#$tel = "sorry it is not your phone number.\nPlease try again";
				$tel = "พิมพ์ดีๆต๊ะ ^^ บอกว่าพิมพ์เบอร์โทรศัพท์นิ๊ ไอยา";
			}
		}
	}
	return $hello . ', ' . $tel;
}
if (is_admin($dbconn,$cus_line_id)){
	#$hello = "Hi, I can ping you from " . $hello;
	#$push_line_mes = "ไงจ๊ะ, วันนี้คุณได้รับ 1 point ไม่ใช่ใคร DCUP เอง";
	if ($isPhoneText){
		$push_line_id = get_cus_line_id($dbconn,$cus_tel);
		$push_line_mes = "วันนี้คุณได้รับ 1 point". cus_tel;
	}
	else{
		$push_line_id = get_admin_lineid($dbconn);
		$push_line_mes = "อย่าลืมคุณคือโคบาล, ต้องกรอกเบอร์โทรลูกค้าเซ่";
	}
}
else{
	$push_line_id = get_admin_lineid($dbconn);
	$push_line_mes = '[' . $time . "]\nข้อความ: " . $str_mes ."\nจาก: " . $cus_name;
}
$date = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
$time = $date->format('d-m-Y H:i:s');
$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($push_line_mes);
$response = $bot->pushMessage($push_line_id, $textMessageBuilder);
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

pg_close($dbconn);
