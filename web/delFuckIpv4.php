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
<!DOCTYPE html>
<meta charset="utf-8">
<html>
<body>
<div>
<br><br>
<form name="input" action="delFuckIpv4.php" method="post" align = "center" >
输入被封禁的IP进行搜索:
<input type="text" name="ip"> 
<input type="submit" value="搜索">
</form>

<form name="input" action="delFuckIpv4.php" method="post" align = "center" >
输入时间范围进行IP搜索:<br>
eg:2020-08-13 09:57:19 - 2020-08-13 19:57:19<br>
<input type="text" name="start_time"> — <input type="text" name="end_time"> 
<input type="submit" value="搜索">
</form>

<?php 
if($group== 'admin' || $group=='长亭科技研判'){
?>
	<form name="input" action="delFuckIpv4.php" method="post" align = "center" >
	输入时间范围进行封禁(<span class='red'>此操作直接删除,谨慎操作！！！</span>)<br>
	eg:2020-08-13 09:57:19 - 2020-08-13 19:57:19<br>
	<input type="text" name="start_time_one"> — <input type="text" name="end_time_one"> 
	<input type="submit" value="删除(谨慎操作!!!)">
	</form>
<?php 
}
?>



<div>

</div>
</body>
</html>


<?php

$GLOBALS['mysql']=new mysqli();
$GLOBALS['mysql']->connect("127.0.0.1","root","hw_zhuanyong@changting","fuck");
$GLOBALS['mysql']->set_charset("utf8");








//IP单个封禁
function deleteIPV4($DbId,$IP,$date){
	//print_r($DbId);

	if($DbId!=0){
		$mysql=$GLOBALS['mysql'];
		$sql_one="delete from ".$date." where DbId=".$DbId;
		//echo $sql_one.'<br>';
		//$sql_one="select * from ".$date;
		$result=$mysql->query($sql_one);

		$cf = new commonFunction();
		$arr = array('mafcustomv4wblist'=>array('DbId' =>$DbId));
		$data = json_encode($arr);
		$url1='http://'.$IP.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist';  //eds 1
		$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
		$action = 'DELETE';
		$strResult = $cf->callInterfaceCommon($url1,$action,$data,$headers);
		#echo "<p width='300px'  style='width=80%; text-align:center';>EDS设备:".$IP."将DbId为".$DbId."封禁记录删除成功!</p><br>";
		
	}else{
		#echo "<p width='300px'  style='width=80%; text-align:center';>EDS设备:".$IP."不存在无需删除!</p><br>";
	}

}



// 查询指定IP地址
function Search_Ip($addrIp,$date){
	$mysql=$GLOBALS['mysql'];
	$sql_one="select * from ".$date;
	$result=$mysql->query($sql_one);
	//print_r($result);
	$bool=0;
	$arrs=array("DbId"=>"","IPaddr"=>"","ModRecord"=>"","GroupStr"=>"",);
	while($res=$result->fetch_object()){

		if($res->IPaddr==$addrIp){
			#$DbId=$res->DbId;
			$arrs["DbId"]=$res->DbId;
			$arrs["IPaddr"]=$res->IPaddr;
			$arrs["ModRecord"]=$res->ModRecord;
			$arrs["GroupStr"]=$res->groups;
		
			break;
		}else{
			$bool=0;
		
		}

	}


	return $arrs;
}


//查询指定时间数据
function Search_date($start_time,$end_time,$date){
	$mysql=$GLOBALS['mysql'];
	$sql_one="select * from ".$date;
	$result=$mysql->query($sql_one);
	//print_r($result);
	$bool=0;



	$arrs[0]=0;
	$num=0;
	
	while($res=$result->fetch_object()){
		$ress=array("DbId"=>"","IPaddr"=>"","ModRecord"=>"","GroupStr"=>"");
		if($start_time<=strtotime($res->ModRecord) && $end_time>=strtotime($res->ModRecord)){
			
				$ress["DbId"]=$res->DbId;
				$ress["IPaddr"]=$res->IPaddr;
				$ress["ModRecord"]=$res->ModRecord;
				$ress["GroupStr"]=$res->groups;
			 	$arrs[$num]=$ress;

			 	$num++;
		}
		
	}
	//print_r($arrs[0]);
	return $arrs;
}




