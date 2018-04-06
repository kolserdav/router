<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 27.03.2018
 * Time: 1:26
 */

namespace Avir\Router\Routetest;

use Avir\Router\Listener\Filter;
use PHPUnit\Framework\TestCase;
use Avir\Router\Route;

class Test extends TestCase
{

    protected $uri_tests;
    protected $uri_test;
    protected $uri_wrong;
    protected $route;

    protected function setUp ()
    {

        $this->uri_tests = '/tests/';
        $this->uri_test = '/test/1';
        $this->uri_wrong = 'www';
    }

    public function testRoute()
    {
        $route = new Route();
        $route->uri = $this->uri_tests;
        $this->assertNotFalse($route->setData());
        $route->uri = $this->uri_test;
        $this->assertNotFalse($route->setData());
        $route->uri = $this->uri_wrong;
        $this->assertFalse($route->setData());

    }
    public function testGetRouteController()
    {
        $list = new Filter();
        $list->uri = $this->uri_tests;
        $this->assertNotNull($list->getRouteController());
    }
    public function testGetNamespace()
    {
        $list = new Filter();

        /**
         * Rename 'Avir\Router' to your namespace
         */
        $this->assertEquals('Avir\Router\\', $list->getNamespace());
    }
    public function testGetMethodName()
    {
        $list = new Filter();
        $list->uri = $this->uri_tests;
        $this->assertEquals('tests', $list->getMethodName('Test::tests'));
    }
    public function testGetClassName()
    {
        $list = new Filter();
        $list->uri = $this->uri_tests;
        $this->assertEquals('Test', $list->getClassName('Test::tests'));
    }


}