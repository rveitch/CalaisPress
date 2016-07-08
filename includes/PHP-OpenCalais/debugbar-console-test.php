<?php
$apikey = "yZGIC9Fbo5Nd5MGUSxNyp1a6HbnjqPXY";

$oc = new OpenCalais($apikey);

$content = 'If Yahoo directors refuse to negotiate a deal within three weeks, Microsoft plans to nominate a board slate and take its case to investors, Chief Executive Officer Steve Ballmer said April 5 in a statement. He suggested the deal value might decline if Microsoft has to take those steps.';

$entities = $oc->getJSON($content);

echo '<pre>';
print_r($entities);
