<?php

function getMethod() 
{
	return $_SERVER["REQUEST_METHOD"]; 
}

function getPostData()
{
	return http_build_query($_POST);
}

function getPutOrDeleteData($url)
{
	$data = substr(file_get_contents('php://input'), strlen($url));
	return $data;
}

function makePostRequest($data, $url)
{
	$httpHeader = [
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data),
	];

	return makePutOrPostCurl('POST', $data, true, $httpHeader, $url);
}

function makePutRequest($data, $url)
{
	return makePutOrPostCurl('PUT', $data, true, $httpHeader, $url);
}

function makeDeleteRequest($url)
{
	$ch = initCurl($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function makeGetRequest($url)
{
	$ch = initCurl($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}

function makePutOrPostCurl($type, $data, $returnTransfer, $httpHeader, $url)
{
	$ch = initCurl($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);

	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function initCurl($url)
{
    // CHANGE THIS PART TO USE A PROXY SERVER
    $proxyAddress = null;
    $proxyPort = null;
    $proxyType = CURLPROXY_HTTP;
    
    $httpHeader = [
        'Content-Type: application/x-www-form-urlencoded',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);

    if ($proxyAddress && $proxyPort) {
        curl_setopt($ch, CURLOPT_PROXY, $proxyAddress . ':' . $proxyPort);
        curl_setopt($ch, CURLOPT_PROXYTYPE, $proxyType);
    }

    return $ch;
}

// -------------------------------------------------------------------

$url = explode($_SERVER['SCRIPT_NAME'], $_SERVER['REQUEST_URI']);

if(empty($url[1])) {
	die("You need to pass in a target URL.");
}

$url = "https://api.telegram.org" . $url[1];

switch (getMethod()) {
	case 'POST':
		$response = makePostRequest(getPostData(), $url);
		break;
	case 'PUT':
		$response = makePutRequest(getPutOrDeleteData($url), $url);
		break;
	case 'DELETE':
		$response = makeDeleteRequest($url);
		break;
	case 'GET':
		$response = makeGetRequest($url);
		break;
	default:
		die("This proxy only supports POST, PUT, DELETE AND GET REQUESTS.");
}

echo $response;