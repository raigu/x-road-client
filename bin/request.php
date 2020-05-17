<?php

if ($argc != 4) {
    echo <<<EOD
Read X-road request from STDIN and print out service response
 
Usage: php ./bin/request.php service client security_server_url

Example: 
$ echo "<prod:RR437 xmlns:prod="http://rr.x-road.eu/producer"><request><Isikukood>00000000000</Isikukood></request></prod:RR437>" | \
  php ./bin/request.php EE/GOV/70008440/rr/RR437/v1 EE/COM/00000000/gis https://security-server.client.com

EOD;

    exit(1);
}

require_once __DIR__ . '/../vendor/autoload.php';

$service = \Raigu\XRoad\Service::create(
    $argv[1],
    $argv[2],
    \Raigu\XRoad\SecurityServer::create(
        $argv[3]
    )
);

$response = $service->request(
    file_get_contents("php://stdin")
);

echo $response;

echo "\n";

