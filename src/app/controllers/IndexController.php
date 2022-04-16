<?php
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
     /**
     * Function to redirect to products view page
     *
     * @return void
     */
    public function indexAction()
    {
        $this->response->redirect('/products/view');
    }
}
