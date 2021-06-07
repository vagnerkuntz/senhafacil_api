<?php
require '../vendor/autoload.php';
$authenticator = new PHPGangsta_GoogleAuthenticator();
$secret = $authenticator->createSecret();
echo "Secret: ".$secret."\n"; //save this at server side

$website = 'https://vagnerkuntz.com.br'; //Your Website
$title= 'SenhaFacil - Esqueci a senha';
$qrCodeUrl = $authenticator->getQRCodeGoogleUrl($title, 'S5TCMSWD7EVMGQTV', $website);
echo $qrCodeUrl;