<?php // callback.php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');
///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

$dbconn = pg_connect("postgres://iesaxpzthmoosu:2985fd62590b6987485efe84c96dc5c22a5eb989f6da8e9aa746c30d8395f97a@ec2-54-225-200-15.compute-1.amazonaws.com:5432/d8rrl8e93ni01r")
    or die('Could not connect: ' . pg_last_error());
$access_token = '4Qu7kgrFlDwTEszsj7jmLBOiQZlJ8VPm0Cl6cgPBD68TguSuDKlCO7fb/hQojMXf9elSUa6VQ6iAm0SiVmUxQlRbnOFN38rCMclfZ/2EfLH1O4mzPPEG8RiF3yv99r2+aRHOS+usOHxGQ882dov5owdB04t89/1O/w1cDnyilFU=';
$channelSecret = '225c1cb58f767eaf6b61053c1346727f';

$httpClient = new CurlHTTPClient($access_token);
$bot = new LINEBot($httpClient, ['channelSecret' => $channelSecret]);

// Get POST body content
$content = file_get_contents('php://input');

// Parse JSON
$events = json_decode($content, true);

// Init variable
$isPhoneText = false;
$isUpdate = false;
$isGivePoint = false;
$isUseReward = false;
$isCusIDText = false;

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		$cus_line_id = $event['source']['userId'];
		$replyToken = $event['replyToken'];
		$sourceType = $event['source']['type'];

		if(isset($event) && array_key_exists('message',$event)){
        	$typeMessage = $event['message']['type'];
        	$str_mes = $event['message']['text'];
        	$idMessage = $event['message']['id'];	
    	}
		if(isset($event) && array_key_exists('postback',$event)){
			$is_postback = true;
			$dataPostback = NULL;
			parse_str($event['postback']['data'],$dataPostback);
			$paramPostback = NULL;
			if(array_key_exists('params',$event['postback'])){
				if(array_key_exists('date',$event['postback']['params'])){
		    		$paramPostback = $event['postback']['params']['date'];
				}
				if(array_key_exists('time',$event['postback']['params'])){
		    		$paramPostback = $event['postback']['params']['time'];
				}
				if(array_key_exists('datetime',$event['postback']['params'])){
		    		$paramPostback = $event['postback']['params']['datetime'];
				}                       
			}
	    }   
	    if(!is_null($is_postback)){
	        $textReplyMessage = "ข้อความจาก Postback Event Data = ";
	        if(is_array($dataPostback)){
	            $textReplyMessage.= json_encode($dataPostback);
	        }
	        if(!is_null($paramPostback)){
	            $textReplyMessage.= " \r\nParams = ".$paramPostback;
	        }
	        $replyData = new TextMessageBuilder($textReplyMessage);
	        #$bot->replyMessage($replyToken, $replyData);
	    }
		// Reply only when message sent is in 'text' format
		if (($event['type'] == 'message' && ($event['message']['type'] == 'text'|| $event['message']['type'] == 'sticker'))||($event['type'] == 'follow')) {
			// Get text sent
			#$str_mes = $event['message']['text'];
			// Get line ID
			#$cus_line_id = $event['source']['userId'];
			// Get display Name
			$cus_name = get_line_displayName($cus_line_id,$bot);
			$point = 1;

			// Get replyToken
			
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
				if(isPoint(substr($str_mes,strpos($str_mes,',')+1))){
					$point = substr($str_mes,strpos($str_mes,',')+1);
					$isGivePoint = true;
				}
				if(isReward(substr($str_mes,strpos($str_mes,',')+1))){
					$isUseReward = true;
				}
			}
			else if (isCusID(sprintf("%04s",substr($str_mes,0,strpos($str_mes,','))))){
				$isCusIDText = true;
				$cus_id = substr($str_mes,0,strpos($str_mes,','));
				if(isPoint(substr($str_mes,strpos($str_mes,',')+1))){
					$point = substr($str_mes,strpos($str_mes,',')+1);
					$isGivePoint = true;
				}
				if(isReward(substr($str_mes,strpos($str_mes,',')+1))){
					$isUseReward = true;
				}
			}

			if (is_admin($dbconn,$cus_line_id)){
				$str_message = "Hi admin " . $cus_name;
				#$textMessageBuilder = new TextMessageBuilder($str_message);
				#$bot->replyMessage($replyToken, $textMessageBuilder);
			}
			else if (!is_lineid_exist($dbconn,$cus_line_id)){
	    		insert_customer($dbconn,$cus_line_id,$cus_name);
	    		#$hello = 'Welcome ' . $cus_name;
				$str_message = "ยินดีต้อนรับ " . $cus_name . "\nเข้าสู่ dcup Reward";
	    		#$tel = "\nPlease enter your phone number";
				$str_message .= "\nกรุณาพิมพ์หมายเลขโทรศัพท์\nเพื่อทำการลงทะเบียน";
				$textMessageBuilder = new TextMessageBuilder($str_message);
				$bot->replyMessage($replyToken, $textMessageBuilder);
			}
			else if (!strcmp($str_mes,"สอบถามข้อมูลส่วนตัวที่ CAFE' DCUP")){
				if (is_custel_exist($dbconn,$cus_line_id)){
					/*
					$str_confirm = "คุณต้องการเพิ่มวันเกิดของคุณหรือไม่";
					$action_yes = http_build_query(array('action'=>'yes','item'=>100));
					#$action_yes = 'Yes';
					$messageBuilder_yes = new MessageTemplateActionBuilder('Yes','Yes',$action_yes);
					$messageBuilder_no = new MessageTemplateActionBuilder('No','NO');
					$templateBuilder = new ConfirmTemplateBuilder($str_confirm,array($messageBuilder_yes,$messageBuilder_no));
					$messageBuilder = new TemplateMessageBuilder('Confirm Template',$templateBuilder);
					$bot->replyMessage($replyToken, $messageBuilder);
					*/
					$cus_id = get_cus_id($dbconn,$cus_line_id);
					$str_cus_id = sprintf("D%04s",$cus_id);
					$cus_tel = get_cus_tel($dbconn,$cus_line_id);
					$str_message = "ข้อมูลส่วนตัวของ ". $cus_name ." :\n";
					$str_message = $str_message . "• รหัสสมาชิก " . $str_cus_id . "\n";
					$str_message = $str_message . "• หมายเลขโทรศัพท์ " . $cus_tel;
				}
				else{
					$str_message = "!!คุณยังไม่ได้ทำการลงทะเบียน\n• กรุณาพิมพ์หมายเลขโทรศัพท์ 10 หลัก นะคะ";
				}
				$textMessageBuilder = new TextMessageBuilder($str_message);
				$bot->replyMessage($replyToken, $textMessageBuilder);

			}
			else if (!strcmp($str_mes,"สอบถามคะแนนและสิทธิพิเศษที่ CAFE' DCUP")){
				if (is_custel_exist($dbconn,$cus_line_id)){
					$cus_id = get_cus_id($dbconn,$cus_line_id);
					$point = get_point($dbconn,$cus_id);
					$reward = get_reward($dbconn,$cus_id);
					#$str_reward = get_reward_message($point,$reward);
					#$str_message = "สิทธิพิเศษของ ". $cus_name ." :\n" . $str_reward;
					$str_message = get_reward_picture($point,$reward);
					$messageBuilder = new TemplateMessageBuilder('DCUP Reward',$str_message);
				}
				else{
					$str_message = "!!คุณยังไม่ได้ทำการลงทะเบียน\n• กรุณาพิมพ์หมายเลขโทรศัพท์ 10 หลัก นะคะ";
					$messageBuilder = new TextMessageBuilder($str_message);
				}				
				$bot->replyMessage($replyToken, $messageBuilder);
			}
			else if (!strcmp($str_mes,"ขอที่อยู่ CAFE' DCUP")){
				$str_message = "ที่อยู่ CAFE' DCUP:\n";
				$str_message = $str_message . "ปั๊มน้ำมัน CALTEX เชิงทะเล\n";
				$str_message = $str_message . "48/207  ม.4 ต.เชิงทะเล อ.ถลาง จ.ภูเก็ต 83110";
			}
			else if (!strcmp($str_mes,"ขอติดต่อ CAFE' DCUP")){
				$str_message = "ติดต่อ CAFE' DCUP:\n";
				$str_message = $str_message . "087-384-1599\n";
				$str_message = $str_message . "FB: https://www.facebook.com/cafeDCUP/";
			}
			
			else if (!strcmp($str_mes,"ขอเมนู")){
				$ImageUrl1 = 'https://cafedcup.herokuapp.com/pictures/1.jpg';
				$ImageUrl2 = 'https://cafedcup.herokuapp.com/pictures/2.jpg';
				$ImageUrl3 = 'https://cafedcup.herokuapp.com/pictures/3.jpg';
				$ImageUrl4 = 'https://cafedcup.herokuapp.com/pictures/4.jpg';
				$ImageUrl5 = 'https://cafedcup.herokuapp.com/pictures/5.jpg';

				
				$ImageActionUrl = 'https://www.facebook.com/pg/cafeDCUP/photos/?tab=album&album_id=748495601994547';
				$ImageBuilder1 = new ImageCarouselColumnTemplateBuilder($ImageUrl1,new UriTemplateActionBuilder("CAFE' DCUP",$ImageActionUrl));
				$ImageBuilder2 = new ImageCarouselColumnTemplateBuilder($ImageUrl2,new UriTemplateActionBuilder("CAFE' DCUP",$ImageActionUrl));
				$ImageBuilder3 = new ImageCarouselColumnTemplateBuilder($ImageUrl3,new UriTemplateActionBuilder("CAFE' DCUP",$ImageActionUrl));
				$ImageBuilder4 = new ImageCarouselColumnTemplateBuilder($ImageUrl4,new UriTemplateActionBuilder("CAFE' DCUP",$ImageActionUrl));
				$ImageBuilder5 = new ImageCarouselColumnTemplateBuilder($ImageUrl5,new UriTemplateActionBuilder("CAFE' DCUP",$ImageActionUrl));
				
				$messageBuilder = new ImageCarouselTemplateBuilder(array($ImageBuilder1,$ImageBuilder2,$ImageBuilder3,$ImageBuilder4,$ImageBuilder5));
				
				$replyData = new TemplateMessageBuilder('Image Carousel',$messageBuilder);
				$bot->replyMessage($replyToken, $replyData);
			}
			else
			{
				$str_message = main_function($dbconn,$cus_name,$cus_line_id,$cus_tel,$isPhoneText,$isUpdate,$replyToken);
				$textMessageBuilder = new TextMessageBuilder($str_message);
				$bot->replyMessage($replyToken, $textMessageBuilder);			
			}
		
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
			
			/*
			# Start Test
			$url = 'https://api.line.me/v2/bot/richmenu';
			$bounds = [
				'x' => 0,
				'y' => 0,
				'width' => 2500,
				'height' => 1686
			];
			$action = [
				'type' => 'message',
				'text' => 'test'
			];
			
			$area = [
				'bounds' => [$bounds],
				'action' => [$action]
			];	
			$data = [
				'size' => {width: 2500, heigth: 1686},
				'selected' => true,
				'name' => "richmenu",
				'chatBarText' => "Tap to open",
				'area' => [$area],
				
			];
			# End Test
			*/
			/*
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
			*/
						/*
			if ($str_message == 'create'){
				$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder(createNewRichmenu($access_token));
				$bot->replyMessage($replyToken, $textMessageBuilder);
			}
			$result = getListOfRichmenu($access_token));

			$result = getListOfRichmenu($access_token));
	        if(isset($result['richmenus']) && count($result['richmenus']) > 0) {
	          $builders = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
	          $columns = Array();

	          for($i = 0; $i < count($result['richmenus']); $i++) {
	            $richmenu = $result['richmenus'][$i];
	            $actionArray = array();
	            array_push($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder ('upload image', 'upload::' . $richmenu['richMenuId']));
	            array_push($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder ('delete', 'delete::' . $richmenu['richMenuId']));
	            array_push($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder ('link', 'link::' . $richmenu['richMenuId']));
	            $column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder (null,$richmenu['richMenuId'],null,$actionArray);
	            array_push($columns, $column);
	            if($i == 4 || $i == count($result['richmenus']) - 1) {
	              $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder('Richmenu',new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns));
	              $builders->add($builder);
	              unset($columns);
	              $columns = Array();
	            }

	          }
	          $bot->replyMessage($replyToken, $builders);
	          
        	}
			*/
			#$replyData = new ConfirmTemplateBuilder('Confirm template builder',array(new MessageTemplateActionBuilder('Yes','YES'),new MessageTemplateActionBuilder('No','NO')));
			#$textMessageBuilder = new TemplateMessageBuilder('Confirm Template',$replyData);

			#$textMessageBuilder = new TextMessageBuilder($str_message);
			#$bot->replyMessage($replyToken, $replyData);
			#echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
		}
	}
}

