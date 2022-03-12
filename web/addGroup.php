<?php
error_reporting(0);
session_start();
$islogin = $_SESSION['islogin'];
$group=$_SESSION['group'];


if(($islogin) == 1){
	//continue;
}else{

	header("location:./login/login.html");


}
require  'D:\phpStudy\PHPTutorial\WWW\group\copy.php';
require  'D:\phpStudy\PHPTutorial\WWW\group\http.php';
?>
<html>

<!-- <style> 
body{ text-align:center} 
.div{ margin:0 auto; width:400px; height:100px; border:1px solid #F00} 
/* css注释：为了观察效果设置宽度 边框 高度等样式 */ 
</style>  -->
<br>
<br>
<form name="input" action="addGroup.php" method="post" align="center">
组名:
<input type="text" name="GroupName"> 
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp描述:
<input type="text" name="Descr"> 
<input type="submit">
</form>
<br>


<?php


$GroupName = utf8_encode($_POST['GroupName']);
$State = '1';
$Descr = utf8_encode($_POST['Descr']);



function createGroup($GroupName,$State,$Descr){
	$cf = new commonFunction();
	$arr = array('mafgrouplist'=>array('GroupName' =>$GroupName ,'Descr'=>$Descr,'State'=>$State ));
	$data = json_encode($arr);
	
	$url1='http://171.17.22.9/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url2='http://171.11.24.9/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url3='http://172.17.225.8/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url4='http://112.17.241.8/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url5='http://122.12.19.17/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url6='http://111.12.19.2/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$action = 'POST';
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
	$GLOBALS['str1'] = $result1;
	
}
function lookGroup(){
	$cf = new commonFunction();
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$getUrl = "http://172.17.242.89/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist";
	$getAction = 'GET';
	$data = '';
	$lookGroup = $cf->callInterfaceCommon($getUrl,$getAction,$data,$headers);
	$GLOBALS['result'] = $lookGroup;
}
lookGroup();
if($_POST){
	if(isset($GroupName)&&isset($Descr)){
		if(empty($GroupName)&&empty($Descr)){
			

		}else{
			createGroup($GroupName,$State,$Descr);
			lookGroup();

		}
	}
}
?>
<!-- <div style="text-align:center"> -->
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
	console.log(table)
	inner = table.innerHTML
	jsons.forEach(element => foreachfun(element))
</script>
<!-- </div> -->
</html>
