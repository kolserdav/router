<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 09.04.2018
 * Time: 3:21
 */

namespace Avir\Router\Respondent;

use Avir\Templater\Module\Render;
use Avir\Templater\Module\Config;

class Teletype
{
    public static function ajaxRequest()
    {
        if(class_exists(Render::class)) {
            $template = new Render($_REQUEST, null);
            $template->ajax();
        }
    }
}