function isCusID($string) {
    return preg_match("/^[0-9]{4}$/", $string);
}
function isPhone($string) {
    return preg_match("/^[0-9]{10}$/", $string);
}
function isPoint($string){
	return preg_match("/^[1-9]{1}$/", $string);
}
function isReward($string){
	return preg_match("/^[@]{1}$/", $string);
}

function get_reward_message($point,$reward){
	$str_point_message = "• คุณยังไม่มีแต้ม รีบมาสะสมนะคะ";
	if ($point != 0){
		$str_point_message = "• คุณมี " . $point . " แต้ม\n";
		$str_reward_message = "• ไม่มีสิทธิพิเศษ";
	}
	if ($reward != 0){
		$str_reward_message = "• มีสิทธิพิเศษ ". $reward ." สิทธิ";
		if($point == 0){
			$str_point_message = "";
		}
	}
	$str_message = $str_point_message . $str_reward_message;
	/*
	elseif ($point != 0){
		$str = " อีก " . (10 - $point) . " แต้ม \n=>พรุ่งนี้มีซ้ำ";
		if ($point >= 3 && $point < 5)
			$str = " อีก " . (10 - $point) . " แต้ม \n=>เป็นกำลังใจให้นะคะ";
		elseif ($point >= 5 && $point < 7)
			$str = " อีก " . (10 - $point) . " แต้ม \n=>ขอบคุณลูกค้าใจดี";
		elseif ($point >= 7 && $point < 9)
			$str = " อีก " . (10 - $point) . " แต้ม \n=>พรุ่งนี้มีเฮ";
		elseif ($point == 9)
			$str = " อีก แค่ 1 แต้มเท่านั้น !!!";
		$str_message = $str_message . $str;
	}
	*/
	return $str_message;
}
function get_reward_picture($point,$reward){
	$ImageUrlPoint = 'https://cafedcup.herokuapp.com/pictures/points/' . $point . '.jpg';
	$ImageUrlReward = 'https://cafedcup.herokuapp.com/pictures/reward/reward.jpg';
	$ImageActionUrl = 'https://www.facebook.com/pg/cafeDCUP/photos/?tab=album&album_id=748495601994547';
    /*
    $actionBuilder = array(new MessageTemplateActionBuilder('Message Template','This is Text'));
    $ColTempBuilder1 = new CarouselColumnTemplateBuilder('My Points','Test',$ImageUrlPoint,null);
    $messageBuilder = new CarouselTemplateBuilder(array($ColTempBuilder1));
	*/
	#$ImageBuilder1 = new ImageCarouselColumnTemplateBuilder($ImageUrlPoint,new UriTemplateActionBuilder("My Points",$ImageActionUrl));
	$ImageBuilder1 = new ImageCarouselColumnTemplateBuilder($ImageUrlPoint,new PostbackTemplateActionBuilder("My Points",http_build_query(array('action'=>'getPoints')),'สอบถามคะแนน'));
	$messageBuilder = new ImageCarouselTemplateBuilder(array($ImageBuilder1));
	
	switch($reward){
		case '1':
			#$ImageBuilder2 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new UriTemplateActionBuilder("My Reward",$ImageActionUrl));
			$ImageBuilder2 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new PostbackTemplateActionBuilder("My Reward",http_build_query(array('action'=>'getReward')),'สอบถามสอบถามสิทธิพิเศษ'));
			$messageBuilder = new ImageCarouselTemplateBuilder(array($ImageBuilder1,$ImageBuilder2));
			break;
		case '2':
			$ImageBuilder2 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new UriTemplateActionBuilder("My Reward",http_build_query(array('action'=>'getReward')),'สอบถามสอบถามสิทธิพิเศษ'));
			$ImageBuilder3 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new UriTemplateActionBuilder("My Reward",http_build_query(array('action'=>'getReward')),'สอบถามสอบถามสิทธิพิเศษ'));
			$messageBuilder = new ImageCarouselTemplateBuilder(array($ImageBuilder1,$ImageBuilder2,$ImageBuilder3));
			break;
		case '3':
			$ImageBuilder2 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new UriTemplateActionBuilder("My Reward",http_build_query(array('action'=>'getReward')),'สอบถามสอบถามสิทธิพิเศษ'));
			$ImageBuilder3 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new UriTemplateActionBuilder("My Reward",http_build_query(array('action'=>'getReward')),'สอบถามสอบถามสิทธิพิเศษ'));
			$ImageBuilder4 = new ImageCarouselColumnTemplateBuilder($ImageUrlReward,new UriTemplateActionBuilder("My Reward",http_build_query(array('action'=>'getReward')),'สอบถามสอบถามสิทธิพิเศษ'));
			$messageBuilder = new ImageCarouselTemplateBuilder(array($ImageBuilder1,$ImageBuilder2,$ImageBuilder3,$ImageBuilder4));
			break;
	}
	
	return $messageBuilder;
}
function get_datetime(){
	$date = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
	$time = $date->format('Y-m-d H:i:s');
	return $time;
}
function get_date(){
	$date = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
	$time = $date->format('Y-m-d');
	return $time;
}