function newlist_test($EDS_ip){
	$cf = new commonFunction();
	$url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/getmafshowv4wblistcount';
    $action = 'GET';
    $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
    $data = '';
    $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
    $arr=json_decode($res);

    $val=$arr->getmafshowv4wblistcount->Count;
  
    return $arr;
}




function newlist_num($EDS_ip){
	$cf = new commonFunction();
	$url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/getmafcustomv4wblistcount';
    $action = 'GET';
    $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
    $data = '';
    $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
    $arr=json_decode($res);
   	$val=$arr->getmafcustomv4wblistcount->Count;
   	
    return $val;
}

//对象数组转换成普通数组
function std_class_object_to_arrray($stdclassobject){

	$_array=is_object($stdclassobject)?get_object_vars($stdclassobject):$stdclassobject;



	foreach($_array as $key=>$value){
		$value=(is_array($value)||is_object($value))?std_class_object_to_array($value):$value;
		$array[$key]=$value;
	}

	echo '11111';
	return $array;

}

function newlist_test1($EDS_ip){
	$cf = new commonFunction();
	$url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset=100&count=5';
    $action = 'GET';
    $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
    $data = '';
    $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
    $arr=json_decode($res);
  	
  	 foreach($arr->mafcustomv4wblist as $val){
  	 	print_r($val);
  	 	echo '<br>';
  	 }
    // print_r($res);
    // echo '<hr>';
    // print_r(array_reverse($arr->mafcustomv4wblist));
}

//newlist_test1('172.17.242.89');




//从EDS中获取IP被封禁Ip的数量
function newlist_num1($EDS_ip,$zong_num,$add_list){
	echo '<hr>';
	echo $zong_num;
	echo '<br>';
	echo $add_list;
	$offset=$zong_num-$add_list;
	echo $offset;
	echo '<br>';
	echo '<hr>';



	$cf = new commonFunction();
    $url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$offset.'&count=200';
    $action = 'GET';
    $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
    $data = '';
    $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
    $arr=json_decode($res);
    foreach($arr->mafcustomv4wblist as $val){
    	$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord)VALUES("'.$val->DbId.'","'.$val->IPaddr.'","'.$val->ModRecord.'")';
	   	$result1=$mysql->query($sql1);
    }


}


//往数据库中写入被封禁的数据 
function newlist($EDS_ip,$date){
	$tiao=0;
	$mysql=$GLOBALS['mysql'];
    $offset=0;
    $num=1;
    $cf = new commonFunction();
    $sql_one="SELECT * FROM ".$date;
    $result_a=$mysql->query($sql_one);
    $date_nums=$result_a->num_rows;


    $Neweds_date=newlist_num($EDS_ip);

 

     if($Neweds_date==$date_nums){ 
     echo '新获取的数据条目:'.$Neweds_date."<br>";
 	echo '数据库的数据条目:'.$date_nums."<br>";
      echo "数据无需变动！<br> <hr>";

     }else{

     	$add_list=$Neweds_date-$date_nums;
 		 echo '新获取的数据条目:'.$Neweds_date."<br>";
 		 echo '数据库的数据条目:'.$date_nums."<br>";
 	
 		echo '需要新增的数据条目:'.$add_list."<br>";

 		$nums=$date_nums;
 		$old_date=$date_nums;

 		if($add_list>200){

 			#print("数据大于2000开始新增");
 			
 			$num_one=1;
		    while($num){
		        $num++;
		       	
		       
		        $url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$offset.'&count=100';
		        $action = 'GET';
		        $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
		        $data = '';
		        $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
		        $arr=json_decode($res);
		 		$tiao=$tiao+count($arr->mafcustomv4wblist);

				foreach($arr->mafcustomv4wblist as $val){
					
					if($nums<$Neweds_date){

						$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord,groups)VALUES("'.$val->DbId.'","'.$val->IPaddr.'","'.$val->ModRecord.'","'.$val->GroupStr.'")';
		   				$result1=$mysql->query($sql1);
		   		

					}
					$nums++;
				}

		        if(count($arr->mafcustomv4wblist)<100){
						return $tiao;
		        }

		        $offset=$offset+100;

		         }

 		}else{
 			$offset=$date_nums;

 			//如果查询出来的新增是一条则单独处理
 			if($add_list==1){
 				echo '在这里增加';
 			
	 			$url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$offset.'&count='.$add_list;
		        $action = 'GET';
		        $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
		        $data = '';
		        $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
		        $arr=json_decode($res);
		        echo '<hr>'.$arr->mafcustomv4wblist->IPaddr.'<hr>';	
		       
		 		$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord,groups)VALUES("'.$arr->mafcustomv4wblist->DbId.'","'.$arr->mafcustomv4wblist->IPaddr.'","'.$arr->mafcustomv4wblist->ModRecord.'","'.$arr->mafcustomv4wblist->GroupStr.'")';

		   		$result1=$mysql->query($sql1);
		
		 		return 0;
 			}

 				$offset_b=$Neweds_date-$add_list;
 				$url='http://'.$EDS_ip.'/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset='.$offset_b.'&count='.$add_list;
 				#echo $url;
		        $action = 'GET';
		        $headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
		        $data = '';
		        $res = $cf->callInterfaceCommon($url,$action,$data,$headers);
		        $arr=json_decode($res);
		 		$arrs=array_reverse($arr->mafcustomv4wblist);
		 		//print_r($arrs);
		 		foreach($arrs as $val){
		 			#echo '<hr>'.$val->IPaddr.'<hr>';	
		 			#$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord)VALUES("'.$val->DbId.'","'.$val->IPaddr.'","'.$val->ModRecord.'")';
		 			$sql1='INSERT INTO '.$date.'(DbId,IPaddr,ModRecord,groups)VALUES("'.$val->DbId.'","'.$val->IPaddr.'","'.$val->ModRecord.'","'.$val->GroupStr.'")';
		   			

		   			$result1=$mysql->query($sql1);
		 		}
		 	
 			
 		}
 		

      }




}






