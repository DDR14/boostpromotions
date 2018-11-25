<?php

App::uses("AppModel", "Model");

class Design extends AppModel {

    private $designCategories = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 53, 62, 131, 447];
    public $primaryKey = 'products_id';
    public $belongsTo = array(
        "Manufacturer" => array(
            "className" => "Manufacturer",
            "foreignKey" => "manufacturers_id"
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
    public $hasOne = [
        "DiscountedDesign" => [
            "className" => "DiscountedDesign",
            "foreignKey" => "design_id"
        ]
    ];
    public $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Category',
            'joinTable' => 'products_to_categories',
            'foreignKey' => 'products_id',
            'associationForeignKey' => 'categories_id',
        )
    );

    /**
     * getAllDesigns method
     *
     * get all the designs data
     */
    public function getAllDesigns() {
        return $this->find('all', [
                    'conditions' => [
                        'manufacturers_id' => 1,
                        "products_status !=" => 0,
                        "master_categories_id" => $this->designCategories
                    ]
        ]);
    }

    /**
     * getByModel
     *
     * get design data by product model
     *
     * @param $model {{ str }} selected product model
     */
    public function getByModel($model) {
        return $this->find('first', [
                    'conditions' => [
                        'products_model' => $model,
                        "master_categories_id" => $this->designCategories
                    ],
                    "contain" => [
                        "Category" => [
                            "fields" => ["Category.parent_id"],
                            "limit" => 1
                        ]
                    ],
                    "fields" => [
                        "Design.products_id",
                        "Design.master_categories_id",
                        "Design.products_model",
                        "Design.products_image"
                    ]
        ]);
    }

    /**
     * getTagsInList
     *
     * get all the designs that is in the list array
     *
     * @param $list {{ array }} contains the list of models
     */
    public function getTagsInList($list) {
        return $this->find('all', [
                    'conditions' => [
                        'products_model' => $list,
                        'manufacturers_id' => 1,
                        "master_categories_id" => $this->designCategories
                    ],
                    "contain" => array(
                        "ProductsDescription",
                        "Category"
                    ),
        ]);
    }

    /**
     * getByMasterCategoriesId
     *
     * get designs by master_categories_id
     *
     * @param $master_categories_id {{ int }} selected category id
     */
    public function getByMasterCategoriesId($master_categories_id) {
        return $this->find("all", [
                    "conditions" => [
                        "master_categories_id" => $master_categories_id,
                        "manufacturers_id" => 1,
                        "products_status" => 1
                    ],
                    "contain" => [
                        "DiscountedDesign" => [
                        'fields' => "dd_new_products_price",
                        "conditions" =>['DiscountedDesign.expires_date >' => date('Y-m-d')]
                        ]
                    ],
                    "order" => [['products_sort_order ASC']]
        ]);
    }

    /**
     * searchDesign
     *
     * Get tags according to the search keyword
     *
     * @param $keyword
     */
    public function searchDesign($keyword = null) {
        return $this->find("all", [
                    "conditions" => [
                        'Design.manufacturers_id' => 1,
                        "Design.products_status !=" => 0,
                        "Design.master_categories_id" => $this->designCategories,
                        "AND" => [
                            "OR" => [
                                "Design.products_model LIKE" => "%" . $keyword . "%",
                                "Design.design_name LIKE" => "%" . $keyword . "%"
                            ]
                        ]
                    ]
        ]);
    }

    /**
     * searchSwagTagsAndShapeTagsOnly
     *
     * Search only for swag tags and shape tags designs
     * @param $keyword
     */
    public function searchSwagTagsAndShapeTagsOnly($keyword) {
        return $this->find("first", [
                    "conditions" => [
                        'Design.manufacturers_id' => 1,
                        "Design.products_status !=" => 0,
                        "Design.master_categories_id" => $this->designCategories,
                        "AND" => [
                            "Design.products_model" => $keyword
                        ]
                    ],
                    "contain" => [
                        "ProductsDescription",
                        "Category" => [
                            "CategoriesDescription"
                        ]
                    ]
        ]);
    }

}
