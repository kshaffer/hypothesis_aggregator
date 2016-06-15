<?php
include('httpful.phar');
echo 'test';
$uri = "https://hypothes.is/api/search?user=kris.shaffer@hypothes.is";
$response = \Httpful\Request::get($uri)->send();

echo "$response\n";
?>
