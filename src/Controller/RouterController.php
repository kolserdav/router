<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 25.03.2018
 * Time: 6:27
 */

namespace Avir\Router\Controller;


class RouterController
{
    /**
     * @var callable
     */
    public $operation;

    public function router(){
        echo 'echo';
        return $this;
    }
}