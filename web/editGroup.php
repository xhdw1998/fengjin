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
<html>
<meta charset="utf-8">
<form name = "input" action = "editGroup.php" method="post" align= "center">
<br>
<br>原组名:<br>
<input type="text" name="oldName"> 
<br>
新祖名：<br>
<input type="text" name="groupName"> 
<br>
是否启用，1启用 0禁用:<br>
<input type="text" name="state"> 
<br>
描述：<br>
<input type="text" name="Descr"> 
<input type="submit"> 
</div>
</form>
</html>
<?php
error_reporting(0);
lookGroup();
$GroupName = $_POST['groupName'];
$OldName = $_POST['oldName'];
$State = strval($_POST['state']);
$Descr = $_POST['Descr'];

function editGroup($GroupName,$State,$Descr,$OldName){
	$cf = new commonFunction();
	$arr = array('mafgrouplist'=>array('GroupName' =>$GroupName ,'Descr'=>$Descr,'State'=>$State,'OldName'=>$OldName ));
	$data = json_encode($arr);
	$urll='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url2='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url3='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url4='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url5='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$url6='http://IP/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafgrouplist';
	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
	$action = 'PUT';
	$strResult1 = $cf->callInterfaceCommon($url1,$action,$data,$headers);
	time_sleep_until(0.3);
	$strResult2 = $cf->callInterfaceCommon($url2,$action,$data,$headers);
	time_sleep_until(0.3);
	$strResult3 = $cf->callInterfaceCommon($url3,$action,$data,$headers);
	time_sleep_until(0.5);
	$strResult4 = $cf->callInterfaceCommon($url4,$action,$data,$headers);
	time_sleep_until(0.4);
	$strResult5 = $cf->callInterfaceCommon($url5,$action,$data,$headers);
	time_sleep_until(0.3);
	$strResult6 = $cf->callInterfaceCommon($url6,$action,$data,$headers);
	time_sleep_until(0.2);
}
if($_POST){
	if(isset($GroupName)&&isset($Descr)&&isset($State)&&isset($OldName)){
		if(empty($GroupName)&&empty($Descr)){
			echo "请填入想要更换的组名";
			echo "<script language=JavaScript> location.replace(location.href);</script>";
		}else{
			editGroup($GroupName,$State,$Descr,$OldName);
			echo "<script language=JavaScript> location.replace(location.href);</script>";
		}
	}
	
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
?>
<br>

<table id="table"  width="1600" border="1" align="center" style="text-align:center" >
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
</html>
