<?php
/**
 * Data warehouse
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$start = microtime(true);

$offset = "";

while(true){
    echo "commencing offset: $offset \n";
    $users = MeTon\Core\Entities::get(array('subtype'=>'video', 'limit'=>200, 'offset'=>$offset));
    $offset= end($users)->guid;
    foreach($users as $user){
        MeTon\plugin\search\start::createDocument($user);
    }
    echo "imported.. \n";
}
$end = microtime(true);

$total = $end-$start;
echo "\n\n TOOK: $total \n\n";
