<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:32
 */

namespace Avir\Router;

use Avir\Router\Listener\Filter;
use Avir\Router\Generator\Getter;
use Avir\Router\Generator\Poster;

class Operator extends RouteStation implements RouteInterface
{
    public function operator(){

    }
    public function getData()
    {

        //var_dump($this);

        return $this->prepareData();

    }
    public function prepareData()
    {
        $list = new Filter();
        Driver::journal($list, $this);
        $result = $list->getData();
        if ($result->uri_base === false){
            return $result;
        }
        if ($this->method == 'GET'){
            $this->method_base = true;
            $get = new Getter();
            return $this;
        }
        else if ($this->method == 'POST'){
            $this->method_base = true;
            $post = new Poster();
            return $this;
        }
        else {
            $this->method_base = false;
            return $this;
        }
    }
    public function setData(): object
    {

    }
}