// function lookFuckipv4(){
// 	$cf = new commonFunction();
// 	$url='http://172.17.225.88/func/web_main/api/maf/maf_addrfilter/maf_addrfilter/mafcustomv4wblist?offset=200&count=400';
// 	$action = 'GET';
// 	$headers=array("Accept: application/json","Content-Type: application/json","Authorization: Basic YWRtaW46Skp5aF9FRFNAV2g5NjIwMg==");
// 	$data = '';
// 	$res = $cf->callInterfaceCommon($url,$action,$data,$headers);
// 	$GLOBALS['result'] = $res;
// }

?>
<br>
<?php

$Ip = trim($_POST['ip']);
	
	newlist('172.17.226.89','date');
	newlist('172.17.242.89','date_one');
	newlist('172.17.225.88','date_two');
	newlist('172.17.241.88','date_three');
	newlist('172.22.9.17','date_four');
	newlist('172.22.9.18','date_five');


$eds_ip=Search_Ip($Ip,'date');
$eds_one=Search_Ip($Ip,'date_one');
$eds_two=Search_Ip($Ip,'date_two');
$eds_three=Search_Ip($Ip,'date_three');
$eds_four=Search_Ip($Ip,'date_four');
$eds_five=Search_Ip($Ip,'date_five');

$info_list[0]=$eds_ip;
$info_list[1]=$eds_one;
$info_list[2]=$eds_two;
$info_list[3]=$eds_three;
$info_list[4]=$eds_four;
$info_list[5]=$eds_five;


// 				$edsTime=array();
// 				$edsTime[0]=Search_date($start_time,$end_time,'date');
// 				$edsTime[1]=Search_date($start_time,$end_time,'date_one');
// 				$edsTime[2]=Search_date($start_time,$end_time,'date_two');
// 				$edsTime[3]=Search_date($start_time,$end_time,'date_three');
// 				$edsTime[4]=Search_date($start_time,$end_time,'date_four');
// 				$edsTime[5]=Search_date($start_time,$end_time,'date_five');
// if(isset($_GET['time'])){

// 		while($num_while<=5){
		
// 				if(true){
// 					for($i=0;$i<count($edsTime[$num_while]);$i++){

// 						print_r($edsTime[$num_while][$i]['DbId']);
						

// 						$delnum++;
// 					}
					

// 				}else{

// 				}



// }

