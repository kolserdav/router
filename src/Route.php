<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:16
 */

namespace Avir\Router;


class Route extends RouteStation implements RouteInterface
{
    public function route()
    {
        parent::getParams();
        return $this->prepareData();
    }
    public function getData(): object
    {
        return $this;
    }
    public function prepareData()
    {
        return $this->setData();
    }
    public function setData()
    {
        $oper = new Operator();
        Driver::journal($oper, $this);
        $result = $oper->getData();
        if ($result->uri_base === false){
            try {
                throw new \InvalidArgumentException("404 ");
            }
            catch (\Exception $e){
                echo $e->getMessage();
            }
        }
        if ($oper->method_base === false){
            try {
                throw new \InvalidArgumentException("Unknow http method ");
            }
            catch (\Exception $e){
                echo $e->getMessage();
            }
        }
        return $oper;
    }
}