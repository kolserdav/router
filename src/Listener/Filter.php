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
    /**
     * @return string
     */
    public function getRoot(): string
    {
        preg_match("%.*vendor%",dirname(__DIR__),$m);
        return preg_filter('%.{1}vendor%','',$m[0]);
    }

    /**
     * Compare uri and content of 'path' field in /config/router/routes.yaml
     * Creates an object with search data
     * @return $this
     */
    public function filterUri()
    {
        $search = preg_match('%[\d]+%', $this->uri, $m);

        /**
         * This block is triggered when uri has digits
         */
        if ($search){
            $this->id = $m[0];
            $this->uri = str_replace($this->id, '{id}', $this->uri);
        }

        /**
         * Setting Class:operation
         */
        try {
            $routes = $this->getRouteController();
        }
        catch (\Exception $e){
            echo 'Error route. '.$e->getMessage();
            exit();
        }

        /**
         * Setting 'namespace'
         */
        $this->namespace = $this->getNamespace();

        /**
         * 404
         */
        if($routes === null){
            $this->uri_base = false;
            return $this;
        }

        /**
         * Forms pages
         */
        if (!empty($routes)) {
            $className = $this->getClassName($routes);
            $this->operation = $this->getMethodName($routes);
            $this->class = '\\'.$this->namespace.'Controller\\'.$className;
            return $this;
        }
        return $this;
    }

    /**
     * Getting the class name from /config/router/routes.yaml
     * @param $routes
     * @return mixed
     */
    public function getClassName($routes)
    {
        preg_match('%.*\:\:%', $routes, $m);
        return str_replace(['::', '"', "'"], '', $m[0]);
    }

    /**
     * Getting the operation name from /config/router/routes.yaml
     * @param $routes
     * @return mixed
     */
    public function getMethodName($routes)
    {
        preg_match('%\:\:.*%', $routes, $m);
        return str_replace(['::', '"', "'"], '', $m[0]);
    }

    /**
     * Getting the namespace which is written in composer.json, section: 'psr-4'
     * @return mixed
     */
    public function getNamespace()
    {
        $comp = json_decode(file_get_contents($this->getRoot() . "/composer.json"));
        preg_match('%[.*]%', json_encode($comp->autoload->{'psr-4'}), $m);
        preg_match('%\".*\\\\\"%', json_encode($comp->autoload->{'psr-4'}), $m);
        $namespace = str_replace(['"', ''],'', $m[0]);
        return str_replace('\\\\','\\', $namespace);
    }

    /**
     * Parse file /config/router/routes.yaml, and getting Class::operation field
     * @return mixed
     */
    public function getRouteController()
    {
        global $routes;
        $arrayNames = array_keys(Yaml::parseFile($this->getRoot() . "/config/route/routes.yaml"));
        $arrays = Yaml::parseFile($this->getRoot() . "/config/route/routes.yaml");
        $count = count($arrays);
        for ($i = 0; $i < $count; $i ++){
            if (@$arrays[$arrayNames[$i]]['path'] == $this->uri){
                return $routes = $arrays[$arrayNames[$i]]['controller'];
            }
        }
        return null;
    }
}