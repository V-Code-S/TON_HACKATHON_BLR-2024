<?php
/**
 * Upgrade for sprint 11
 */
 
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

/**
 * Create the counters table.
 */
$client = MeTon\Core\Data\Client::build('Cassandra');
$query = new MeTon\Core\Data\Cassandra\Prepared\System();
$client->request($query->createTable("counters", array("guid"=>"varchar", "metric"=>"varchar", "count"=>"counter"), array("guid", "metric")));
echo "complete \n";

$client = MeTon\Core\Data\Client::build('Cassandra');
$query = new MeTon\Core\Data\Cassandra\Prepared\Counters();
$result = $client->request($query->setQuery("SELECT * from meton.counters"));
var_dump($result);

exit;
