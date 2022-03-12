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
<form  action="fuckIpv4.php" method="post" align = "center">
	<textarea rows=10 cols=45 name='ip_list' ></textarea><br>
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

$ids=0;

if(is_numeric($_GET['id'])){
 $ids=$_GET['id'];

$Nelist=(newlist())*100;


echo '<br>';
 #id值必须大于等于0 或则 id值小于list中的数据条目综合
 if($ids>=0 and $ids<=$Nelist){
   lookFuckipv4($ids);
 }else if($ids<0){ 
   $ids=$Nelist;
   lookFuckipv4($ids);
 }else if($ids>$Nelist){
    $ids=$Nelist;
    lookFuckipv4($ids);
 }
}else{
 $ids=(newlist())*100;
 $ids=$ids-200;
 lookFuckipv4($ids);
# print($ids);
 
}









$GroupStr = utf8_encode($_SESSION['group']);
$IPStart = utf8_encode($_POST['IPStart']);
$IPEnd = utf8_encode($_POST['IPEnd']);
$LeftAge = utf8_encode($_POST['LeftAge']);
$Action = utf8_encode($_POST['Action']);
$CheckLib = utf8_encode($_POST['CheckLib']);


//临时封禁
// print_r($CheckLib);
if(isset($_POST['ip_list'])){
	$ip_list=$_POST['ip_list'];
	print_r($ip_list);
	$ip_list=explode("\n",$ip_list);
	echo '<br>';
	print_r($ip_list);




	for($i=0;$i<count($ip_list);$i++){
		print_r(trim($ip_list[$i]));
		fuckIpv4($GroupStr,trim($ip_list[$i]),trim($ip_list[$i]),'-1','2','1');
		sleep(0.3);
	}
}


function fuckIpv4($GroupStr,$IPStart,$IPEnd,$LeftAge,$Action,$CheckLib){

	$cf = new commonFunction();
	$arr = array('mafcustomv4wblist'=>array('GroupStr' =>$GroupStr ,'IPStart'=>$IPStart,'IPEnd'=>$IPEnd,'LeftAge'=>$LeftAge,'Action'=>$Action,'CheckLib'=>$CheckLib ));


	$data = json_encode($arr);
	$url1='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';

	$url2='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url3='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url4='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url5='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
	$url6='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';
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
               $url='http://ip/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$offset.'&count=100';
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

$ye_list=newlist();

function newlist_test($EDS_ip){
    $cf = new commonFunction();
    $url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/getmafcustomv4wblistcount';
    $action = 'GET';
    $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
    $data = '';
    $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
    $arr=json_decode($res);
    $val=$arr->getmafcustomv4wblistcount->Count;
    $offset=$offset+100;
    $num=ceil($val/100); 	
    return $num;
}


#print_r(newlist('172.17.226.89'));
#
#
#临时封禁
if($_POST['IPStart']){

	if(isset($GroupStr)&&isset($IPStart)&&isset($IPEnd)){
		fuckIpv4($GroupStr,$IPStart,$IPEnd,$LeftAge,$Action,$CheckLib);
		//echo "<script language=JavaScript> location.replace(location.href);</script>";
	}else{
		echo "请输入相关的IP";
		echo "<script language=JavaScript> location.replace(location.href);</script>";
	}
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
	$url='http://172.17.242.89/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$num.'&count=100';
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


<div align="center">
	<a href="fuckIpv4.php?id=<?php  $ids=$ids-100; echo $ids ?>">《《往回翻</a>
	<span>&nbsp;&nbsp;&nbsp;&nbsp;当前是第 <?php $ids=$ids+100; echo ($ids/100)+1;?> 页&nbsp;&nbsp;&nbsp;&nbsp;</span>
	<a href="fuckIpv4.php?id=<?php echo houfan();?>">往后翻》》</a>
</div>

<table id="table"  width="60%" border="1" align="center"  >
<tr>
<td class="td1">ID</td>
<td class="td1">DbId</td>
<td class="td3">封禁IP</td>
<td class="td1">动作</td>
<td class="aaa2">分组信息</td>
<td class="aaa1">封禁时间</td>
<td class="td7">封禁时效	</td>
</tr>
</table>


<style>
	#pifeng{
		width:322px;
		margin:auto;
		
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
<?php
//echo "<script language=JavaScript> location.replace(location.href);</script>";
?>
