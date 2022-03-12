<?php
//error_reporting(0);
session_start();
$islogin = $_SESSION['islogin'];
if(($islogin) == 1){
    //continue;
}else{

    header("location:./login/login.html");


}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ban ip</title>
<style>
*{
    padding:0;
    margin:0;
}
.menu{font-family:"华文软体"; width:980px; margin:50px auto;}
.menu ul {
list-style: none;
}
.menu ul li {
float:left;
position:relative;
}
.menu ul li a, .menu ul li a:visited {
display:block;
text-align:center;
text-decoration:none;
width:150px;
height:35px;
color:#000;
border:1px solid #fff;
border-width:1px 1px 0 0;
background:#c9c9a7;
line-height:30px;
font-size:20px;
}
.menu ul li ul {
display: none;
}
.menu ul li:hover a {
color:#fff;
background:#b3ab79;
}
.menu ul li:hover ul {
display:block;
position:absolute;
top:31px;
left:0;
width:105px;
}
.menu ul li:hover ul li a {
display:block;
background:#faeec7;
color:#000;
}
.menu ul li:hover ul li a:hover {
background:#dfc184;
color:#000;
}
</style>
</head>

<body>
<h2 style = "position:absolute;right:750px;top:1px;z-index:999;">欢迎大佬<?php echo $_SESSION['username']?>使用封禁系统</h2>
<a href='http:///group/login/logout.php' style="position:absolute;right:50px;top:1px;z-index:999;">注销</a>
<div class="menu" >
<?php 
if($_SESSION['group'] == 'admin' || $_SESSION['group'] == '长亭科技研判' ){
    echo "<ul>";
    echo "<li><a href='#'>分组管理</a>";
    echo "<ul>";    
    echo "<li><a href='http://10.19.195.231/group/addGroup.php' >新增分组</a></li>";
    echo "<li><a href='http://10.19.195.231/group/delGroup.php' >删除分组</a></li>";
    echo "<li><a href='http://10.19.195.231/group/editGroup.php'>修改分组</a></li>";
    echo "</ul>";
    echo "</li>";
}else{
    echo "<ul>";
    echo "<li><a href='#'>分组管理</a>";
    echo "<ul>";
    echo "<li><a href='#' >新增分组</a></li>";
    echo "<li><a href='#' >删除分组</a></li>";
    echo "<li><a href='#'>修改分组</a></li>";
    echo "</ul>";
    echo "</li>";
}
?>
<li><a href="#">封禁管理</a>
<ul>
<li><a href="http://10.19.195.231/group/fuckIpv4.php" >封禁IP</a></li>
<li><a href="http://10.19.195.231/group/delFuckIpv4.php" >搜索/删除封禁</a></li>

<li><a href="#" >修改封禁</a></li>
</ul>
</li>
<li><a href="href="http://10.19.195.231/group/copy.php">eds系统</a>
<ul>
<li><a href="http://" >系统一</a></li>
<li><a href="http://" >系统二</a></li>
<li><a href="http://" >系统三</a></li>
<li><a href="http://" >系统四</a></li>
<li><a href="http://" >系统五</a></li>
<li><a href="http://" >系统六</a></li>
</ul>
</li>
<li><a href="#">每日总结</a>
<ul>
<li><a href="#" >黑名单事件</a></li>
<li><a href="#" >错误封禁事件</a></li>
<li><a href="#" ></a></li>
</ul>

<li><a href="#">使用说明</a>
<li><a href="#">问题反馈</a>

</ul>
<div class="clear"> </div>
</div>
</body>
</html>