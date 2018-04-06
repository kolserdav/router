<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 0:32
 */

namespace Avir\Router;


abstract class RouteStation
{
    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var bool
     */
    protected $uri_base;

    /**
     * @var integer
     */
    protected $id;
    protected $params;

    /**
     * @var string
     */
    protected $operation;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Getting the source data
     * @param array $data
     */
    public function getParams(array $data = array())
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->data = $data;
    }
}