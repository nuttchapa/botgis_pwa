<?php

date_default_timezone_set('Asia/Bangkok');
header('Content-Type: text/html; charset=utf-8');

//ini_set("log_errors", 1);
//ini_set("error_log", "php-error.txt");

require_once('LINEBotTiny.php');

//mlab
$api_key = "xxxxxxxxxxxxxxxxxxxxxxxxxxxx";

//line
$channelAccessToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$access_token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

//line
$channelSecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$botName = "BOT";


//----------function--114------------//
function get_url($urllink) {
  $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $urllink);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}
//---------------------------------//

function get_profile($fullurl) 
{
        $channelAccessToken2 = $channelAccessToken;
 
        $header = array(
            "Content-Type: application/json",
            'Authorization: Bearer '.$channelAccessToken2,
        );
 

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);      
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $fullurl);
         
        $returned =  curl_exec($ch);
        curl_close($ch);
        return($returned);
}

//-----------auto send----push message------------------//
// Example : https://gispwaai.herokuapp.com/bot/bot.php?send=auto&text=test
// Example : https://gispwaai.herokuapp.com/bot/bot.php?send=auto&text=test&uid=xxxxxxxxxxxx

if ( $_GET['send'] == 'm_group' )
{
	$text = array(
			'type' => 'text',
			'text' => $_GET['text']
		);
	$uid = "xxxxxxxxxxxx"; // meter group
	$client->pushMessage($uid, $text);
}

if ( $_GET['send'] == 'g_group' )
{
	$text = array(
			'type' => 'text',
			'text' => $_GET['text']
		);
	$uid = "xxxxxxxxxxxx"; // gis_group
	$client->pushMessage($uid, $text);
}


//---------------------------------------------------------//


if ( $_GET['send'] == 'auto' )
{
	$text = array(
			'type' => 'text',
			'text' => $_GET['text']
		);
	$uid = $_GET['id']; // id auto
	$client->pushMessage($uid, $text);
}
//---------------------------------------------------------//


//ส่งแบบข้อความแบบ-multi----แบบ array มี sub array-------------//
if ( $_GET['send'] == 'location' )
{

	$text = array(
		            array(
			                'type' => 'image',
		                    'originalContentUrl' => $_GET['path_img'],
		                    'previewImageUrl' => $_GET['path_img']
		                ),
		            array(
							"type"=> "location",
							"title"=> "ตำแหน่งของภาพ(".urldecode($_GET['nameid']).")",
							"address"=> "Location",
							"latitude"=> $_GET['Latuse'],
							"longitude"=> $_GET['Lonuse']		
						)
			);  
	//$uid = "xxxxxxxxxxxx";	  // id group GIS	
	$uid = $_GET['id'];
	$client->pushMessage1($uid, $text);
}



// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent

			$text = $event['message']['text'];
			$uid = $event['source']['userId'];
			$gid = $event['source']['groupId'];
			$timestamp = $event['timestamp'];

			if ($text == 'EGA' || $text == 'ega') {

				$messages = [
				'type' => 'text',
				'text' => 'สำนักงานรัฐบาลอิเล็กทรอนิกส์ (องค์การมหาชน) : EGA เปลี่ยนชื่อเป็น สำนักงานพัฒนารัฐบาลดิจิทัล (องค์การมหาชน) (ใช้ชื่อย่อ "สพร.") และเปลี่ยนชื่อภาษาอังกฤษเป็น "Digital Government Development Agency (Public Organization)" (ย่อว่า "DGA")'
				];

			}

			//ทดสอบฟังก์ชั่น getprofile --user ต้องอัพเดทlineเป็นversionใหม่
			else if(preg_match('(สวัสดี|สวัสดีครับ|สวัสดีค่ะ)', $text) === 1) {	

					$gid = $event['source']['groupId'];
					$uid = $event['source']['userId'];

					//$url = 'https://api.line.me/v2/bot/group/'.$gid.'/member/'.$uid; //กลุ่ม
					$url = 'https://api.line.me/v2/bot/profile/'.$uid;			//user
					$channelAccessToken2 = $channelAccessToken;

					$header = array(
						"Content-Type: application/json",
						'Authorization: Bearer '.$channelAccessToken2,
					);
					$ch = curl_init();
					//curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
					//curl_setopt($ch, CURLOPT_VERBOSE, 1);
					//curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_FAILONERROR, 0);		;
					//curl_setopt($ch, CURLOPT_HTTPGET, 1);
					//curl_setopt($ch, CURLOPT_USERAGENT, $agent);
					//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					curl_setopt($ch, CURLOPT_URL, $url);
					
					$profile =  curl_exec($ch);
					curl_close($ch);
					$obj = json_decode($profile);

					$pathpic = explode("cdn.net/", $obj->pictureUrl);

					/*
					$a = array(

							array(
								'type' => 'text',
								'text' => 'สวัสดี @'.$obj->displayName."(".$obj->statusMessage.")"
							),
							array(
								'type' => 'image',
								'originalContentUrl' => 'https://obs.line-apps.com/'.$pathpic[1],
								'previewImageUrl' => 'https://obs.line-apps.com/'.$pathpic[1].'/large'
							)
//							,
//								array(
//									'type' => 'text',
//									'text' => $ty. ' '.$uid. ' '. $gid. ' '.$profile
//								)
//							,
//								array(
//									'type' => 'text',
//									'text' => 'สวัสดีคุณ '.$obj->displayName.' type='.$ty.' uid='.$uid.' gid='.$gid
//								)
//						,
//							array(
//								'type' => 'text',
//								'text' => $obj->statusMessage
//							),
//							array(
//								'type' => 'text',
//								'text' => $obj->pictureUrl
//							)
						);
					$client->replyMessage1($event['replyToken'],$a);
				*/
					$messages = [
							"type" => "text",
							//"text" =>  "สวัสดี คุณ ".$obj->displayName
							"text" =>  "สวัสดี คุณ ".$obj->displayName."(".$obj->statusMessage.")"
					];

			}
	


			else if (preg_match('(วิธีการเพิ่มสิทธิ์|วิธีเพิ่มสิทธิ์)', $text) === 1) {
				$text_reply = "วิธีการเพิ่มสิทธิ์ระบบติดตามมาตรวัดน้ำ 
				\n ใช้คีย์เวิร์ดในการเพิ่ม ดังนี้ #เพิ่มสิทธิ์ หรือ #เพิ่มสิทธิ์มาตร หรือ #เพิ่มสิทธิ์ระบบมาตร หรือ #เพิ่มสิทธิ์ระบบมาตรฯ 
				\n ***(เพิ่มสิทธิ์ได้มากสุด ครั้งละไม่เกิน 59 user)
				\n ตัวอย่างการเพิ่มสิทธิ์
				\n  1.กรณีเพิ่มสิทธิ์ 1 คน => #เพิ่มสิทธิ์ 12974
				\n  2.กรณีเพิ่มสิทธิ์ มากกว่า 1 คน => #เพิ่มสิทธิ์ 12974 12975 12976
				";

				// Build message to reply back
				$messages = [
				'type' => 'text',
				//'text' => $text
				'text' => $text_reply
				];

			}


			else if ($text == 'kpi' || $text == 'Kpi' || $text == 'KPI' ) {
				$pic = "https://gis4manager.herokuapp.com/image/kpi.jpg";
				// Build message to reply back
				$messages = [
					'type' => 'image',
					'originalContentUrl' => $pic,
					'previewImageUrl' => $pic
				];

			}


			else if ($text == 'sticker') {

				// Build message to reply back
				$messages = [
					"type" => "sticker",
					"packageId" => "1",
					"stickerId" => "1"
				];

			}


			else if ($text == 'location') {
				$messages = [
					"type"=> "location",
					"title"=> "ตำแหน่ง กปภ.",
					"address"=> "กปภ. สำนักงานใหญ่",
					"latitude"=> 13.875844,
					"longitude"=> 100.585318
				];
			}


			else if ($text == 'id') {
				$gid = $event['source']['groupId'];
				$uid = $event['source']['userId'];
				// Build message to reply back
				$messages = [
				'type' => 'text',
				"text" => 'uid:'.$uid.'\n'.'gid'.$gid
				];
			}


			else {
				
				/*
				$text_reply = "ยังไม่มีคำตอบ";

				// Build message to reply back
				$messages = [
				'type' => 'text',
				//'text' => $text
				"text" => $text_reply." ".$uid
				//"text" => $text_reply

				];

				*/

			}



			// Get replyToken
			$replyToken = $event['replyToken'];


			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
				//'messages' => ["https://gispwaai.herokuapp.com/golf.jpg"],

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

			echo $result . "\r\n";
		}




	}
}



