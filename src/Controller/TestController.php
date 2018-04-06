<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 27.03.2018
 * Time: 1:46
 */

namespace Avir\Router\Controller;


class TestController
{

    public function test()
    {
        echo 'TestController::test';
        return true;
    }
    public function tests()
    {
        echo 'TestController::tests';
        return true;
    }
}