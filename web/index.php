<!-- <!DOCTYPE html>
<html>
<meta charset="utf-8">
<head>
<style>
ul{list-style-type:none;margin:0;padding:0;overflow:hidden;}li{float:left;}
a{display:block;width:150px;background-color:#ffffff;}
</style>
</head>
</head>
<body>
<ul>
<li><a href="addGroup.php">添加分组</a></li>
<li><a href="delGroup.php">删除分组</a></li>
<li><a href="editGroup.php">修改分组</a></li>
<li><a href="FuckIpv4.php">添加封禁ipv4地址</a></li>
</ul>
</body>
</html> -->
<?php 


function netStatus($strResult1,$strResult2,$strResult3,$strResult4,$strResult5,$strResult6){
	
	if($strResult1 !== ''){
		$str1 = '设备1发送成功';
	}else{
		$str1 = '设备1未发送成功';
		}
	if($strResult2 !== ''){
		$str2 = '设备2发送成功';
	}else{
		$str2 = '设备2未发送成功';
		}
	if($strResult3 !== ''){
		$str3 = '设备3发送成功';
	}else{
		$str3 = '设备3未发送成功';
	}if($strResult4 !== ''){
		$str4 = '设备4发送成功';
	}else{
		$str4 = '设备4未发送成功';
	}if($strResult5 !== ''){
		$str5 = '设备5发送成功';
	}else{
		$str5 = '设备5未发送成功';
	}if($strResult6 !== ''){
		$str6 = '设备6发送成功';
	}else{
		$str6 = '设备6未发送成功';
	}
	
	//echo "<script>alert($str1)</script>";

}

?>

