<?php

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");


$campaign = new MeTon\Core\Email\Campaigns\Custom();
$campaign->setSubject($argv[1]);
$campaign->setTemplate($argv[2]);
$campaign->send();
