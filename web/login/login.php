<?php 
error_reporting(0);
header("Content-Type:text/html;charset=utf-8"); 
session_start(); 

$link = mysqli_connect("localhost","root","hvv2020","fuck");
if($link){
  if(isset($_POST['login'])){
    mysqli_query($link,'SET NAMES UTF8');
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $key = $_POST['key'];
    $stmt = mysqli_prepare($link,"SELECT pass FROM users WHERE user = ?");
    $stmt -> bind_param('s',$user);
    $stmt -> execute();
    $stmt -> bind_result($password);
    $stmt -> fetch();
    $stmt -> close(); 
    if($password == $pass && $key == "!!13579!!"){
      $_SESSION['username']=$user; 
      $_SESSION['islogin']=1; 
      if($user == 'admin' || $user == 'an' || $user =='g'){
        $_SESSION['group'] = 'admin';
      }elseif($user=='admin1'){
        $_SESSION['group']='xx科技';
      }elseif($user == 'aa@001' ){
        $_SESSION['group'] = 'x藤科技';
      }elseif ($user == 'aa@001') {
        $_SESSION['group'] = 'x来网络';
      }elseif ($user == 'aa@001' || $user == 'lm@002') {
        $_SESSION['group'] = 'x盟科技';
      }elseif ($user == 'aa@001' || $user == 'dp@002') {
        $_SESSION['group'] = 'x普科技';
      }elseif ($user == 'aa@001') {
        $_SESSION['group'] = 'x讯科技';
      }
      setcookie("username",'',time()-1); 
      setcookie("code",'',time()-1);  
      header('refresh:1;url=../fuckipv4.php');
    }else{
      echo "用户名或密码错误";
      header('refresh:1;url=./login.html');
      //echo "<script>setTimeout(function(){window.location.href='login.html';},1000);</script>";//如果错误使用js 1秒后跳转到登录页面重试;
    }
  }
}
?>