function insert_customer($dbconn,$cus_line_id,$cus_name){
    $result = pg_insert($dbconn,'dcup_customer_mst',array('cus_id' => '','cus_line_id' => $cus_line_id,'cus_name' => $cus_name, 'cus_regdate' => get_date())) or die('Query failed: ' . pg_last_error());
    // Free result
    pg_free_result($result);     
}

function insert_reward($dbconn,$cus_id,$point,$valid){
    $result = pg_insert($dbconn,'dcup_reward_tbl',array('id' => '','customer_id' => $cus_id,'point_count' => $point,'valid' => $valid, 'reward_start_date' => get_datetime())) or die('Query failed: ' . pg_last_error());
	#$result = pg_insert($dbconn,'dcup_reward_tbl',array('id' => '','customer_id' => $cus_id,'point_count' => $point,'valid' => true)) or die('Query failed: ' . pg_last_error());
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
function use_reward($dbconn,$reward_id){
    $result = pg_update($dbconn,'dcup_reward_tbl',array('reward_use_date' => get_datetime()),array('id' => $reward_id)) or die('Query failed: ' . pg_last_error());
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

function get_cus_tel($dbconn,$cus_line_id){
    $query = "SELECT cus_tel FROM dcup_customer_mst WHERE cus_line_id = '" . $cus_line_id . "'";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $cus_tel = $col_value;
        }
    }
    // Free resultset
    pg_free_result($result);
    return $cus_tel;
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

function get_cus_line_id($dbconn,$cus_tel,$cus_id){
	if (!is_null($cus_tel)){
		$str = "cus_tel = '" . $cus_tel . "'";
	}
	else if(!is_null($cus_id)){
		$str = "cus_id = '" . $cus_id . "'";
	}
    $query = "SELECT cus_line_id FROM dcup_customer_mst WHERE " . $str;
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
	
}
function get_reward($dbconn,$cus_id){
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
function get_reward_id($dbconn,$cus_id){
	$query = "SELECT id FROM dcup_reward_tbl Where valid = false and reward_use_date is null and customer_id = '" . $cus_id . "' order by reward_start_date ASC";
    $result = pg_query($dbconn,$query) or die('Query failed: ' . pg_last_error());
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        foreach ($line as $col_value) {        
            $reward_id = $col_value;
        }
		break;
    }
    // Free resultset
    pg_free_result($result);
	return $reward_id;
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

function main_function($dbconn,$cus_name,$cus_line_id,$cus_tel,$isPhoneText,$isUpdate,$replyToken){
	$hello = $cus_name;
	if (is_admin($dbconn,$cus_line_id)){
		#$hello = $hello . " คุณคือโคบาน";
		#$cus_line_id = get_cus_line_id($dbconn,$cus_tel,NULL);
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
				$cus_id = get_cus_id($dbconn,$cus_line_id);
				$str_cus_id = sprintf("D%04s",$cus_id);
				#$tel = "your phone number " . $cus_tel . " is registered already.\nYour ID is " . $str_cus_id;
				$tel = "หมายเลขโทรศัพท์  " . $cus_tel . " ได้ลงทะเบียนเรียบร้อย\n• รหัสสมาชิกของคุณคือ " . $str_cus_id;
				$tel .= "\n• คุณได้รับสิทธิพิเศษฟรี 1 สิทธิ";
				insert_reward($dbconn,$cus_id,10,false);
				insert_reward($dbconn,$cus_id,0,true);
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
				$tel = "[".$str_cus_id . "]\n" . $tel;
			}
			else 
			{
				#$tel = "sorry it is not your phone number.\nPlease try again";
				$tel = "ลงทะเบียนไม่สำเร็จ\nกรุณาพิมพ์หมายเลขโทรศัพท์ 10 หลัก อีกครั้งนะคะ";
			}
		}
	}
	return $hello . ' ' . $tel;
}

