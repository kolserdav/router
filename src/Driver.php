<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 2:36
 */

namespace Avir\Router;

class Driver extends RouteStation
{
    /**
     * @return Route
     */
    public static function getManager():Route
    {
        return new Route();
    }

    /**
     * Transfer properties from object to object
     * @param $worker
     * @param $oppo
     * @return mixed
     */
    public static function journal($worker, $oppo)
    {
        $worker->method = $oppo->method;
        $worker->uri = $oppo->uri;
        $worker->data = $oppo->data;
        $worker->uri_base = $oppo->uri_base;
        $worker->method_base = $oppo->method_base;
        return $worker;
    }
}