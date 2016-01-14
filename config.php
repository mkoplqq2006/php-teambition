<?php
return array(
'client_id'     => '38d0fc40-af9b-11e5-a3b9-4b84614a1630',//申请应用时分配的AppKey
'client_secret' => 'cd0082a0-5023-4793-bb1a-70384c1d19e6',//申请应用时分配的AppSecret
'redirect_uri'  => 'http://localhost:81/teambition/api.php',//回调地址
'apiURL'        => 'https://api.teambition.com',//接口域
'accountURL'    => 'https://account.teambition.com',//认证域
'_projectId'    => '5684e0a99d224f8372191c4d',//项目编号
'_stageId'      => '5684e0aa7f7ef45f5938bc2e',//阶段编号, 默认为任务分组的第一个阶段
'_tasklistId'   => '5684e0aa7f7ef45f5938bc2d',//任务列表编号
'access_token'  => '' //访问令牌,默认为空,授权后自动更新
);
?>