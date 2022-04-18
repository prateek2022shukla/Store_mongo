<?php
use Phalcon\Mvc\Controller;

class OrdersController extends Controller
{
    public function indexAction()
    {

    }
    public function addAction()
    {
        $collection = $this->client->demo->products;
        $products = $collection->find();
        $this->view->products = $products;
        
    }
    public function viewAction()
    {
        
    }

}