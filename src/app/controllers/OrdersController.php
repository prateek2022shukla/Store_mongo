<?php
use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectId;

class OrdersController extends Controller
{
    public function indexAction()
    {

    }
    /**
     * Function to add a new order to the orders table in database
     *
     * @return void
     */
    public function addAction()
    {
        $collection = $this->client->demo->products;
        $products = $collection->find();
        $this->view->products = $products;

        $order_table = $this->client->demo->orders;

        $product_id = $this->request->getPost('product');
        $name = $this->request->getPost('customer_name');
        $quantity = $this->request->getPost('quantity');

        $data =  [
            'product' => $product_id,
            'name' => $name,
            'quantity' => $quantity,
            'date' => date('d/m/Y'),
            'status' => 'processing',
        ];

        if ($this->request->getPost('varient') != null) {
            $varient = $this->request->getPost('varient');
            array_push($data, $varient);
        }
        echo '<pre>';
        print_r($data);
        // die;

        if ($product_id && $varient && $name && $quantity) {
            $result = $order_table->insertOne($data);
            $this->response->redirect('/orders/view');
        }
    }

    /**
     * Function to fetch products from database by matching id and sending products data to the corresponding view
     *
     * @return void
     */
    public function getproductAction()
    {
        $id = $this->request->getPost('product_id');
        $collection = $this->client->demo->products;
        $oneproduct = $collection->findOne(['_id' => new ObjectID($id)]);
        $data = json_encode($oneproduct);
        return $data;
    }
    /**
     * Function to view the orders on the basis of 
     * - All orders
     * - Filter by status
     * -Filter by dates
     *
     * @return void
     */
    public function viewAction()
    {
        $order_table = $this->client->demo->orders;
        $orders = $order_table->find();
        $this->view->orders = $orders;

        
        if ($this->request->getPost('status')) {
            $status = $this->request->getPost('status');
            $collection = $this->client->demo->orders;
            $filtered_products = $collection->find(['status' => $status]);
            $this->view->orders = $filtered_products;
        }

        if ($this->request->getPost('time')) {
            $time = $this->request->getPost('time');
            $today = date('d/m/Y');
            if ($time == 'today') {
                $today_orders = $order_table->find(['date' => $today]);
                $this->view->orders = $today_orders;
            }
            if ($time == 'this_week') {
                $week = date('d/m/Y', strtotime('this week'));
                $week_orders = $order_table->find(['date' => ['$gte' => $week, '$lte' => $today]]);
                $this->view->orders = $week_orders;
            }
            if ($time == 'this_month') {
                $month = date('d/m/Y', strtotime('first day of this month'));
                $week_orders = $order_table->find(['date' => ['$gte' => $month, '$lte' => $today]]);
                $this->view->orders = $week_orders;
            }
        }

        if ($this->request->getPost('Check')) {
            $start_date = $this->request->getPost('startdate');
            $end_date = $this->request->getPost('enddate');
            $custom = $order_table->find(['date' => ['$gte' => $start_date, '$lte' => $end_date]]);
            $this->view->orders = $custom;
        }

        if ($this->request->getPost('all')) {
            $orders = $order_table->find();
            $this->view->orders = $orders;
        }
    }

    /**
     * Function to change the status of a particular product by matching its id
     *
     * @return void
     */
    public function changestatusAction()
    {
        $id = $this->request->getPost('order_id');
        $status = $this->request->getPost('status');
        $collection = $this->client->demo->orders;
        $result = $collection->updateOne(['_id' => new ObjectID($id)], ['$set' => ["status" => $status]]);
        $this->response->redirect('/orders/view');
    }
}
