<?php
/**
 * Data warehouse
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$channel_boost = new MeTon\Core\Boost\Channel();
$channel_boost->autoExpire();
