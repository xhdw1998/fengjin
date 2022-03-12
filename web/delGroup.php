<?php
error_reporting(0);
session_start();
$islogin = $_SESSION['islogin'];
if(($islogin) == 1){
	//continue;
}else{

	header("location:./login/login.html");


}
require  'D:\phpStudy\PHPTutorial\WWW\group\copy.php';
require  'D:\phpStudy\PHPTutorial\WWW\group\http.php';
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8">
<br>
<br>
<body>
<form name="input" action="delGroup.php" method="post" align="center">
组名:
<input type="text" name="GroupName"> 
<input type="submit"> 
</body>
</form>

<?php

$GroupName = utf8_encode($_POST['GroupName']);
function deleteGroup($GroupName){
	$cf = new commonFunction();
	$arr =  array('mafgrouplist' => array('GroupName' => $GroupName) );
	
	$url1='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url2='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url3='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url4='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url5='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url6='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$data = json_encode($arr);
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$action = 'DELETE';
	$strResult1 = $cf->callInterfaceCommon($url1,$action,$data,$headers);
	time_sleep_until(0.5);
	$strResult2 = $cf->callInterfaceCommon($url2,$action,$data,$headers);
	time_sleep_until(0.5);
	$strResult3 = $cf->callInterfaceCommon($url3,$action,$data,$headers);
	time_sleep_until(0.5);
	$strResult4 = $cf->callInterfaceCommon($url4,$action,$data,$headers);
	time_sleep_until(0.5);
	$strResult5 = $cf->callInterfaceCommon($url5,$action,$data,$headers);
	time_sleep_until(0.5);
	$strResult6 = $cf->callInterfaceCommon($url6,$action,$data,$headers);
	time_sleep_until(0.5);
	
}
if($_POST){
	
	deleteGroup($GroupName);
}
function lookGroup(){
	$cf = new commonFunction();
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$getUrl = "http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist";
	$getAction = 'GET';
	$data = '';
	$lookGroup = $cf->callInterfaceCommon($getUrl,$getAction,$data,$headers);
	//$data = stripslashes(html_entity_decode(utf8_encode($lookGroup)));
	//var_dump(json_decode($data));
	//var_dump(json_decode($json, true));
	$GLOBALS['result'] = $lookGroup;	

	

}
lookGroup();
?>
<br>


<table id="table"  width="1600" border="1" align="center" style="text-align:center">
<tr>
<td>ID</td>
<td>是否启用</td>
<td>组名</td>
<td>描述信息</td>
</tr>
</table>
</div>
<script >
function foreachfun(element) {
	table.innerHTML = table.innerHTML + `<tr><td>${element["Id"]}</td><td>${element["State"]?"是":"否"}</td><td>${element["GroupName"]}</td><td>${element["Descr"]}</td></tr>`
}

	jsons = <?php echo $result ?>;
	jsons = jsons["mafgrouplist"]
	jsons.reverse()

	table = document.getElementById('table');
	
	inner = table.innerHTML
	jsons.forEach(element => foreachfun(element))
</script>
</html>