if($_GET['cmd']=='del_all'){

	



	deleteIPV4($_GET['num1'],'172.17.226.89','date');
	deleteIPV4($_GET['num2'],'172.17.242.89','date_one');
	deleteIPV4($_GET['num3'],'172.17.225.88','date_two');
	deleteIPV4($_GET['num4'],'172.17.241.88','date_three');
	deleteIPV4($_GET['num5'],'172.22.9.17','date_four');
	deleteIPV4($_GET['num6'],'172.22.9.18','date_five');
 }

 if(isset($_POST['start_time_one'])&& isset($_POST['end_time_one'])){

	$start_time_one=strtotime($_POST['start_time_one']);
	$end_time_one=strtotime($_POST['end_time_one']);

	$num_while_one=0;
	$Del_id_one=array();
	$delnum_one=0;
	$why_del_one=0;
	$eds_list_one=['172.17.226.89','172.17.242.89','172.17.225.88','172.17.241.88','172.22.9.17','172.22.9.18'];
	$eds_list_date=['date','date_one','date_two','date_three','date_four','date_five'];

	$edsTime_one=array();
	$edsTime_one[0]=Search_date($start_time_one,$end_time_one,'date');
	$edsTime_one[1]=Search_date($start_time_one,$end_time_one,'date_one');
	$edsTime_one[2]=Search_date($start_time_one,$end_time_one,'date_two');
	$edsTime_one[3]=Search_date($start_time_one,$end_time_one,'date_three');
	$edsTime_one[4]=Search_date($start_time_one,$end_time_one,'date_four');
	$edsTime_one[5]=Search_date($start_time_one,$end_time_one,'date_five');
?>
<h2><?php echo '目前删除的时间范围是:'.($_POST['start_time_one'].'-'.$_POST['end_time_one']).''; ?></h2>

<?php



	while($num_while_one<=5){
		print("设备".$num_while_one);
		for($i=0;$i<count($edsTime_one[$num_while_one]);$i++){
		
			$dbid=$edsTime_one[$num_while_one][$i]['DbId'];
			deleteIPV4($dbid,$eds_list_one[$num_while_one],$eds_list_date[$num_while_one]);
			$delnum_one++;
		}
		$num_while_one++;
		echo '<hr>';
	}


	 del_timedes($start_time_one,$start_time_one);
		

 }


//ip列表范围擦汗寻
if(isset($_POST['text_ip'])){
	$text_ip=$_POST['text_ip'];
	$Ntext_ip=explode(",",$text_ip);




	for($i=0;$i<count($Ntext_ip);$i++){
		print_r($Ntext_ip[$i]);
		// $eds1=Search_Ip($Ntext_ip[$i],'date');
		// parint_r($eds1);


		$i++;
	}

// $eds_ip=Search_Ip($Ip,'date');
// $eds_one=Search_Ip($Ip,'date_one');
// $eds_two=Search_Ip($Ip,'date_two');
// $eds_three=Search_Ip($Ip,'date_three');
// $eds_four=Search_Ip($Ip,'date_four');
// $eds_five=Search_Ip($Ip,'date_five');

// $info_list[0]=$eds_ip;
// $info_list[1]=$eds_one;
// $info_list[2]=$eds_two;
// $info_list[3]=$eds_three;
// $info_list[4]=$eds_four;
// $info_list[5]=$eds_five;

	// for($i=0;$i<count($Ntext_ip);$i++){

	// 		$num_while_one=0;
	// 		$eds_list_one=['172.17.226.89','172.17.242.89','172.17.225.88','172.17.241.88','172.22.9.17','172.22.9.18'];
	// 		while($num_while_one<=5){
	// 			print_r($)

	// 			// echo '<hr>';
	// 			// if($info_list[$num_while]['DbId']){
	// 			// 	print('<tr>
	// 			// 		<td>'.$eds_list[$num_while].'</td>
	// 			// 		<td class="green">'.$info_list[$num_while]['DbId'].'</td>
	// 			// 		<td>'.$info_list[$num_while]['GroupStr'].'</td>
	// 			// 		<td>'.$info_list[$num_while]['ModRecord'].'</td>
	// 			// 	</tr>');

	// 			// }else{
	// 			// 	print('<tr>
	// 			// 		<td>'.$eds_list[$num_while].'</td>
	// 			// 		<td class="red">0000</td>
	// 			// 		<td class="red">0000-00-00 00</td>
	// 			// 		<td class="red">00000</td>
	// 			// 	</tr>');
	// 			// }
				


	// 			$num_while_one++;

	// 		}


	// 	#print($Ntext_ip[$i]);

	// }
	

}

