<?php

namespace App\Controllers;

use Phalcon\Mvc\View;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $appIndexPath = $this->di->get('config')->application->ngAppIndex;

        if (file_exists($appIndexPath)) {
            return file_get_contents($appIndexPath);
        }
    }
}
