<?php
$config = include('config.php');
$location = $config['accountURL']
."/oauth2/authorize?client_id=".$config['client_id']
."&redirect_uri=".$config['redirect_uri'];
header("Location:".$location);
?>