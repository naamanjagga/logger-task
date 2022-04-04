<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $config = $this->di->get("config");
        $this->view->variable1 = $config->get('app')->get('name');
        $this->view->variable2 = $config->get('app')->get('version');
        $this->view->variable3 = $config->database->host;
    }
}