echo "OK";



//ฟังก์ชั่น ReplyMessage-------------------------------------------------------------//
function replyMsg($event, $client)
{
  //if($event['source']['groupId'] == 'Cbb266cca8a0e0b7ae940dec7f3dc15dc' || $event['source']['userId'] == 'Ud28e6a312cb9816218fc44edef9c2f3d'){
    $uid;
    $gid;
    $ty  = $event['source']['type'];    //user,group
    //$uid = $event['source']['userId'];
    //$gid = $event['source']['groupId'];
    if($event['source']['userId']){
        $uid = $event['source']['userId'];
    }
    if($event['source']['groupId']){
        $uid = $event['source']['groupId'];
    }
 
    $id = $event['message']['id'];
 
    //-----ถ้ามีการส่งข้อความText------------------------------------------------------------//
    if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
        //ข้อความtext ที่ได้รับ
        $msg = $event['message']['text'];
 
        //$api_key="xxxxxxxxxxxxxxxxxxxxxxxxxxxx";
        $url = 'https://api.mlab.com/api/1/databases/linedb/collections/meter_gis?apiKey='.$api_key;
 
 
        //file_get_contents('https://api.mlab.com/api/1/databases/linedb/collections/kunutt?apiKey='.$api_key.'&q={ "_id" : { "$oid" : "59fc80f9c2ef163b3e8be96d"} ,"question":"'.$_question.'"}&c=true');
 
 
        $msg_encode = urlencode($msg);
        $json_cmsg = file_get_contents('https://api.mlab.com/api/1/databases/linedb/collections/meter_gis?apiKey='.$api_key.'&q={"question":"'.$msg_encode.'"}');
        $q_msg = json_decode($json_cmsg); 
 
 
         if (preg_match('(สอนบอท)', $msg) === 1) {
 
            if(strstr($msg,"[") && strstr($msg,"|") && strstr($msg,"]")){
 
                // พบคำว่า PHP ในข้อความ
 
                $x_tra = str_replace("สอนบอท","", $msg);
                $pieces = explode("|", $x_tra);
                $_question = str_replace("[","",$pieces[0]);
                $_answer = str_replace("]","",$pieces[1]);
 
                if($_question == '' || $_answer == '' ){
                    exit();
                }
 
                //count-question---------//
                $json_c = file_get_contents('https://api.mlab.com/api/1/databases/linedb/collections/meter_gis?apiKey='.$api_key.'&q={"question":"'.$_question.'"}&c=true');
                $count = json_decode($json_c);  //จำนวนที่นับได้
                //count-question---------//
 
 
						$id = $event['source']['userId'];
                        $urlp = 'https://api.line.me/v2/bot/profile/'.$id;
                        $channelAccessToken2 = $channelAccessToken;
 
                        $header = array(
                            "Content-Type: application/json",
                            'Authorization: Bearer '.$channelAccessToken2,
                        );
 
                        $ch = curl_init();
                        //curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
                        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
                        //curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_FAILONERROR, 0);       ;
                        //curl_setopt($ch, CURLOPT_HTTPGET, 1);
                        //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($ch, CURLOPT_URL, $urlp);
                         
                        $profile =  curl_exec($ch);
                        curl_close($ch);
                        $obj = json_decode($profile);
 
                        $pathpic = explode("cdn.net/", $obj->pictureUrl);
 
 
                if ($count == 0){
 
                    //Post New Data--------------------------//
                    $newData = json_encode(
                      array(
                        'question' => $_question,
                        'answer'=> $_answer,
                        'uid'=> $uid,
                        'name'=> $obj->displayName,
                        'originalContentUrl' => 'https://obs.line-apps.com/'.$pathpic[1]
                      )
                    );
 
                    $opts = array(
                      'http' => array(
                          'method' => "POST",
                          'header' => "Content-type: application/json",
                          'content' => $newData
                       )
                    );
                    $context = stream_context_create($opts);
                    $returnValue = file_get_contents($url,false,$context);
                    //Post New Data--------------------------//
 
 
                    $sec = explode('"$oid" : "', $returnValue);
                    $sec_id = explode('"', $sec[1]);
 
                     
                    $t=array("น่ารักที่สุดอ่ะเราน่ะ","ต่อไปเราจะตอบเธอแบบนี้นะ","ขอบคุณที่สอนเรานะ","เข้าใจแล้วล่ะว่าต้องตอบเธอยังไง");
                    $random_keys=array_rand($t,1);
                    $txt = $t[$random_keys];
                    $a = array(
                                array(
                                    'type' => 'text',
                                    //'text' => $txt." เพิ่ม id:".$sec_id[0]." count:".$count
                                    'text' => $txt
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
 
                }

                else if ($count == 1){  
 
                    //query-คำถามที่เคยถามในdb----------------------------------//
                    $json_f = file_get_contents('https://api.mlab.com/api/1/databases/linedb/collections/meter_gis?apiKey='.$api_key.'&q={"question":"'.$_question.'"}');
                    $q_json_f = json_decode($json_f); 
                    $q_json_id = $q_json_f[0]->_id;
                    $q_json_oid = '';
                    foreach ($q_json_id as $k=>$v){
                        $q_json_oid = $v; // etc.
                    }
 
                    //update-----------------------------------//
                    //$_id = '59fb2268bd966f7657da67cc';
                    $url_up = 'https://api.mlab.com/api/1/databases/linedb/collections/meter_gis/'.$q_json_oid.'?apiKey='.$api_key;
 
                    $newupdate = json_encode(
                        array(
                            '$set' => array('answer'=> $_answer)
                        )
                    );
 
                    $optsu = array(
                        'http' => array(
                            'method' => "PUT",
                            'header' => "Content-type: application/json",
                            'content' => $newupdate
                        )
                    );
 
                    $contextu = stream_context_create($optsu);
                    $returnValup = file_get_contents($url_up, false, $contextu);
 
 
                    $t=array("คำถามนี้เคยสอนแล้วนะ แต่ไม่เปงไร จำใหม่ก็ได้","สอนซ้ำๆแบบนี้ เมมจะเต็มแล้วนะ");
                    $random_keys=array_rand($t,1);
                    $txt = $t[$random_keys];
                    //$txt = 'มีคำถามนี้แล้ว-อัพเดท $oid:';
                    $a = array(
                                array(
                                    'type' => 'text',
                                    //'text' => $txt." อัพเดท id:".$q_json_oid." count:".$count
                                    'text' => $txt
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
                }
            }
 
            else{
 
                    $t = 'สอนผมได้นะ เพียงพิมพ์: สอนบอท[คำถาม|คำตอบ]';  
                    $a = array(
                                array(
                                    'type' => 'text',
                                    'text' => $t . ''               
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
            }
 
 
        }
 
        else{
 
            $ty = $event['source']['type']; //user,group
 
            //หากไม่มีคำว่า สอนบอทจะมีการยิง API ข้างต้นไปตรงๆ โดยมี q สำหรับ Query {question:คำถาม} ไปเลย ก็จะใช้การเรียกคือ
 
            if($q_msg){
                foreach($q_msg as $rec){
                    $a = array(
                                array(
                                    'type' => 'text',
                                    'text' => $rec->answer            
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
                }
            }
 
            else{
 
 
                if (preg_match('(หวัดดี|หวัดดีค่ะ|หวัดดีครับ|ดีค่ะ|ดีคับ|ดีครับ|สวัสดีบอท|หวัดดีบอท|บอท)', $msg) === 1) {
 
                    if ($ty == 'user'){
 
                        $url = 'https://api.line.me/v2/bot/profile/'.$uid;
                        $channelAccessToken2 = $channelAccessToken;
 
                        $header = array(
                            "Content-Type: application/json",
                            'Authorization: Bearer '.$channelAccessToken2,
                        );
 
                        $ch = curl_init();
                        //curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
                        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
                        //curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_FAILONERROR, 0);       ;
                        //curl_setopt($ch, CURLOPT_HTTPGET, 1);
                        //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($ch, CURLOPT_URL, $url);
                         
                        $profile =  curl_exec($ch);
                        curl_close($ch);
                        $obj = json_decode($profile);
 
 
                        $pathpic = explode("cdn.net/", $obj->pictureUrl);
 
 
                        $a = array(
 
                                array(
                                    'type' => 'text',
                                    'text' => 'ดีจ้า '.$obj->displayName
                                ),
                                array(
                                    'type' => 'image',
                                    'originalContentUrl' => 'https://obs.line-apps.com/'.$pathpic[1],
                                    'previewImageUrl' => 'https://obs.line-apps.com/'.$pathpic[1].'/large'
                                )
    //                      ,
    //                          array(
    //                              'type' => 'text',
    //                              'text' => $ty. ' '.$uid. ' '. $gid. ' '.$profile
    //                          )
    //                      ,
    //                          array(
    //                              'type' => 'text',
    //                              'text' => 'สวัสดีคุณ '.$obj->displayName.' type='.$ty.' uid='.$uid.' gid='.$gid
    //                          )
    //                      ,
    //                          array(
    //                              'type' => 'text',
    //                              'text' => $obj->statusMessage
    //                          ),
    //                          array(
    //                              'type' => 'text',
    //                              'text' => $obj->pictureUrl
    //                          )
                            );
                        $client->replyMessage1($event['replyToken'],$a);
 
                    }
 
                    else if ($ty == 'group'){
 
 
                        $gid = $event['source']['groupId'];
                        $uid = $event['source']['userId'];
 
                        $url = 'https://api.line.me/v2/bot/group/'.$gid.'/member/'.$uid;
                        //$url = 'https://api.line.me/v2/bot/profile/'.$uid;
                        $channelAccessToken2 = $channelAccessToken;
 
                        $header = array(
                            "Content-Type: application/json",
                            'Authorization: Bearer '.$channelAccessToken2,
                        );
                        $ch = curl_init();
                        //curl_setopt($ch, CURLOPT_HTTP_VERSION, 'CURL_HTTP_VERSION_1_1');
                        //curl_setopt($ch, CURLOPT_VERBOSE, 1);
                        //curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_FAILONERROR, 0);       ;
                        //curl_setopt($ch, CURLOPT_HTTPGET, 1);
                        //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        curl_setopt($ch, CURLOPT_URL, $url);
                         
                        $profile =  curl_exec($ch);
                        curl_close($ch);
                        $obj = json_decode($profile);
 
                        $pathpic = explode("cdn.net/", $obj->pictureUrl);
 
                        $a = array(
 
                                array(
                                    'type' => 'text',
                                    'text' => 'ดีจ้า '.$obj->displayName
                                ),
                                array(
                                    'type' => 'image',
                                    'originalContentUrl' => 'https://obs.line-apps.com/'.$pathpic[1],
                                    'previewImageUrl' => 'https://obs.line-apps.com/'.$pathpic[1].'/large'
                                )
//                          ,
//                              array(
//                                  'type' => 'text',
//                                  'text' => $ty. ' '.$uid. ' '. $gid. ' '.$profile
//                              )
//                          ,
//                              array(
//                                  'type' => 'text',
//                                  'text' => 'สวัสดีคุณ '.$obj->displayName.' type='.$ty.' uid='.$uid.' gid='.$gid
//                              )
    //                      ,
    //                          array(
    //                              'type' => 'text',
    //                              'text' => $obj->statusMessage
    //                          ),
    //                          array(
    //                              'type' => 'text',
    //                              'text' => $obj->pictureUrl
    //                          )
                            );
                        $client->replyMessage1($event['replyToken'],$a);
 
                    }
 
                }
 

				else if(preg_match('(#ลบ|#ลบข้อมูล)', $msg) === 1) {

						$pieces = explode(" ", $msg);
						$_sel = $pieces[1];

						//$api_key="xxxxxxxxxxxxxxxxxxxxxxxx";

						//query-คำถามที่เคยถามในdb----------------------------------//
						$json_f = file_get_contents('https://api.mlab.com/api/1/databases/linedb/collections/meter_gis?apiKey='.$api_key.'&q={"question":"'.$_sel.'"}');
						$q_json_f = json_decode($json_f); 
						$q_json_id = $q_json_f[0]->_id;
						$q_json_oid = '';
						foreach ($q_json_id as $k=>$v){
							$q_json_oid = $v; // etc.
						}
 
						//$_id = '59fb2268bd966f7657da67cc';
						$url_d = 'https://api.mlab.com/api/1/databases/linedb/collections/meter_gis/'.$q_json_oid.'?apiKey='.$api_key;

						$optsd = array(
								'http' => array(
								'method' => "DELETE",
								'header' => "Content-type: application/json"
							)
						);
	 
						$contextd = stream_context_create($optsd);
						$returnValdel = file_get_contents($url_d, false, $contextd);

						$t=array("ลบให้แล้วครับ","จัดให้ครับ","ลบเรียบร้อยครับ");
						$random_keys=array_rand($t,1);
						$txt = $t[$random_keys];
						$a = array(
									array(
										'type' => 'text',
										'text' => $txt          
									)
								);
						$client->replyMessage1($event['replyToken'],$a);


				}

				else if(preg_match('(#ใครสอน|#ใครสอนคำว่า)', $msg) === 1) {

						$pieces = explode("|", $msg);
						$_sel = $pieces[1];

						//$api_key="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

						$msg_encode = urlencode($_sel);
						$json_cmsg = file_get_contents('https://api.mlab.com/api/1/databases/linedb/collections/meter_gis?apiKey='.$api_key.'&q={"question":"'.$msg_encode.'"}');
						$q_msg = json_decode($json_cmsg); 
				 
						if($q_msg){
							foreach($q_msg as $rec){
								$a = array(
											array(
												'type' => 'text',
												'text' => $rec->name     
												//'text' => $rec->originalContentUrl 					
											),
											array(
													'type' => 'image',
													'originalContentUrl' => $rec->originalContentUrl ,
													'previewImageUrl' => $rec->originalContentUrl 
												)
										);
								$client->replyMessage1($event['replyToken'],$a);
							}
						}

				}

 
                else if (preg_match('(เสียใจ|ร้องไห้|ไม่ต้องร้อง|ผิดหวัง)', $msg) === 1) {
                    $a = array(
                                array(
                                    'type' => 'sticker',
                                    'packageId' => 2,
                                    'stickerId' => 152
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
                }
 
                else if (preg_match('(นอนละ|ไปนอน|นอนแล้ว|ฝันดี)', $msg) === 1) {
 
                    $a = array(
                                array(
                                    'type' => 'sticker',
                                    'packageId' => 3,
                                    'stickerId' => 239
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
 
                }
 
                else if (preg_match('(ด่า|นิสัยไม่ดี|โดนว่า|น่าเบื่อ|รำคาญ)', $msg) === 1) {
 
                    $t = 'การบ่นไม่ใช่การแก้ปัญหา และ การด่าก็ไม่ใช่วิธีการแก้ไข';  
                    $a = array(
                                array(
                                    'type' => 'text',
                                    'text' => $t . ''               
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
                }
 
 
                else if (preg_match('(เหงา|เหงาจัง|เหงาอ่ะ)', $msg) === 1) {
 
                    $t=array("เราพร้อมจะเป็นเพื่อนคุณนะ","เหงาเหมือนกันเลย","ให้ช่วยแก้เหงามั้ย");
                    $random_keys=array_rand($t,1);
                    $txt = $t[$random_keys];
                    $a = array(
                                array(
                                    'type' => 'text',
                                    'text' => $txt          
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
                }
 
                else if (preg_match('(วิธีใช้งาน|สอนยังไง|วิธีสอน)', $msg) === 1) {
 
                    $t = 'คุณสามารถสอนผมให้ฉลาดได้ เพียงพิมพ์: สอนบอท[คำถาม|คำตอบ]';    
                    $a = array(
                                array(
                                    'type' => 'text',
                                    'text' => $t . ''               
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
 
                }
 
 
                else if (preg_match('(วันนี้|วันอะไร)', $msg) === 1) {

					$today = date("Y-m-d");
					//$today = "2018-07-01";
					$txt = "";
					$DayOfWeek = date("w", strtotime($today));
					if($DayOfWeek == 0 )  // 0 = Sunday, 6 = Saturday;
					{
						$txt = "วันนี้เป็นวันหยุด(วันอาทิตย์)";
						//echo "$today = <font color=red>Holiday(Sunday)</font><br>";
					}

					else if($DayOfWeek ==6)  // 0 = Sunday, 6 = Saturday;
					{
						$txt = "วันนี้เป็นวันหยุด(วันเสาร์)";
						echo "$today = <font color=red>Holiday(Saturday)</font><br>";
					}


					else{
						$txt = "วันนี้ก็คือวันนี้";
						//echo "$today = <font color=blue>No Holiday</font><br>";

					}


                    $a = array(
                                array(
                                    'type' => 'text',
                                    'text' => $txt          
                                )
                            );
                    $client->replyMessage1($event['replyToken'],$a);
                }

                else{
 
                }
                 
            }
 
        }
    }
    //----------------------------จบเงื่อนไขข้อความtext-----------------------------------//
 
 
 
    //-----ถ้ามีการส่งสติ๊กเกอร์------------------------------------------------------------//
    elseif ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {


		$sticker=array("2,149","2,23","3,239","2,154","2,161","3,232","2,24","1,115","2,152","4,616","4,296","2,165","4,279","2,525","2,19","2,527");
		$random_keys=array_rand($sticker,1);
		$txt = $sticker[$random_keys];

		$split = explode(",", $txt);
		$p = $split[0];
		$s = $split[1];
		//echo $split[0];

        $client->replyMessage1($event['replyToken'],array(
                array(
                    'type' => 'sticker',
                    'packageId' => $p,
                    'stickerId' => $s
                )
             
                /*,
                array(
                    'type' => 'sticker',
                    'packageId' => 3,
                    'stickerId' => 232
                ),
                    array(
                    'type' => 'sticker',
                    'packageId' => 3,
                    'stickerId' => 233
                )       
                */
         
        )
            );
    }
    //----------------------------จบเงื่อนไขสติ๊กเกอร์------------------------------------//
 

   //-----ถ้ามีการแชร์ location-------------------------------------------------------//
   elseif ($event['type'] == 'message' && $event['message']['type'] == 'location') {
        $latitude = $event['message']['latitude'];
        $longitude = $event['message']['longitude'];
        $title = $event['message']['title'];
        $address = $event['message']['address'];
 
               $client->replyMessage1($event['replyToken'],array(
 
 
                        array(
                                'type' => 'text',
                                'text' => 'มีการแชร์ตำแหน่ง'
                        ),
 
                        array(
                                "type"=> "location",
                                "title"=> "ตำแหน่งของท่าน",
                                "address"=> $address,
                                "latitude"=> $latitude,
                                "longitude"=> $longitude
                        )
                   )
                );
    }
  


  //}
}
//----------------------------จบฟังก์ชั่น ReplyMessage----------------------------------//
 
 
 
 
//------listen--$client->parseEvents()----และเข้าฟังก์ชั่น replyMsg--------//
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    replyMsg($event, $client);                  
                    break;
                case 'image':
                    replyMsg($event, $client);                  
                    break;
                case 'sticker':
                    replyMsg($event, $client);                  
                    break;
                case 'location':
                    replyMsg($event, $client);                  
                    break;
                default:
                    //error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
//----------------------------------------------------------//
 
 





?>
