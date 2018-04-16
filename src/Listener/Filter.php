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
        /**
         * Setting 'namespace'
         */
        $this->namespace = $this->getNamespace();

        if($this->uri === '/respond-require-data'){
            require __DIR__.'/../Respondent/responder.php';
            exit();
        }
        $getParams = $this->searchGetParams($this->uri);
        if ($getParams){
            $this->uri = $this->delString($this->uri, $getParams);
            $this->params = $this->getGetParams($getParams);
        }
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

            return $this->getClassAndMethod($routes);
        }
        return $this;
    }
    public function getClassAndMethod($routes)
    {
        $className = $this->getClassName($routes);
        $this->operation = $this->getMethodName($routes);
        $this->class = '\\'.$this->namespace.'Controller\\'.$className;
        return $this;
    }
    public function searchGetParams($uri)
    {
        if(preg_match('%\?.*%', $uri, $m)){
            return $m[0];
        }
        else {
            return false;
        }
    }

    /**
     * @param string $params_string
     * @return array
     */
    public function getGetParams(string $params_string): array
    {
        $checkQuest = preg_match('%\&.*%', $params_string, $m);
        if(!$checkQuest){
            $line = $this->delString($params_string,'?');
            return $this->getArrayParams($line);
        }
        else {
            preg_match('%\?\w*\=\w*\&%', $this->searchGetParams($params_string), $m);
            $n = $this->searchAnd($params_string);
            $n[0][] = $this->delString($m[0],['?','&']);
            $arr = array_map(function($val){
                return $this->delString($val,'&');
            },$n[0]);
            $keys = array_map(function ($val){
                preg_match( '%\w*\=%', $val, $z);
                return $this->delString($z[0], '=');
            }, $arr);
            $values = array_map(function ($val){
                preg_match( '%\=\w*%', $val, $z);
                return $this->delString($z[0], '=');
            }, $arr);
            $result = array_combine($keys, $values);

            return $result;
        }
    }

    /**
     * @param string $str
     * @return bool|array
     */
    public function searchAnd(string $str)
    {
        if(preg_match_all('%\&\w*\=\w*%', $str, $n)){
            return $n;
        }
        else {
            return false;
        }
    }

    /**
     * @param string $expression
     * @return mixed
     */
    public function getArrayParams(string $expression)
    {
        if (preg_match('%.*\=%', $expression, $m)){
            $key = trim(str_replace('=', '', $m[0]));
            if(preg_match('%\=.*%', $expression, $n)){
                $value = trim(str_replace('=', '', $n[0]));
            }
            else {
                $value = 'empty';
            }
            $result[$key] = $value;
            return $result;
        }
        else {

            return ['Expression'  => 'not found'];
        }
    }

    /**
     * @param string $string
     * @param string|array $del_string
     * @return string
     */
    public function delString(string $string,$del_string): string
    {
        return str_replace($del_string, '', $string);
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
        $search = $this->cycleForSearch($this->uri);
        if($search == null && $this->uri[strlen($this->uri)-1] == '/'){
            $uri = rtrim($this->uri,'/');
            $search = $this->cycleForSearch($uri);
        }
        else if($search == null && $this->uri[strlen($this->uri)-1] != '/'){
            $uri = $this->uri.'/';
            $search = $this->cycleForSearch($uri);
        }
        return $search;
    }
    public function cycleForSearch($uri)
    {
        $arrayNames = array_keys(Yaml::parseFile($this->getRoot() . "/config/route/routes.yaml"));
        $arrays = Yaml::parseFile($this->getRoot() . "/config/route/routes.yaml");
        $count = count($arrays);
        for ($i = 0; $i < $count; $i ++){
            if (@$arrays[$arrayNames[$i]]['path'] == $uri){
                return $routes = $arrays[$arrayNames[$i]]['controller'];
            }
        }
        return null;
    }
}