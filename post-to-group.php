<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$appId = $_ENV['FB_APP_ID'];
$appSecret = $_ENV['FB_APP_SECRET'];
session_start();

if (!isset($_SESSION['fb_access_token'])) {
  die('Access token missing. Start from index.php');
}

$fb = new \Facebook\Facebook([
  'app_id' => $appId,
  'app_secret' => $appSecret,
  'default_graph_version' => 'v19.0',
]);

$accessToken = $_SESSION['fb_access_token'];
$groupId = $_ENV['FB_GROUP_ID'];

try {
  $response = $fb->post(
    "/$groupId/feed",
    ['message' => '🚀 Auto-post from PHP to Facebook Group!'],
    $accessToken
  );

  $graphNode = $response->getGraphNode();
  echo "✅ Post successful! Post ID: " . $graphNode['id'];

} catch (Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
  echo 'SDK returned an error: ' . $e->getMessage();
}
