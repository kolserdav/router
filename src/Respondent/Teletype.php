<?php
/**
 * Created by kolserdav
 * User: Sergey Kol'miller
 * Date: 09.04.2018
 * Time: 3:21
 */

namespace Avir\Router\Respondent;

use Avir\Templater\Render;

class Teletype
{
    public static function ajaxRequest()
    {
        $template = new Render($_REQUEST, null);
        $template->ajax();
        //$host = $_REQUEST['host'];
        //preg_match('%https?\:\/\/\w*\.\w*\/%', $host, $m);
        //$result = $m[0];
        //$result = json_decode($result);
        //$nameCookie = $result->name->cookie;
        //var_dump($result);
        //$template->ajaxData = $result;
    }
}