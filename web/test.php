<?php
require 'D:\phpStudy\PHPTutorial\WWW\group\http.php';

function lookFuckipv4(){
	$cf = new commonFunction();
	$url='http://172.17.225.88/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset=600&count=200';
	$action = 'GET';
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$data = '';
	$res = $cf->callInterfaceCommon($url,$action,$data,$headers);
	$GLOBALS['result'] = $res;
	$arr=json_decode($res);
	//print_r($GLOBALS['result']);



	foreach($arr->mafcustomv4wblist as $val){
		print_r($val->GroupStr);
		//echo '<br>';
					
					// if($nums<$Neweds_date){

					// 	$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord,groups)VALUES("'.$val->DbId.'","'.$val->IPaddr.'","'.$val->ModRecord.'","'.$val->GroupStr.'")';
		   // 				$result1=$mysql->query($sql1);
		   		

					// }
					// $nums++;
				}

}
lookFuckipv4()
// $tiao=$tiao+count($arr->mafcustomv4wblist);


// foreach($arr->mafcustomv4wblist as $val){
// 	pritn_r($val);
					
// 					// if($nums<$Neweds_date){

// 					// 	$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord,groups)VALUES("'.$val->DbId.'","'.$val->IPaddr.'","'.$val->ModRecord.'","'.$val->GroupStr.'")';
// 		   // 				$result1=$mysql->query($sql1);
		   		

// 					// }
					
// }
// // 


?>