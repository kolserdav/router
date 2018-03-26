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
    public static function getOperator(){
        return new Operator();
    }

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