<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:23
 */

namespace Avir\Router\Listener;


use Avir\Router\Driver;
use Avir\Router\RouteStation;
use Symfony\Component\Yaml\Yaml;


class Filter extends RouteStation
{

    private function getRoot(): string
    {
        preg_match("%.*src%",dirname(__DIR__),$m);
        return preg_filter('%.{1}src%','',$m[0]);
    }
    public function getData()
    {
        return $this->prepareData();
    }
    public function prepareData()
    {
        $routes = array_search($this->uri, Yaml::parseFile($this->getRoot()."/config/route/routes.yaml"));
        if($routes === false){
            $this->uri_base = false;
            return $this;
        }
        if (!empty($routes)) {

            $className = $this->getClassName($routes);
            $methodName = $this->getMethodName($routes);
            $namespace = $this->getNamespace();
            $class = '\\'.$namespace.'Controller\\'.$className;
            $controller = new $class();
            return $controller->$methodName();
        }
        return Driver::journal(Driver::getOperator(), $this);
    }

    public function getClassName($routes)
    {
        preg_match('%.*\:\:%', $routes, $m);
        return str_replace(['::', '"', "'"], '', $m[0]);
    }

    public function getMethodName($routes)
    {
        preg_match('%\:\:.*%', $routes, $m);
        return str_replace(['::', '"', "'"], '', $m[0]);
    }

    public function getNamespace()
    {
        $comp = json_decode(file_get_contents($this->getRoot() . "/composer.json"));
        preg_match('%[.*]%', json_encode($comp->autoload->{'psr-4'}), $m);
        preg_match('%\".*\\\\\"%', json_encode($comp->autoload->{'psr-4'}), $m);
        $namespace = str_replace(['"', ''],'', $m[0]);
        return str_replace('\\\\','\\', $namespace);
    }
}