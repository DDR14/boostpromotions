<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');

/**
 * CakePHP MerchandiseController
 * @author Meristone
 */
class MerchandiseController extends AppController {
    
    var $uses = [];
    
     /**
     * method beforeFilter
     * description this controller beforeFilter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function index($id = null) {
        $cat_merch = $this->Product->Category->find('all', [
            'conditions' => ['parent_id' => 530],
            'contain' => ['CategoriesDescription']
        ]);

        $merchandises = [];
        $merchandise = [];

        if (!$id) {
            $merchandises = $this->Product->find("all", array(
                "conditions" => array(
                    "MasterCategory.parent_id" => 530
                ),
                "contain" => array(
                        "ProductsDiscountQuantity",
                    "MasterCategory",
                    "ProductsDescription" => array(
                        "fields" => array(
                            "ProductsDescription.products_name", "ProductsDescription.products_description"
                        )
                    )
                ),
                "fields" => array(
                            "Product.products_id",
                            "Product.products_price",
                            "Product.products_model",
                            "Product.products_image",
                            "Product.master_categories_id",
                            "Product.products_quantity_order_min"
                ),
                'order' => [["ProductsDescription.products_name ASC"]]
            ));
        } else {
            $merchandise = $this->Product->find('first', [
                'conditions' => ['Product.products_id' => $id],
                'contain' => ['ProductsDescription', 'ProductsDiscountQuantity']
            ]);
        }

        $this->set(compact("merchandise", "merchandises", "cat_merch"));
    }
    
    public function view($id){
        
    }

}
