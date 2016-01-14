<?php
error_reporting(E_ALL & ~E_NOTICE);
header("Content-type: text/html; charset=utf-8");
$config = include('config.php');
$code = $_GET["code"];
if(!empty($code))
{
	if($_COOKIE["access_token"]){
		$config['access_token'] = $_COOKIE["access_token"];
	}else{
		$responseText = send_post($config['accountURL'].'/oauth2/access_token',array(
			'client_id' => $config['client_id'],
			'client_secret' => $config['client_secret'],
			'code' => $code
		));
		$json = json_decode($responseText,true);
		$config['access_token'] = $json['access_token'];//存放令牌
		setcookie("access_token", $json['access_token'], time()+3600*24*7);
	}
	addTask($config);
}else{
	echo "非法操作";
}
exit();
//创建任务
function addTask($config){
	echo send_post2($config['apiURL']."/api/tasks",array(
		'content'  	  => '测试订单',
		'_tasklistId' => $config['_tasklistId'],
		'_stageId' => $config['_stageId']
	),$config['access_token']);
}
//验证access_token是否合法
function validateToken($config){
	echo send_get2($config['apiURL']."/api/applications/".$config['client_id']
		."/tokens/check",$config['access_token']);
}
//获取我的任务
function getMeTask($config){
	echo send_get($config['apiURL']."/api/v2/tasks/me",$config['access_token'],array(
		'isDone'  	  => true
	));
}
//获取阶段下的任务
function getStageTask($config){
	echo send_get($config['apiURL']."/api/stages/".$config['_stageId']."/tasks",$config['access_token']);
}
//获取个人信息
function getUer($config){
	echo send_get($config['apiURL']."/api/users/me",$config['access_token']);
}
//获取项目表下的任务
function getProjects($config){
	echo send_get($config['apiURL']."/api/projects/".$config['_projectId']."/tasks",$config['access_token']);
}
/** 
 * 发送post请求 
 * @param string $url 请求地址 
 * @param array $param post键值对数据
 * @param array $token 令牌
 * @return string 
 */  
function send_post($url, $param, $token=null) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO, getcwd().'/cacert.pem');//证书地址
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl, CURLOPT_POST, true); // post传输数据
    if(!empty($param)){
    	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($param));
    }
    if(!empty($token)){
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type:text/html;charset=utf-8',
			'Authorization:OAuth2 '.$token
		));//头部信息
    }
    $responseText = curl_exec($curl);
    // var_dump(curl_error($curl));//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);
    return $responseText;
}
/** 
 * 发送post请求 
 * @param string $url 请求地址 
 * @param array $param post键值对数据
 * @param array $token 令牌
 * @return string 
 */  
function send_post2($url, $param, $token=null) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO, getcwd().'/cacert.pem');//证书地址
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl, CURLOPT_POST, true); // post传输数据
    if(!empty($param)){
    	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));//注意这里使用json_encode
    }
    if(!empty($token)){
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type:application/json',
			'Authorization:OAuth2 '.$token
		));//头部信息
    }
    $responseText = curl_exec($curl);
    // var_dump(curl_error($curl));//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);
    return $responseText;
}
/** 
 * 发送get请求 
 * @param string $url 请求地址
 * @param string $token 合法令牌
 * @param string $param 参数
 * @return string 
 */  
function send_get($url, $token, $param=null) {
	if(!empty($param)){
		$url.= '?'.http_build_query($param);
	}
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO, getcwd().'/cacert.pem');//证书地址
	if(!empty($token)){
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type:text/html;charset=utf-8',
			'Authorization:OAuth2 '.$token
		));//头部信息
	}
	$responseText = curl_exec($curl);
	//var_dump(curl_error($curl));//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);
	return $responseText;
}
/** 
 * 发送get请求 
 * @param string $url 请求地址
 * @param string $token 合法令牌
 * @param string $param 参数
 * @return string 
 */  
function send_get2($url, $token, $param=null) {
	if(!empty($param)){
		$url.= '?'.http_build_query($param);
	}
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	curl_setopt($curl, CURLOPT_CAINFO, getcwd().'/cacert.pem');//证书地址
	if(!empty($token)){
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type:application/json',
			'Authorization:OAuth2 '.$token
		));//头部信息
	}
	$responseText = curl_exec($curl);
	//var_dump(curl_error($curl));//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	curl_close($curl);
	return $responseText;
}
?>