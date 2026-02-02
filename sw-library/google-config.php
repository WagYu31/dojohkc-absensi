<?php
//sessoin_start();
include_once '../sw-library/sw-config.php';
// Include Librari Google Client (API)
include_once '../sw-library/google-client/Google_Client.php';
include_once '../sw-library/google-client/contrib/Google_Oauth2Service.php';

$client_id = '568878821107-n08qsm14eg2lq2e2th3br9empd3mkfr2.apps.googleusercontent.com'; // Google client ID
$client_secret = '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'; // Google Client Secret
$redirect_url = ''.$site_url.'/oauth/google'; // Callback URL

// Call Google API
$gclient = new Google_Client();
$gclient->setClientId($client_id); // Set dengan Client ID
$gclient->setClientSecret($client_secret); // Set dengan Client Secret
$gclient->setRedirectUri($redirect_url); // Set URL untuk Redirect setelah berhasil login

$google_oauthv2 = new Google_Oauth2Service($gclient);
?>
