<?php

declare(strict_types=1);
use Phalcon\Mvc\Controller;


class SignupController extends Controller
{

        public function indexAction()
        {
                //return 'hello world';
        }
        public function registerAction()
        {
                $user = new Users();

                // assign value from the form to $user
                $user->assign(
                        $this->request->getPost(),
                        [
                                'name',
                                'email',
                                'password',
                                'role',
                                'status'     
                        ],
                );
                // Store and check for errors

                $success = $user->save();

                // passing the result to the view
                $this->view->success = $success;

                if ($success) {
                        $message = "Thanks for registering!";
                        $this->logger2->info('user signup ');
                } else {
                        $message = "Sorry, the following problems were generated:<br>"
                                . implode('<br>', $user->getMessages());
                                $this->logger2->error('something went wrong');
                }

                // passing a message to the view
                $this->view->message = $message;
        }
}