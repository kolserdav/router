<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:16
 */

namespace Avir\Router;

use Avir\Router\Listener\Filter;

class Route extends RouteStation
{
    /**
     * This operation connects the routing
     * @param array $data
     * @return mixed
     */
    public function route(array $data = array())
    {
        parent::getParams($data);
        return $this->setData();
    }

    /**
     * @return mixed
     */
    public function setData()
    {
        $list = new Filter();
        Driver::journal($list, $this);
        $result = $list->filterUri();

        /**
         * 404 page generation
         */
        if ($result->uri_base === false){
            $undefPageClass = '\\'.$result->namespace.'Controller\\ErrorPage';
            $undef = new $undefPageClass;
            $operation = 'errorPage';
            $undef->$operation();
            return false;
        }

        /**
         * Page generation
         */
        else {
            $controller = new $result->class();
            $operation = $result->operation;
            $controller->id = $result->id;
            $controller->params = $result->params;
            $controller->$operation();
            return true;
        }
    }
}