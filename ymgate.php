<?php
if (!extension_loaded('curl')) exit("缺少 curl 扩展");
$headers = array(
    "X-Ym-User: u229740.601b922dd11be4ae147dc089fb141f980753fba3",
    "X-Ym-Ver: PHPv20250415"
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://gate.open.yimenyun.com/ymgate/?" . $_SERVER['QUERY_STRING']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
}
$resp = curl_exec($ch);
$statusCode;
if (curl_errno($ch)) {
    $statusCode = 500;
    $resp = curl_error($ch);
} else {
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    if (!empty($contentType)) header("Content-Type: " . $contentType);
    if (!empty($redirectUrl)) header("Location: " . $redirectUrl);
}
if (function_exists("http_response_code")) {
    http_response_code($statusCode);
} else {
    header("HTTP/1.1 " . $statusCode);
}
echo $resp;
curl_close($ch);