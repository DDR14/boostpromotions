<?php

/*
 * TODO:: UNCOMMENT THIS CODE "Product.products_status !=" => 0,
 *
 */

App::uses("AppModel", "Model");

class Product extends AppModel {

    public $useTable = "products";
    public $primaryKey = 'products_id';
    public $belongsTo = array(
        "Manufacturer" => array(
            "className" => "Manufacturer",
            "foreignKey" => "manufacturers_id"
        ),
        "MasterCategory" => [
            "className" => "Category",
            "foreignKey" => "master_categories_id"
        ],
        "MasterCategoriesDescription" => [
            "className" => "CategoriesDescription",
            "foreignKey" => "master_categories_id"
        ]
    );
    public $hasOne = array(
        "ProductsDescription" => array(
            "className" => "ProductsDescription",
            "foreignKey" => "products_id"
        )
    );
    public $hasMany = array(
        "OrdersProduct" => array(
            "className" => "OrdersProduct",
            "foreignKey" => "products_id"
        ),
        "ProductsDiscountQuantity" => array(
            "className" => "ProductsDiscountQuantity",
            "foreignKey" => "products_id"
        )
    );
    public $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Category',
            'joinTable' => 'products_to_categories',
            'foreignKey' => 'products_id',
            'associationForeignKey' => 'categories_id',
        )
    );

    ## Custom Find methods ##

    /**
     * method getTeachersKitsAddOns
     * description get the teachers kits add on products
     */
    public function getTeachersKitsAddOns() {
        // retrieve the customers basket data
        $addOnsProduct = $this->find("all", array(
            "conditions" => array(
                "Product.products_status !=" => 0,
                "Product.master_categories_id" => 24
            ),
            "contain" => array("ProductsDescription"),
            "fields" => array(
                "Product.products_id", "Product.products_price", "Product.products_model", "Product.products_image",
                "ProductsDescription.products_name", "ProductsDescription.products_description"
            )
        ));

        return $addOnsProduct;
    }

    /**
     * method getByProductsMasterId
     * description get the shapes category data
     *
     * @param $productId {{ int }} get product by selected products_master_id
     */
    public function getByProductsMasterId($productId) {
        $shapesProduct = $this->find("all", array(
            "conditions" => array(
                //"Product.products_status !=" => 0,
                "Product.master_categories_id" => $productId
            ),
            "contain" => array(
                "ProductsDiscountQuantity",
                "ProductsDescription" => array(
                    "fields" => array(
                        "ProductsDescription.products_name", "ProductsDescription.products_description"
                    )
                ),
                "Category" => array(
                    "CategoriesDescription" => array(
                        "fields" => array(
                            "CategoriesDescription.categories_name"
                        )
                    ),
                    "fields" => array(
                        "Category.categories_id", "Category.parent_id"
                    )
                )
            ),
            "fields" => array(
                "Product.products_id",
                "Product.products_price",
                "Product.products_model",
                "Product.products_image",
                "Product.master_categories_id",
                "Product.products_weight",
                "Product.products_quantity_order_min"
            ),
            "order" => [["Product.products_sort_order DESC"]]
        ));

        return $shapesProduct;
    }

    /**
     * method getAccessoryById
     * description get selected accessory by products_id, 
     * they're not exactly accessories only but ALL products inside the new beta boost site.
     * 
     * if $strict, show only products WITHIN main catalog; else, show product from 
     * different website like youthbowling or ismile or old order
     * usage: 
     *
     * @param productsId {{ int }} selected accessory id
     */
    public function getAccessoryById($productsId, $strict = true) {
        $conditions = [
            "conditions" => [
                //"Product.products_status !=" => 0,
                "Product.products_id" => $productsId
            ],
            "contain" => [
                "ProductsDescription" => [
                    "fields" => [
                        "ProductsDescription.products_name", "ProductsDescription.products_description"
                    ]
                ],
                "ProductsDiscountQuantity",
                "Manufacturer" => [
                    "fields" => ["Manufacturer.manufacturers_name"]
                ],
                "MasterCategoriesDescription" => [
                    "fields" => ["MasterCategoriesDescription.categories_name"]
                ],
            ],
            "fields" => [
                "Product.products_id",
                "Product.products_price",
                "Product.products_weight",
                "Product.products_model",
                "Product.products_image",
                "Product.require_artwork",
                "Product.master_categories_id",
                "Product.products_quantity_order_min"
            ]
        ];
        
        if ($strict) {
            $conditions["conditions"]["Product.master_categories_id"] = [519, 521, 522, 523, 524, 525, 526, 527, 528, 101, 24];
        }

        return $this->find("first", $conditions);
    }
}
