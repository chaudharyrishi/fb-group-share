<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$appId = $_ENV['FB_APP_ID'];
$appSecret = $_ENV['FB_APP_SECRET'];
session_start();

$fb = new \Facebook\Facebook([
  'app_id' => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => 'v19.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();

  if (!isset($accessToken)) {
    die('Access Token not received');
  }

  // Optionally extend the token
  $oAuth2Client = $fb->getOAuth2Client();
  $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

  $_SESSION['fb_access_token'] = (string) $longLivedAccessToken;

  header('Location: post-to-group.php');
  exit;

} catch (Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph Error: ' . $e->getMessage();
  exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
  echo 'SDK Error: ' . $e->getMessage();
  exit;
}
