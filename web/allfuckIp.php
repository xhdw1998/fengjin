<?php
error_reporting(0);
session_start();
$islogin = $_SESSION['islogin'];

$group=$_SESSION['group'];


if(($islogin) == 1){
	//continue;
}else{
	header("location:index.php?=Login");
}


require  'copy.php';
require  'http.php';
//require  'D:\phpStudy\PHPTutorial\WWW\group\index.php';
?>
<!DOCTYPE html>
<meta charset="utf-8">
<html>
<body>
<div>
<form name="input" action="fuckIpv4.php" method="post" align = "center">
<br><br>
<input type="text" name="GroupStr"  size = '5' value = "<?php echo $_SESSION['group']?>" >分组<br>
<input type="hidden" name="LeftAge" value="-1">
<input type="hidden" name="Action" value = "2">
<input type="hidden" name="CheckLib" value = "1">
<?php 
if($group== 'admin' || $group=='长亭科技研判'){
?>

起始IP: 
<input type="text" name="IPStart">
-- 
<input type="text" name="IPEnd">终止IP 
<input type="submit">

</form>
<div id="pifeng">
<form>
	<textarea rows=10 cols=45 ></textarea><br>
	<input type="submit" value="一键批量封禁">

</form>
</div>
<?php 
}
?>
</div>
</body>
</html>
<?php



if(isset($_POST['ip_list'])){
	$ip_list=$_POST['ip_list'];
	$ip_list=explode(",",$ip_list);




	for($i=0;$i<count($ip_list);$i++){
		print_r($ip_list[$i]);
		// $eds1=Search_Ip($Ntext_ip[$i],'date');
		// parint_r($eds1);


		$i++;
}








function fuckIpv4($GroupStr,$IPStart,$IPEnd,$LeftAge,$Action,$CheckLib){

	$cf = new commonFunction();
	$arr = array('mafcustomv4wblist'=>array('GroupStr' =>$GroupStr ,'IPStart'=>$IPStart,'IPEnd'=>$IPEnd,'LeftAge'=>$LeftAge,'Action'=>$Action,'CheckLib'=>$CheckLib ));
	$data = json_encode($arr);
	$url1='http:///func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';

	$url2='http:///func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url3='http:///func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url4='http://func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
$url5='/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url6='/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$action = 'POST';
	
	$strResult1 = $cf->callInterfaceCommon($url1,$action,$data,$headers);
	
	time_sleep_until(3);
	$strResult2 = $cf->callInterfaceCommon($url2,$action,$data,$headers);
	
	time_sleep_until(3);
	$strResult3 = $cf->callInterfaceCommon($url3,$action,$data,$headers);
	
	time_sleep_until(3);
	$strResult4 = $cf->callInterfaceCommon($url4,$action,$data,$headers);
	
	time_sleep_until(3);
	$strResult5 = $cf->callInterfaceCommon($url5,$action,$data,$headers);
	
	time_sleep_until(3);
	$strResult6 = $cf->callInterfaceCommon($url6,$action,$data,$headers);
	
	if(empty($strResult1)){
		$str1 = '设备1未发送成功';
	}else{
		$str1 = '设备1发送成功';
	}
	if(empty($strResult2)){
		$str2 = '设备2未发送成功';
	}else{
		$str2 = '设备2发送成功';
		}
	if(empty($strResult3)){
		print_r($strResult3);
		$str3 = '设备3未发送成功';
	}else{
		$str3 = '设备3发送成功';
	}if(empty($strResult4)){
		$str4 = '设备4未发送成功';
	}else{
		$str4 = '设备4发送成功';
	}if(empty($strResult5)){
		$str5 = '设备5未发送成功';
	}else{
		$str5 = '设备5发送成功';
	}if(empty($strResult6)){
		$str6 = '设备6未发送成功';
	}else{
		$str6 = '设备6发送成功';
	}
	$str = $str1."<br>".$str2."<br>".$str3."<br>".$str4."<br>".$str5."<br>".$str6 ;
	//$GLOBALS['strMessage'] = $str;
	echo $str;
}

function newlist(){
        $offset=0;
        $num=1;
        $cf = new commonFunction();
        while($num){
               $num++;
               $url='http:///func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$offset.'&count=100';
               $action = 'GET';
               $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
               $data = '';
               $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
               $arr=json_decode($res);

               # print(count($arr->mafcustomv4wblist));
                if(count($arr->mafcustomv4wblist)<100){
		#	echo $num.'<br>';
                        return $num;
                }

                $offset=$offset+100;

         }

   # echo 'offset='.$offset.'<br>';

}




if($_POST){
	if(isset($GroupStr)&&isset($IPStart)&&isset($IPEnd)){
		fuckIpv4($GroupStr,$IPStart,$IPEnd,$LeftAge,$Action,$CheckLib);
		//echo "<script language=JavaScript> location.replace(location.href);</script>";
	}else{
		echo "请输入相关的IP";
		echo "<script language=JavaScript> location.replace(location.href);</script>";
	}
}
function lookFuckipv4($num){
	$cf = new commonFunction();
	$url='http:///func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$num.'&count=100';
	$action = 'GET';
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$data = '';

	$res = $cf->callInterfaceCommon($url,$action,$data,$headers);

	$GLOBALS['result'] = $res;

}

function houfan(){

	
 	$New_ids=$_GET['id']+100;
	
	return $New_ids;

}
?>
<br>




<style>
	#pifeng{
		width:322px;
		margin:auto;
		background:pink;
	}
	#table{
		margin-left:20%;
		margin-right:20%;
	}
	td{
		text-align:center;
	}
	.td1{
		width:5%;
		text-align:center;
	}
	.td3{
		width:20%;

	}
	.aaa{
		width:20em;
	
	}

	.aaa1{
		width:25em;
		
	}

	.aaa2{
		width:15em;
	}
	.td7{
		width:15%;
	}
</style>
<script >
function foreachfun(element) {
	console.log("1111");
	table.innerHTML = table.innerHTML + `<tr><td>${element["Id"]}</td><td>${element["DbId"]}</td><td>${element["IPaddr"]}</td><td>${element["Action"] == 1 ? "白名单":"黑名单"}</td><td>${element["GroupStr"]}</td><td>${element["ModRecord"]}</td><td>${element["LeftAge"]== -1 ? "永久": element["LeftAge"]+" 秒"}</td></tr>`
}

	jsons = <?php echo $result ?>;
	jsons = jsons["mafcustomv4wblist"]
	jsons.reverse()
	table = document.getElementById('table');
	console.log(table)
	inner = table.innerHTML
	jsons.forEach(element => foreachfun(element))
</script>

	

</html>

