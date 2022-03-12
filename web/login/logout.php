<?php 
error_reporting(0);
 header("Content-Type:text/html;charset=utf-8"); 
 session_start(); 
//  if(isset($_COOKIE['username'])){
//   $_SESSION['username'] = $_COOKIE['username'];
//   $_SESSION['islogin'] = 1;
//   if(isset($_SESSION['islogin'])){

//   } else{
//     echo "还没有登陆！" exit;
//   }
// }
 //清除session 
 $username=$_SESSION['username']; 
 $_SESSION=array(); 
 session_destroy(); 
 //清除cookie 
 setcookie("username",'',time()-1); 
 setcookie("code",'',time()-1); 
 echo "$username,欢迎下次光临"; 
 echo "重新<a href='../login/login.html'>登录</a>"; 
?>