//按照时间

if(isset($_POST['start_time'])&& isset($_POST['end_time'])){


	$start_time=strtotime($_POST['start_time']);
	$end_time=strtotime($_POST['end_time']);



	#print(strtotime($info_list[1]['ModRecord']));
	#
?>

<h2><?php echo '目前查询的时间范围是:'.($_POST['start_time'].'-'.$_POST['end_time']).''; ?></h2>





<table>

<!-- 
			<tr>
			<th class="td1">EDS设备</th>
			<th class="td1">DbId</th>
			<th class="aaa2">分组信息</th>
			<th class="aaa1">封禁时间</th>
		
			</tr> -->

		<?php

			$num_while=0;
			$Del_id=array();
			$delnum=0;
			$why_del=0;
			$eds_list=['172.17.226.89','172.17.242.89','172.17.225.88','172.17.241.88','172.22.9.17','172.22.9.18'];

			// $edsTime=Search_date($start_time,$end_time,'date');
			// $edsTime_one=Search_date($start_time,$end_time,'date_one');
			// $edsTime_two=Search_date($start_time,$end_time,'date_two');
			// $edsTime_three=Search_date($start_time,$end_time,'date_three');
			// $edsTime_four=Search_date($start_time,$end_time,'date_four');
			// $edsTime_five=Search_date($start_time,$end_time,'date_five');


				$edsTime=array();
				$edsTime[0]=Search_date($start_time,$end_time,'date');
				$edsTime[1]=Search_date($start_time,$end_time,'date_one');
				$edsTime[2]=Search_date($start_time,$end_time,'date_two');
				$edsTime[3]=Search_date($start_time,$end_time,'date_three');
				$edsTime[4]=Search_date($start_time,$end_time,'date_four');
				$edsTime[5]=Search_date($start_time,$end_time,'date_five');




				// print_r($edsTime[0]['id']);
			while($num_while<=5){
				//$num=0;
				echo '<hr><h3>EDS设备'.$num_while.':'.$eds_list[$num_while].'</h3>';

				print("<table>");
				if(true){
					for($i=0;$i<count($edsTime[$num_while]);$i++){

						$Del_id[$delnum]=$edsTime[$num_while][$i]['DbId'];
						print('<tr>
						<td>'.$edsTime[$num_while][$i]['IPaddr'].'</td>
						<td class="green">'.$edsTime[$num_while][$i]['DbId'].'</td>
						<td>'.$edsTime[$num_while][$i]['GroupStr'].'</td>
						<td>'.$edsTime[$num_while][$i]['ModRecord'].'</td>
						</tr>');

						$delnum++;
					}
					

				}else{

				}
				print("<table>");
	// if(strtotime($info_list[$num_while]['ModRecord'])>$start_time && strtotime($info_list[$num_while]['ModRecord'])<$end_time){
	// 				print('<tr>
	// 					<td>'.$eds_list[$num_while].'</td>
	// 					<td class="green">'.$eds_ip['DbId'].'</td>
	// 					<td>'.$eds_ip['GroupStr'].'</td>
	// 					<td>'.$eds_ip['ModRecord'].'</td>
	// 				</tr>');

	// 			}else{
	// 				print('<tr>
	// 					<td>'.$eds_list[$num_while].'</td>
	// 					<td class="red">0000</td>
	// 					<td class="red">0000-00-00 00</td>
	// 					<td class="red">00000</td>
	// 				</tr>');
	// 			}

				$num_while++;

				
				echo '<hr>';
			}
			//删除按照时间查询的所有封禁
		

			

			#print_r($Del_id);
		?>
<!-- 	<a class="btn" href="delFuckIpv4.php?time=del_time_all">删除全部封禁</a> -->
	</table>

<?php




}
//120.39.41.15
if(isset($_POST['ip'])){


?>
<div id="Search_Ip" align="center">
	<h3>IP:<?php echo $Ip; ?>查询结果</h3>

	<table>


			<tr>
			<th class="td1">EDS设备</th>
			<th class="td1">DbId</th>
			<th class="aaa2">分组信息</th>
			<th class="aaa1">封禁时间</th>
		
			</tr>

		<?php

			$num_while=0;
			$eds_list=['172.17.226.89','172.17.242.89','172.17.225.88','172.17.241.88','172.22.9.17','172.22.9.18'];
			while($num_while<=5){
				echo '<hr>';
				if($info_list[$num_while]['DbId']){
					print('<tr>
						<td>'.$eds_list[$num_while].'</td>
						<td class="green">'.$info_list[$num_while]['DbId'].'</td>
						<td>'.$info_list[$num_while]['GroupStr'].'</td>
						<td>'.$info_list[$num_while]['ModRecord'].'</td>
					</tr>');

				}else{
					print('<tr>
						<td>'.$eds_list[$num_while].'</td>
						<td class="red">0000</td>
						<td class="red">0000-00-00 00</td>
						<td class="red">00000</td>
					</tr>');
				}
				


				$num_while++;

			}


		?>
	</table>

<!-- 
	<ul>
		<li><p class='left'>EDS设备:172.17.226.89</p>  <p class="rigt"><?php $text=($eds_ip['DbId']!=0)?("<span>分组信息:".$eds_ip['GroupStr']."</span><span>封禁时间:".$eds_ip['ModRecord']."</span><span>设备DbId:<span class='green'>".$eds_ip['DbId']."</span></span>"):("<span class='red'>此设备未封禁此IP</span>"); echo $text;?></p> </li>








		<li><p class='left'>EDS设备:172.17.242.89</p>  <p class="rigt">设备DbId:<?php $text=($eds_one['DbId']!=0)?("<span class='green'>".$eds_one['DbId']."</span>"):("<span class='red'>此设备未封禁此IP</span>"); echo $text;?></p> </li>
		<li><p class='left'>EDS设备:172.17.225.88</p>  <p class="rigt">设备DbId:<?php $text=($eds_two['DbId']!=0)?("<span class='green'>".$eds_two['DbId']."</span>"):("<span class='red'>此设备未封禁此IP</span>"); echo $text;?></p> </li>
		<li><p class='left'>EDS设备:172.17.241.88</p>  <p class="rigt">设备DbId:<?php $text=($eds_three['DbId']!=0)?("<span class='green'>".$eds_three['DbId']."</span>"):("<span class='red'>此设备未封禁此IP</span>"); echo $text;?></p> </li>
		<li><p class='left'>EDS设备:172.22.9.17</p>  <p class="rigt">设备DbId:<?php $text=($eds_four['DbId']!=0)?("<span class='green'>".$eds_four['DbId']."</span>"):("<span class='red'>此设备未封禁此IP</span>"); echo $text;?></p> </li>
		<li><p class='left'>EDS设备:172.22.9.18</p>  <p class="rigt">设备DbId:<?php $text=($eds_five['DbId']!=0)?("<span class='green'>".$eds_five['DbId']."</span>"):("<span class='red'>此设备未封禁此IP</span>"); echo $text;?></p> </li>

	</ul> -->



<?php 
if($group== 'admin' || $group=='长亭科技研判'){
?>
	<a class="btn" href="delFuckIpv4.php?cmd=del_all&num1=<?php echo $eds_ip['DbId'];?>&num2=<?php echo $eds_one['DbId'];?>&num3=<?php echo $eds_two['DbId'];?>&num4=<?php echo $eds_three['DbId'];?>&num5=<?php echo $eds_four['DbId'];?>&num6=<?php echo $eds_five['DbId'];?>">删除全部封禁</a>
<?php
}
?>
</form>

</div>
<?php
}


?>





<style>
	#Search_Ip{}
	#Search_Ip a{ display:inline-block; width:300px; height:34px; background:black; color:white;  line-height: 34px;  margin-top:30px; text-decoration: none;}




	#Search_Ip ul{

	}
	#Search_Ip ul li{
		width:58%;

	}
	#Search_Ip ul li p{
		
	}
	#Search_Ip ul li .x_p{
		display:inline;
		
	}
    #Search_Ip ul li span{
		float:left;

	}
	#Search_Ip ul li .left{
		width:30%;
		float:left;
		background:orange;
		text-align:left;
	}
	#Search_Ip ul li .rigt{
		width:70%;
		float:left;
		background:pink;
		text-align:right;
	}

	#Search_Ip ul li span{
			background:yellow;
	}
	.red{
		color:red;
	}
	.green{
		color:green;
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