if (is_admin($dbconn,$cus_line_id)){
	if ($isGivePoint){
		if($isPhoneText){
			$push_line_id = get_cus_line_id($dbconn,$cus_tel,NULL);
			$cus_id = get_cus_id($dbconn,$push_line_id);
		}
		else if($isCusIDText){
			$push_line_id = get_cus_line_id($dbconn,NULL,$cus_id);	
		}
		if(!is_null($push_line_id)){
			$cus_name = get_cus_name($dbconn,$push_line_id);

			$str_cus_id = sprintf("D%04s",$cus_id);
			
			$point_cur = get_point($dbconn,$cus_id);
			$point_new = $point_cur + $point;
			$push_line_mes = "คุณได้รับเพิ่ม ". $point . " แต้ม\n";
			if (!is_reward_exist($dbconn,$cus_id)){
				insert_reward($dbconn,$cus_id,$point_new,true);
			}
			else{
				if ((floor($point_new / 10)) == 0){
					update_reward($dbconn,$cus_id,$point_new,true);
				}
				else{
					update_reward($dbconn,$cus_id,10,false);
					insert_reward($dbconn,$cus_id,($point_new % 10),true);
				}
			}
			
			$reward = get_reward($dbconn,$cus_id);
			$str_message = get_reward_message(($point_new % 10),$reward);
			$str_message = $cus_name . "[" . $str_cus_id . "]\n" . $str_message;
			$push_line_mes = $push_line_mes . $str_message;
		}
		else{
			$push_line_id = get_admin_lineid($dbconn);
			$push_line_mes = "หมายเลขนี้ยังไม่ได้ทำการลงทะเบียน";
		}
	}
	else if ($isUseReward){
		if($isPhoneText){
			$push_line_id = get_cus_line_id($dbconn,$cus_tel,NULL);
			$cus_id = get_cus_id($dbconn,$push_line_id);
		}
		else if($isCusIDText){
			$push_line_id = get_cus_line_id($dbconn,NULL,$cus_id);
		}
		if(!is_null($push_line_id)){
			$cus_name = get_cus_name($dbconn,$push_line_id);

			$str_cus_id = sprintf("D%04s",$cus_id);
			$reward_id = get_reward_id($dbconn,$cus_id);

			if(!is_null($reward_id)){
				use_reward($dbconn,$reward_id);
				$str_message = "• คุณได้ทำการใช้สิทธิพิเศษ 1 สิทธิ";
				$push_line_mes = $cus_name . "[" . $str_cus_id . "]\n" . $str_message;
			}
			else{
				$push_line_id = get_admin_lineid($dbconn);
				$push_line_mes = "หมายเลขนี้ยังไม่มีสิทธิพิเศษ";
			}
		}
		else{
			$push_line_id = get_admin_lineid($dbconn);
			$push_line_mes = "หมายเลขนี้ยังไม่ได้ทำการลงทะเบียน";
		}
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
}

function createNewRichmenu($channelAccessToken) {
  $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $channelAccessToken' \
  -H 'Content-Type:application/json' \
  -d '{"size": {"width": 2500,"height": 1686},"selected": false,"name": "Controller","chatBarText": "Controller","areas": [{"bounds": {"x": 551,"y": 325,"width": 321,"height": 321},"action": {"type": "message","text": "up"}},{"bounds": {"x": 876,"y": 651,"width": 321,"height": 321},"action": {"type": "message","text": "right"}},{"bounds": {"x": 551,"y": 972,"width": 321,"height": 321},"action": {"type": "message","text": "down"}},{"bounds": {"x": 225,"y": 651,"width": 321,"height": 321},"action": {"type": "message","text": "left"}},{"bounds": {"x": 1433,"y": 657,"width": 367,"height": 367},"action": {"type": "message","text": "btn b"}},{"bounds": {"x": 1907,"y": 657,"width": 367,"height": 367},"action": {"type": "message","text": "btn a"}}]}' https://api.line.me/v2/bot/richmenu;
EOF;
  $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
  if(isset($result['richMenuId'])) {
    return $result['richMenuId'];
  }
  else {
    return $result['message'];
  }
}

function getListOfRichmenu($channelAccessToken) {
  $sh = <<< EOF
  curl \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/richmenu/list;
EOF;
  $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
  return $result;
}

function deleteRichmenu($channelAccessToken, $richmenuId) {
  if(!isRichmenuIdValid($richmenuId)) {
    return 'invalid richmenu id';
  }
  $sh = <<< EOF
  curl -X DELETE \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/richmenu/$richmenuId
EOF;
  $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
  if(isset($result['message'])) {
    return $result['message'];
  }
  else {
    return 'success';
  }
}
function unlinkFromUser($channelAccessToken, $userId) {
  $sh = <<< EOF
  curl -X DELETE \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/user/$userId/richmenu
EOF;
  $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
  if(isset($result['message'])) {
    return $result['message'];
  }
  else {
    return 'success';
  }
}

$textMessageBuilder = new TextMessageBuilder($push_line_mes);
$bot->pushMessage($push_line_id, $textMessageBuilder);
$bot->replyMessage($replyToken, $textMessageBuilder);
#$response = $bot->pushMessage($push_line_id, $textMessageBuilder);
#echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

pg_close($dbconn);
