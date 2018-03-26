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
    public function route(array $data = array())
    {
        parent::getParams($data);
        return $this->setData();
    }

    public function setData()
    {
        $list = new Filter();
        Driver::journal($list, $this);
        $result = $list->getData();
        if ($result->uri_base === false){
            try {
                throw new \InvalidArgumentException("404 ");
            }
            catch (\Exception $e){
                echo $e->getMessage();
            }
        }
        return $this;
    }
}