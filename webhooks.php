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
			$point = 1;
			if (isPhone($str_mes)){
				$isPhoneText = true;
				$cus_tel = $str_mes;
			}
			else if (!strcmp(strtolower(substr($str_mes,0,strpos($str_mes,':'))),"update")){
				$isUpdate = true;
				if (isPhone(substr($str_mes,strpos($str_mes,':')+1)))
				{
					$isPhoneText = true;
					$cus_tel = substr($str_mes,strpos($str_mes,':')+1);
				}
			}
			else if (isPhone(substr($str_mes,0,strpos($str_mes,',')))){
				$isPhoneText = true;
				$cus_tel = substr($str_mes,0,strpos($str_mes,','));
				$point = substr($str_mes,strpos($str_mes,',')+1);
				
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
function isPoint ($point){
	return preg_match("/^[1-9]{1}$/", $point);
}

function get_reward_message($point,$reward){
	$str_message = "\nขณะนี้คุณไม่มีแต้ม รีบมาสะสมนะคะ";
	if ($point != 0){
		$str_message = "\nขณะนี้คุณมี " . $point . " แต้ม ";
	}
	if ($reward != 0){
		$str_message = $str_message . "\nมีสิทธิพิเศษ ". $reward ." สิทธิ";
	}
	elseif ($point != 0){
		$str = " อีก " . (10 - $point) . " แต้ม \n=>ห็นทีพรุ่งนี้ต้องซ้ำ";
		if ($point >= 3 && $point < 5)
			$str = " อีก " . (10 - $point) . " แต้ม \n=>สู้ต่อไปเป็นกำลังใจให้นะคะ";
		elseif ($point >= 5 && $point < 7)
			$str = " อีก " . (10 - $point) . " แต้ม \n=>พรุ่งนี้ต้องพาเดอะแก๊งมาโดน";
		elseif ($point >= 7 && $point < 9)
			$str = " อีก " . (10 - $point) . " แต้ม \n=>ไม่ธรรมดานะคะ";
		elseif ($point == 9)
			$str = " อีก แค่ 1 แต้มเท่านั้น !!!";
		$str_message = $str_message . $str;
	}
	/*
	if (($point == 0) && ($reward == 0 )){
		$str_message = "\nขณะนี้ไม่มีแต้ม รีบมาสะสมเพิ่มนะคะ";
	}
	*/
	return $str_message;
}
function get_datetime(){
	$date = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
	#$time = $date->format('d-m-Y H:i:s');
	$time = $date->format('d-m-Y H:i');
	return $time;
}
function get_date(){
	$date = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
	#$time = $date->format('d-m-Y H:i:s');
	$time = $date->format('d-m-Y');
	return $time;
}

function insert_customer($dbconn,$cus_line_id,$cus_name){
    $result = pg_insert($dbconn,'dcup_customer_mst',array('cus_id' => '','cus_line_id' => $cus_line_id,'cus_name' => $cus_name)) or die('Query failed: ' . pg_last_error());
    // Free result
    pg_free_result($result);     
}

function insert_reward($dbconn,$cus_id,$point){
    #$result = pg_insert($dbconn,'dcup_reward_tbl',array('id' => '','customer_id' => $cus_id,'point_count' => 1,'valid' => true, 'reward_start_date' => get_date())) or die('Query failed: ' . pg_last_error());
	$result = pg_insert($dbconn,'dcup_reward_tbl',array('id' => '','customer_id' => $cus_id,'point_count' => $point,'valid' => true)) or die('Query failed: ' . pg_last_error());
	// Free result
    pg_free_result($result);     
}

function update_custel($dbconn,$cus_tel,$cus_line_id){
    $result = pg_update($dbconn,'dcup_customer_mst',array('cus_tel' => $cus_tel),array('cus_line_id' => $cus_line_id)) or die('Query failed: ' . pg_last_error());
    // Free result  
    pg_free_result($result);
}

function update_reward($dbconn,$cus_id,$point_count,$valid){
    $result = pg_update($dbconn,'dcup_reward_tbl',array('point_count' => $point_count,'valid' => $valid),array('customer_id' => $cus_id,'valid' => true)) or die('Query failed: ' . pg_last_error());
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

function get_cus_name($dbconn,$cus_line_id){
    $query = "SELECT cus_name FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $cus_name = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $cus_name;
}

function get_cus_line_id($dbconn,$cus_tel){
    $query = "SELECT cus_line_id FROM dcup_customer_mst WHERE cus_tel = '" . $cus_tel . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $cus_line_id = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $cus_line_id;
}

function get_point($dbconn,$cus_id){
    $query = "SELECT point_count FROM dcup_reward_tbl Where valid = true and customer_id = '" . $cus_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $point = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
	return $point;
	
}function get_reward($dbconn,$cus_id){
    $query = "SELECT count(id) FROM dcup_reward_tbl Where valid = false and reward_use_date is null and customer_id = '" . $cus_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $reward = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
	return $reward;
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

function is_reward_exist($dbconn,$cus_id){
    $query = "SELECT customer_id FROM dcup_reward_tbl Where valid = true and customer_id = '" . $cus_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $cusid = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
	return $cusid != '';
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
		$hello = $hello . " คุณคือโคบาน";
		$cus_line_id = get_cus_line_id($dbconn,$cus_tel);
	}
	else if (!is_lineid_exist($dbconn,$cus_line_id)){
	    insert_customer($dbconn,$cus_line_id,$cus_name);
	    #$hello = 'Welcome ' . $cus_name;
		$hello = "ยินดีต้อนรับ " . $cus_name . "\nเข้าสู่ dcup Reward";
	    #$tel = "\nPlease enter your phone number";
		$tel = "\nกรุณาพิมพ์หมายเลขโทรศัพท์\nเพื่อทำการลงทะเบียน";
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
				$tel = "หมายเลขโทรศัพท์  " . $cus_tel . " ได้ลงทะเบียนเรียบร้อย\nรหัสสมาชิกของคุณคือ " . $str_cus_id;
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
				$cus_id = get_cus_id($dbconn,$cus_line_id);
				$str_cus_id = sprintf("D%04s",$cus_id);
				#$tel = '';
				$point = get_point($dbconn,$cus_id);
				$reward = get_reward($dbconn,$cus_id);
				$tel = get_reward_message($point,$reward);
				/*
				if ($point != 0){
					$tel = "ขณะนี้คุณมี " . $point . " แต้ม ";
				}
				if ($reward != 0){
					$tel = $tel . "และฟรี ". $reward ." แก้ว";
				}
				else{
					$tel = $tel . "สู้ๆนะคะ อีก " . (10 - $point) . " แต้ม";
				}
				if (($point == 0) && ($reward == 0 )){
					$tel = "\nขณะนี้ยังไม่มีแต้ม รีบมาสะสมกันนะคะ";
				}
				*/
				#$tel = "คุณนี้มี " . $point . " แต้ม และฟรี ". $reward ." แก้ว";
				#$tel = "You register already.\nYour ID is " . $str_cus_id;
				#$tel = "คุณได้ลงทะเบียนเรียบร้อย\nหมายเลขสมาชิกของคุณคือ " . $str_cus_id . "\nโปรดติดตามตอนต่อไปจ้า...";
				$tel = "[".$str_cus_id . "] " . $tel;
			}
			else 
			{
				#$tel = "sorry it is not your phone number.\nPlease try again";
				$tel = "คุณยังไม่ได้ทำการลงทะเบียน\nกรุณาพิมพ์หมายเลขโทรศัพท์ 10 หลัก อีกครั้งนะคะ";
			}
		}
	}
	return $hello . ' ' . $tel;
}
if (is_admin($dbconn,$cus_line_id)){
	#$hello = "Hi, I can ping you from " . $hello;
	#$push_line_mes = "ไงจ๊ะ, วันนี้คุณได้รับ 1 point ไม่ใช่ใคร DCUP เอง";
	if ($isPhoneText){
		$push_line_id = get_cus_line_id($dbconn,$cus_tel);
		$cus_name = get_cus_name($dbconn,$push_line_id);
		$cus_id = get_cus_id($dbconn,$push_line_id);
		
		$str_cus_id = sprintf("D%04s",$cus_id);
		$point_cur = get_point($dbconn,$cus_id);
		$point_new = $point_cur + $point;
		$push_line_mes = "วันนี้คุณได้รับ ". $point . " แต้ม\n";
		if (!is_reward_exist($dbconn,$cus_id)){
			insert_reward($dbconn,$cus_id,$point_new);
			#$push_line_mes = "วันนี้คุณได้รับ ". $point . " แต้ม";
		}
		else{
			#$point_cur = get_point($dbconn,$cus_id);
			#$point_new = $point_cur + $point;
			if ((floor($point_new / 10)) == 0){
				update_reward($dbconn,$cus_id,$point_new,true);
				#$reward = get_reward($dbconn,$cus_id);
				/*
				$push_line_mes = "วันนี้คุณได้ " . $point . " แต้ม, ขณะนี้มี " . $point_new . " แต้ม";
				if ($reward != 0){
					$push_line_mes = $push_line_mes . "และฟรี ". $reward ." แก้ว";
				}
				*/
			}
			else{
				update_reward($dbconn,$cus_id,10,false);
				#$push_line_mes = "วันนี้คุณได้ " . $point . " แต้ม, ขณะนี้มี " . ($point_new % 10) . " แต้ม และฟรีเพิ่ม 1 แก้ว";
				insert_reward($dbconn,$cus_id,($point_new % 10));
			}
		}
		
		$reward = get_reward($dbconn,$cus_id);
		$str_message = get_reward_message(($point_new % 10),$reward);
		$str_message = $cus_name . "[" . $str_cus_id . "] " . $str_message;
		$push_line_mes = $push_line_mes . $str_message;
	}
	else{
		$push_line_id = get_admin_lineid($dbconn);
		$push_line_mes = "อย่าลืมคุณคือโคบาล, ต้องกรอกเบอร์โทรลูกค้าเซ่";
	}
}
else{
	$time = get_datetime();
	$push_line_id = get_admin_lineid($dbconn);
	$push_line_mes = '[' . $time . "]\nข้อความ: " . $str_mes ."\nจาก: " . $cus_name;
	#$push_line_mes = "]\nข้อความ: " . $str_mes ."\nจาก: " . $cus_name;
}

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($push_line_mes);
$response = $bot->pushMessage($push_line_id, $textMessageBuilder);
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

pg_close($dbconn);
