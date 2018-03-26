<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:23
 */

namespace Avir\Router\Listener;


use Avir\Router\Driver;
use Avir\Router\RouteInterface;
use Avir\Router\RouteStation;
use Symfony\Component\Yaml\Yaml;


class Filter extends RouteStation implements RouteInterface
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
        if (!empty($routes)) {
            preg_match('%.*\:\:%', $routes, $m);
            $className = str_replace(['::', '"', "'"], '', $m[0]);
            preg_match('%\:\:.*%', $routes, $m);
            $methodName = str_replace(['::', '"', "'"], '', $m[0]);
            $comp = json_decode(file_get_contents($this->getRoot() . "/composer.json"));
            preg_match('%[.*]%', json_encode($comp->autoload->{'psr-4'}), $m);
            preg_match('%\".*\\\\\"%', json_encode($comp->autoload->{'psr-4'}), $m);
            $namespace = str_replace(['"', ''],'', $m[0]);
            $namespace = str_replace('\\\\','\\', $namespace);
            $class = '\\'.$namespace.'Controller\\'.$className;
            //constant($class);
            $m = new $class();
            $m->$methodName();
            var_dump(class_exists($class));
            var_dump($class);
        }
        //$this->uri_base = false;
        //$this->data = $data;

        return Driver::journal(Driver::getOperator(), $this);
    }
    public function setData(): object
    {
        // TODO: Implement setData() method.
    }
}