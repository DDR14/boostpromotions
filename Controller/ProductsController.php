<?php

App::uses("AppController", "Controller");

class ProductsController extends AppController {

    var $uses = array(
        "Category",
        "Product",
        "CustomersBasket",
        "CustomCo",
        "Design"
    );
    var $helper = ['Custom'];

    /**
     * method beforeFilter
     * description this controller beforeFilter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
                "index", "category", "view", "order", "search", "accessories", "merchandise"
        );
    }

    /**
     * method index
     * url /products/
     *
     * @var $category {{ str }} product category
     */
    public function index() {
        // just render the view and do nothing. :)
    }

    /**
     * method search
     * url /proudcts/search
     *
     * @param $keyword {{ str }} search key word
     */
    public function search() {
        $keyword = (isset($this->request->query['search'])) ? $this->request->query['search'] : null;
        $designs = $this->Design->searchDesign($keyword);
        $this->loadModel('Product');
        $products = $this->Product->find('all', [
            "contain" => ['ProductsDescription', 'MasterCategory'],
            "fields" => ['Product.*', 'ProductsDescription.*'],
            "conditions" => [
                'Product.manufacturers_id' => [1, 3], //youthbowling as manufacturer too
                "Product.products_status <>" => 0, //disabled are unsearcheable
                "MasterCategory.parent_id" => [293, 520, 322, 452, 451, 450, 317, 299, 313, 104], // temporarily add youthbowling
                "AND" => [
                    "OR" => [
                        "Product.products_model LIKE" => "%" . $keyword . "%",
                        "ProductsDescription.products_name LIKE" => "%" . $keyword . "%",
                        "ProductsDescription.products_description LIKE" => "%" . $keyword . "%"
                    ]
                ]
            ]
        ]);
        $this->set(compact('designs', 'products'));
    }

    /**
     * method category
     * url /products/category/:categoryName
     *
     * @var param $categoryName {{ str }} category name
     */
    public function category($categoryId) {
        $mainCategory = $this->Category->find("first", [
            "conditions" => [
                "Category.categories_id" => $categoryId,
            //"Category.categories_status" => 1
            ],
            "contain" => ["CategoriesDescription"]
        ]);

        $subCategories = $this->Category->find("all", array(
            "conditions" => ["parent_id" => $categoryId],
            "contain" => [
                "CategoriesDescription",
                "ChildCategory" => ['limit' => 1, 'fields' => ['categories_id'],
                    'Product' => ['limit' => 1, 'fields' => ['products_id'],
                        'ProductsDiscountQuantity' => [
                            'limit' => 1,
                            'fields' => ['discount_price'],
                            'order' => [['ProductsDiscountQuantity.discount_id DESC']]
                        ]]
                ],
                'Product' => ['fields' => ['products_id'],
                    'ProductsDiscountQuantity' => [
                        'limit' => 1,
                        'fields' => ['discount_price'],
                        'order' => [['ProductsDiscountQuantity.discount_id DESC']]
                    ]]
            ]
        ));
        $this->set("subCategories", $subCategories);
        $this->set("mainCategory", $mainCategory);
    }

    /**
     * method accessories
     * url /products/accessories/:master_categories_id
     *
     * @param $masterCategoriesId {{ int }} selected accesories master_categories_id
     */
    public function accessories($masterCategoriesId = null) {
        // $allowed = [96, 192, 194, 94, 257, 93];
        //
        // if (!in_array($masterCategoriesId, $allowed))
        //  return $this->redirect($this->referer());
        $accessories = $this->Product->getByProductsMasterId($masterCategoriesId);
        $this->set("accessories", $accessories);
    }

