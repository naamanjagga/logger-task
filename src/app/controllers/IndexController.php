<?php

declare(strict_types=1);

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        if ($this->cookies->has("login-action")) {
            $loginCookie = $this->cookies->get("login-action");
 
            $value = $loginCookie->getValue();
            $this->response->redirect('index/login');
        }
    }

    public function loginAction()
    {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $user = Users::findFirstByemail($email);
            if ($user->password == $password) {
                $id = $user->id;
                $rem = $this->request->getPost('remember-me');
                if ($rem == 'on') {
                    $this->cookies->set(
                        "login-action",
                        $id,
                        time() + 15 * 86400
                    );
                }
                $this->session->set("id", $id);
                if ($this->session->has("id")) {
                    $name = $this->session->get("id");
                    $this->response->redirect('dashboard/dashboard');

                } 
                $this->view->message = $this->container->get('service');
            } else {
                $response = new Response(
                    "Sorry, the page doesn't exist",
                    404,
                    'Not Found'
                );
                $response->send();
                die();
        }
    }

    public function logoutAction()
    {
            $this->session->destroy(); 
            $this->response->redirect('index/index');
    }
}
