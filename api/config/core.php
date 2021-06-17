<?php
error_reporting(E_ALL);
 
date_default_timezone_set('America/Sao_Paulo');
 
$key = "PamKEekdPasdczx";
$issued_at = new DateTimeImmutable();
$expiration_time = $issued_at->modify('+1 minutes')->getTimestamp();
$issuer = "https://vagnerkuntz.com.br";
