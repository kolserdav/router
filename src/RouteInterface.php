<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:33
 */

namespace Avir\Router;



interface RouteInterface
{
    /**
     * @return object
     */
    public function getData();

    /**
     * @return object
     */
    public function prepareData();

    /**
     * @return object
     */
    public function setData();
}