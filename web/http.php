<?php
session_start();
$islogin = $_SESSION['islogin'];
if(($islogin) == 1){
    //continue;
}else{

    header("location:./login/login.html");


}
/**
 * http.php
 * 用来向服务器的RESTful API发起各类HTTP请求的工具函数。
 *
 * 使用: http://mysite.com/http.php?action=xxx
 * xxx \in {get,post,put,patch,delete}
 *
 * Created by Sublime.
 * User: Dale
 * Date: 20/6/10
 * Time: 下午5:22
 */
class commonFunction{
    function callInterfaceCommon($URL,$type,$params,$headers){
        $ch = curl_init($URL);
        $timeout = 5;
        if($headers!=""){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
        }else {
            curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        switch ($type){
            case "GET" : curl_setopt($ch, CURLOPT_HTTPGET, true);break;
            case "POST": curl_setopt($ch, CURLOPT_POST,true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PUT" : curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
            case "PATCH": curl_setopt($ch, CULROPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);break;
            case "DELETE":curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);break;
        }
        $file_contents = curl_exec($ch);//获得返回值
        return $file_contents;
        curl_close($ch);
    }
}

$cf = new commonFunction();