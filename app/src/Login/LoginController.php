<?php


namespace Anax\Login;

/**
 * A controller for users and admin related events.
 *
 */
class LoginController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable,
    \Anax\MVC\TRedirectHelpers;




    /**
     * List all users.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->initialize();
        if($this->request->getPost('doLogin')){
            $this->login->loginUser($this->request->getPost('username'), $this->request->getPost('password'));
        }
        $this->theme->setTitle("Logga in");

        $this->di->views->add('login/login', [
            'title' => "Logga in"
        ], 'jumbotron');

    }





    /**
     * List all users.
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->initialize();
        $this->theme->setTitle("Logga ut");

        $this->di->session->set('user', null);

        $url = $this->di->url->create('');
        $this->di->response->redirect($url);
    }







    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->login = new \Anax\Login\Login();
        $this->login->setDI($this->di);
    }





}
