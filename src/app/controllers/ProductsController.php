<?php
use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectId;

class ProductsController extends Controller
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

    /**
     * Function to add a new product to database and redirects to the products view page
     *
     * @return void
     */
    public function addAction()
    {
        $name = $this->request->getPost('product_name');
        $category = $this->request->getPost('product_category');
        $price = $this->request->getPost('product_price');
        $stock = $this->request->getPost('product_stock');
        $label = $this->request->getPost('label');
        $value = $this->request->getPost('value');
        $variation_field = $this->request->getPost('variation_field');
        $variation_name = $this->request->getPost('variation_name');
        $variation_price = $this->request->getPost('variation_price');
        $additional = array(
            'label' => $label,
            'value' => $value,
        );
        
        $data = [
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
        ];
        $variation = array(
            'Variation Field' => $variation_field,
            'Variation Name' => $variation_name,
            'Variation Price' => $variation_price,
        );
               
        
        if ($additional['label'] && $additional['value']) {
            array_push($data, $additional);
        }
        if ($variation) {
            array_push($data, $variation);
        }
          
        $collection = $this->client->demo->products;
        if ($name && $category && $price && $stock) {
            $result = $collection->insertOne($data);
            $this->response->redirect('/products/view');
        }
    }

    /**
     * Function to fetch products from db and send the data to the corresponding view
     *
     * @return void
     */
    public function viewAction()
    {
        $collection = $this->client->demo->products;
        $result = $collection->find();
        $this->view->products = $result;
    }

    /**
     * Function to fetch an item from db and to delete the fetched product.
     *
     * @return void
     */
    public function deleteAction()
    {
        $id = $this->request->getQuery('id');
        $collection = $this->client->demo->products;
        $deleteResult = $collection->deleteOne(["_id" => new ObjectId($id)]);
        $this->response->redirect('/products/view');
    }

    /**
     * Function to fetch all the details on a single product from database by matching _id
     *
     * @return void
     */
    public function oneproductAction()
    {
        $id = $this->request->getPost('product_id');
        $collection = $this->client->demo->products;
        $oneproduct = $collection->findOne(['_id' => new ObjectID($id)]);
        $data = json_encode($oneproduct);
        return $data;
    }

    /**
     * Function to get updated values to the edit fields
     *
     * @return void
     */
    public function editAction()
    {
        $id = $this->request->getQuery('id');
        $collection = $this->client->demo->products;
        $editproduct = $collection->findOne(['_id' => new ObjectID($id)]);
        $this->view->editproduct = $editproduct;
    }

    /**
     * Function to update new fields and update the data in the database too 
     *
     * @return void
     */
    public function updateAction()
    {
        
        $name = $this->request->getPost('product_name');
        $category = $this->request->getPost('product_category');
        $price = $this->request->getPost('product_price');
        $stock = $this->request->getPost('product_stock');
        $label = $this->request->getPost('label');
        $value = $this->request->getPost('value');
        $variation_field = $this->request->getPost('variation_field');
        $variation_name = $this->request->getPost('variation_name');
        $variation_price = $this->request->getPost('variation_price');
        $id = $this->request->getPost('id');
     
        $additional = array(
            'label' => $label,
            'value' => $value,
        );
        
        $data = [
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
        ];
        $variation = array(
            'Variation Field' => $variation_field,
            'Variation Name' => $variation_name,
            'Variation Price' => $variation_price,
        );

        
        
        if ($additional['label'] && $additional['value']) {
            array_push($data, $additional);
        }
        if ($variation) {
            array_push($data, $variation);
        }
          
        $collection = $this->client->demo->products;
        if ($name && $category && $price && $stock) {
            $result = $collection->updateOne(["_id"=> new ObjectID($id)], ['$set'=>$data]);
            $this->response->redirect('/products/view');
        }
    }
}