    /**
     * method order
     * description let the customer order  tags
     *
     * @param $categoryId {{ int || str }} selected category id tag Id use as base design or string "update"
     * if the form is use for updating the page.
     */
    public function order($categoryId, $customersBasketId = null) {
        $mainCategory = [];
        $higlight = $categoryId;

        $this->set("categoryId", $categoryId);
        $this->set(compact("customersBasketId", "categoryId"));

        // $customersBasketId is not equal to null
        // load the customers basket data and prefilled the form
        if (!is_null($customersBasketId) && $categoryId == "update") {

            // get the data from the customers basket
            $basketData = [];
            if ($this->Auth->loggedIn()) {
                $customerId = $this->Auth->user("customers_id");
                $basketData = $this->CustomersBasket->getCustomerBasketItem($customersBasketId, $customerId);

                // format the data here to be used by the view
                $products[0]["Product"] = $basketData["Product"];
                $products[0]["ProductsDescription"] = $basketData["Product"]["ProductsDescription"];
                $products[0]["ProductsDiscountQuantity"] = $basketData["Product"]["ProductsDiscountQuantity"];
                $products[0]["Manufacturer"] = $basketData["Product"]["Manufacturer"];
            } else {
                $cart = $this->Cookie->read('tmpCart')[$customersBasketId];
                if ($cart) {
                    $product_price = $this->Product->getAccessoryById($cart['CustomersBasket']['products_id'], false);
                    $basketData = $cart + $product_price;
                    $products[0] = $basketData;
                }
            }

            // redirect back the user if the baseket data is empty
            if (!$basketData) {
                return $this->redirect('/products/?not_found=true');
            }

            // check here if the image image is set
            if (!empty($basketData['CustomersBasket']['upload'])) {
                $this->set("uploadedImage", $basketData['CustomersBasket']['upload']);
            }

            // pre-filled the form only when the request is not post.
            if (!$this->request->is("post")) {
                // extract the image comment form the customs text value
                if (!empty($basketData["CustomersBasket"]["customs"])) {
                    $textValue = explode("/image-comment=", $basketData["CustomersBasket"]["customs"]);
                    $imageComment = (isset($textValue[1])) ? $textValue[1] : "";
                    $this->request->data["CustomersBasket"]["image"] = $imageComment;
                    $this->set("selectedDesigns", $basketData["CustomersBasket"]["customs"]);
                }
                $this->request->data["CustomersBasket"] = $basketData["CustomersBasket"];
            }
        } else if ($categoryId == "re-order") {
            // add to cart, then go to update page
            //   ../../2dodash/proofs/USER%20-%20WIN_20150105_09341... directly reference a proof
            /* if stock dont reorder proof */

            $customerId = $this->Auth->user("customers_id");
            // get the data from the customers basket
            $basketData = $this->CustomCo->find("first", [
                "conditions" => [
                    "id" => $customersBasketId
                ],
                "contain" => [
                    "Proof" => ['fields' => ['location'], 'conditions' => ['status' => 1]],
                    "OrdersProduct" => [
                        "Product" => [
                            "ProductsDescription",
                            "ProductsDiscountQuantity",
                            "Manufacturer"
                        ]
                    ]
                ]
            ]);

            if (empty($basketData)) {
                return $this->redirect('/products/?not_found=true');
            }
            $products = [];
            $products[0]["Product"] = $basketData["OrdersProduct"]["Product"];
            $products[0]["ProductsDescription"] = $basketData["OrdersProduct"]["Product"]["ProductsDescription"];
            $products[0]["ProductsDiscountQuantity"] = $basketData["OrdersProduct"]["Product"]["ProductsDiscountQuantity"];
            $products[0]["Manufacturer"] = $basketData["OrdersProduct"]["Product"]["Manufacturer"];

            // pre-filled the form only when the request is not post.
            if (!$this->request->is("post")) {
                // extract the image comment form the customs text value
                $textValue = explode("/image-comment=", $basketData["CustomCo"]["customs"]);
                $imageComment = (isset($textValue[1])) ? $textValue[1] : "";

                $this->request->data["CustomersBasket"] = $basketData["CustomCo"];
                $this->request->data['CustomersBasket']['title'] = 'RE-ORDER: #' . $basketData['OrdersProduct']['orders_id']
                        . ' ' . $basketData['OrdersProduct']['products_model'];
                $this->request->data['CustomersBasket']['customers_basket_quantity'] = $basketData['OrdersProduct']['products_quantity'];
                $this->set("selectedDesigns", $basketData["CustomCo"]["customs"]);
                if ($basketData['Proof']) {
                    $this->set("uploadedImage", '../../2dodash/' . $basketData['Proof'][0]['location']);
                }
            }
        } else {
            // load all the three products data
            $products = $this->Product->getByProductsMasterId($categoryId);

            if (empty($products)) {
                // get the product by products id
                $product = $this->Product->getAccessoryById($categoryId);
                if (!empty($product)) {
                    $products[0] = $product;
                    $mainCategory = [];
                } else {
                    // get product and by category id instead and set the first
                    // product as default
                    $subCategories = $this->Category->getSubCategories($categoryId);
                    if (!empty($subCategories)) {
                        $products = $this->Product->getByProductsMasterId($subCategories[0]['Category']['categories_id']);
                        $higlight = $products[0]['Category'][0]['categories_id'];
                    } else {
                        return $this->redirect('/products/?not_found=true');
                    }
                }
            }

            // generate a list of shapes in the top of the form
            if (isset($products[0]['Category'][0]['parent_id'])) {
                $parentId = $products[0]['Category'][0]['parent_id'];
                $subCategories = $this->Category->getSubCategories($parentId);
                if (!empty($subCategories)) {
                    $this->set("subCategories", $subCategories);
                }

                // SEO show Parent Category page name
                $mainCategory = $this->Category->find("first", [
                    "conditions" => [
                        "Category.categories_id" => $parentId
                    ], "contain" => [
                        'ParentCategory' => [
                            'CategoriesDescription' => ['fields' => ['CategoriesDescription.categories_name']]
                        ], 'CategoriesDescription' => ['fields' => ['CategoriesDescription.categories_name']]
                    ], "fields" => [
                        "Category.categories_id"
                    ]
                ]);
            }

            //loop the products and merge the double sided and variable to show as one
            $merge = [];
            foreach ($products as $key => $product):
                // identify the product if it is stock, modified, or custom
                $modelPrefix = substr($product["Product"]["products_model"], 0, 3);
                if (in_array($modelPrefix, ["2SI", "2ST", "VAR"])) {
                    $merge[] = $product;
                    unset($products[$key]);

                    //skip
                    continue;
                } elseif ($merge) {
                    $products[$key]['merge'] = $merge;
                    if ($modelPrefix == "STO") {
                        $merge = [];
                    }
                }

            endforeach;
        }

        reset($products);
        $first_tab = key($products);
        $this->set(compact("products", "mainCategory", "higlight", "first_tab"));

        if ($this->request->is("post")) {
            $requestData = $this->request->data;

            // check if update mode
            if (!is_null($customersBasketId) && $categoryId != "re-order" && $categoryId != "teachersKits" && $this->Auth->loggedIn()) {
                $requestData["CustomersBasket"]["customers_basket_id"] = $customersBasketId;
            }

            if (!empty($requestData["CustomersBasket"]["image"])) {
                if (!isset($requestData["CustomersBasket"]["customs"])) {
                    $requestData["CustomersBasket"]["customs"] = "";
                }

                $requestData["CustomersBasket"]["customs"] = $requestData["CustomersBasket"]["customs"] . "/image-comment=" . $requestData["CustomersBasket"]["image"];
            }

            // load the selected product data this will be check if the product is stock, modified, or custom
            // TODO:: optimize the product is already loaded but database call again because!
            $productData = $this->Product->getAccessoryById($requestData["CustomersBasket"]["products_id"], false);

            if (!empty($requestData["CustomersBasket"]["imageFile"][0]["name"])) {
                // check if the image file is valid
                $this->CustomersBasket->set($requestData["CustomersBasket"]);
                if (!$this->CustomersBasket->validates()) {
                    $errors = $this->CustomersBasket->validationErrors;
                    return $this->Flash->error('Unexpected error. please contact support.', ['key' => 'newItemAdded']);
                }
            }

            // check the image first before uploading it.            
            if (!empty($requestData["CustomersBasket"]["imageFile"][0]["name"])) {

                //Upload Each file
                $folder = "/images/uploads";
                $upload = [];
                foreach ($requestData["CustomersBasket"]["imageFile"] as $imgFile):
                    $response = $this->Utility->upload($imgFile, $folder);
                    $upload[] = $response['url'];
                endforeach;

                $requestData["CustomersBasket"]["upload"] = implode(',', $upload);
            } else {
                unset($requestData["CustomersBasket"]["imageFile"]);
            }

            // Combine same shaped stock tags for proper pricing
            $productType = explode("-", $productData["Product"]["products_model"]);

            if (in_array($productType[0], ['STOCK', '2STOCK'])) {

                // check if the product model already exists in the cart
                // TODO:: if logged in, look for it in cookieCart
                $savedCartData = $this->CustomersBasket->find("first", array(
                    "conditions" => array(
                        "customers_id" => $this->Auth->user("customers_id"),
                        "products_id" => $productData["Product"]["products_id"]
                    )
                ));

                if ($savedCartData && is_null($customersBasketId)) {
                    //combine quantities
                    $newQty = $savedCartData["CustomersBasket"]["customers_basket_quantity"] + $requestData["CustomersBasket"]["customers_basket_quantity"];
                    $newCustoms = $savedCartData["CustomersBasket"]["customs"] . "," . $requestData["CustomersBasket"]["customs"];

                    //explodes using ','; remove trailing spaces; deletes blank values
                    $arr1 = preg_split('/[\s*,\s*]*,+[\s*,\s*]*/', $newCustoms);
                    $arr2 = [];
                    for ($i = 0; $i < count($arr1); $i++):
                        list($k, $v) = explode('=', $arr1[$i]);
                        $arr2[$k] = array_key_exists($k, $arr2) ? $arr2[$k] + $v : $v;
                    endfor;

                    $newCustoms = implode(', ', array_map(
                                    function ($v, $k) {
                                return sprintf("%s=%s", $k, $v);
                            }, $arr2, array_keys($arr2)
                    ));

                    // if it exists get the id and update the order quantity and the customs field
                    $this->CustomersBasket->id = $savedCartData["CustomersBasket"]["customers_basket_id"];
                    $this->CustomersBasket->saveField("customers_basket_quantity", $newQty);
                    $this->CustomersBasket->saveField("customs", $newCustoms);

                    // notify the user that the customers order data is being updated instead of adding a new order.
                    $this->Flash->success("Product Model: " . $productData["Product"]["products_model"] . " has been updated.", array("key" => "newItemAdded"));

                    return $this->redirect([
                                "controller" => "shoppingCart",
                                "action" => "index"
                    ]);
                }
            }

            // if not stock, create a new or duplicate product in cart
            $requestData['CustomersBasket']['website'] = 'https://boost2.boostpromotions.com';
            if ($this->Auth->loggedIn()) {
                $requestData['CustomersBasket']['customers_id'] = $this->Auth->user("customers_id");
                $this->CustomersBasket->save($requestData["CustomersBasket"]);
            } else {
                $tmpCart = $this->Cookie->read('tmpCart');
                $key = $customersBasketId ? $customersBasketId : count($tmpCart) + 1;
                $tmpCart[$key] = $requestData;
                $this->Cookie->write('tmpCart', $tmpCart);
            }

            $message = (is_null($customersBasketId) || $categoryId == "teachersKits") ? "New item added to cart" : "Order Updated";
            $this->Flash->success($message, array("key" => "newItemAdded"));


            return $this->redirect([
                        "controller" => "shoppingCart",
                        "action" => "index"
            ]);
        }
    }

    /**
     * 
     * @param type $id
     */
    public function merchandise($id = null) {
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

    /**
     * method redirectToitem
     * url /products/redirectBack/:$itemId
     *
     * @param $itemId {{ str }} selected item id
     */
    public function redirectToitem($type, $itemId) {
        if ($type == "tag") {
            return $this->redirect(array(
                        "controller" => "products",
                        "action" => "order",
                        $itemId
            ));
        } elseif ($type == "design") {
            return $this->redirect('/products/viewDesign/' . $itemId);
        } else {
            return $this->redirect('/products/order/teachersKits/' . $itemId);
        }
    }

}
