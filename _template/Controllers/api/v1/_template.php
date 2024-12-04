<?php
/**
 * {{plugin.name}}
 *
 * @version 1
 * @author Mark Harding
 */
namespace Meton\Plugin\{{plugin.name}}\Controllers\api\v1;

use Meton\Core;
use Meton\Entities;
use Meton\Helpers;
use Meton\Interfaces;
use Meton\Api\Factory;

class {{plugin.name}} implements Interfaces\Api
{

    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}
