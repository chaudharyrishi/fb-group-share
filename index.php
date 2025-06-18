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
$permissions = ['publish_to_groups', 'groups_access_member_info'];

$callbackUrl = htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . '/fb-callback.php');
$loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Facebook Group Share</title>
</head>
<body>
  <h2>Share a Post to Facebook Group</h2>

  <button onclick="window.location='<?php echo $loginUrl; ?>'">Share to Group</button>
</body>
</html>
