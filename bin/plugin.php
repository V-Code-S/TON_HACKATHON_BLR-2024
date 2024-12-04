<?php

use MeTon\Core\plugins;

define('_SCRIPT_NAME', basename(__FILE__));

function _out(array $output = [], $exit = false, $nosmartindent = false)
{
    static $smartindented = false;

    if (!$nosmartindent && $smartindented) {
        echo "       ";
    } elseif (!$nosmartindent) {
        echo "meTon: ";
    }

    echo implode(PHP_EOL, $output) . PHP_EOL;

    $smartindented = true;
    if ($exit) {
        exit;
    }
}

function get_from_dir($dir) {
    $plugin_ids = array();
    $handle = opendir($dir);

    if ($handle) {
        while ($plugin_id = readdir($handle)) {
            // must be directory and not begin with a .
            if (substr($plugin_id, 0, 1) !== '.' && is_dir($dir . $plugin_id)) {
                $plugin_ids[] = $plugin_id;
            }
        }
    }

    //does this really need to be sorted?
    sort($plugin_ids);
    return $plugin_ids;
}

if (PHP_SAPI !== 'cli') {
    _out(['this is a cli script. Please run it from Terminal.'], true, true);
}

date_default_timezone_set('UTC');

if (!isset($argv[1])) {
    _out(['usage: php ' . _SCRIPT_NAME . ' <operation> [args]'], true, true);
}

$operations = [
    'list',
    'enable',
    'disable',
    'purge',
    'clear-cache',
];
$operation = $argv[1];

if (!in_array($operation, $operations)) {
    _out(['invalid plugin operation'], true);
}

define('__METON_ROOT__', realpath(dirname(__FILE__) . '/../engine'));
require_once(__METON_ROOT__ . "/vendor/autoload.php");

$meTon = new MeTon\Core\MeTon();
$meTon->loadConfigs();
$meTon->loadLegacy();

$path = dirname(__METON_ROOT__) . '/plugins/';
$plugins = get_from_dir($path);
$db = new \MeTon\Core\Data\Call('plugin');
$plugins_db = $db->getRows($plugins); // TODO: Get ALL rows, not just the folders (to detect orphaned)
$dirty = false;

switch ($operation) {
    case 'list':
        _out(['list of MeTon plugins']);

        foreach ($plugins as $plugin) {
            $active_checkmark = isset($plugins_db[$plugin]['active']) ? 'X' : ' ';
            _out(["[{$active_checkmark}] {$plugin}"]);
        }
        break;
    case 'enable':
        if (!isset($argv[2])) {
            _out(["missing plugin name"], true);
        }

        $plugin = $argv[2];
        if (!in_array($plugin, $plugins)) {
            _out(["plugin `{$plugin}` doesn't exist"], true);
        }

        if (isset($plugins_db[$plugin]['active']) && $plugins_db[$plugin]['active']) {
            _out(["plugin `{$plugin}` is already enabled"], true);
        }

        $new_state = 1;
        break;
    case 'disable':
        if (!isset($argv[2])) {
            _out(["missing plugin name"], true);
        }

        $plugin = $argv[2];
        if (!in_array($plugin, $plugins)) {
            _out(["plugin `{$plugin}` doesn't exist"], true);
        }

        if (!isset($plugins_db[$plugin]['active']) || !$plugins_db[$plugin]['active']) {
            _out(["plugin `{$plugin}` is already disabled"], true);
        }

        $new_state = 0;
        break;
    case 'purge':
        // TODO: Clear orphaned plugin entries (exists on DB, missing on FS)
        $dirty = true;
        break;
    case 'clear-cache':
        $dirty = true;
        break;
}

if (isset($plugin) && isset($new_state)) {
    $db->insert($plugin, [ 'type' => 'plugin', 'active' => $new_state, 'access_id' => 2 ]);
    $dirty = true;

    _out(["plugin `{$plugin}` was " . ($new_state ? 'enabled' : 'disabled')]);
}

if ($dirty) {
    plugins::purgeCache('plugins:active');
    _out(['cleared plugin cache']);
}
