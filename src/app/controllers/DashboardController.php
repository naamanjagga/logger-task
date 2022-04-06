<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

class DashboardController extends Controller
{
    public function dashboardAction()
    {
        if (!$this->session->has("id")) {
            $this->response->redirect('index/index');
        } else {
            $this->view->message = $this->container->get('service');
        }
    }
}