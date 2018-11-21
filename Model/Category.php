<?php

App::uses("AppModel", "Model");

class Category extends AppModel {

    /**
     * property $primaryKey
     * description database primary key
     */
    public $primaryKey = 'categories_id';

    /**
     * property $belongsTo
     * description model belongs to realationships
     */
    public $belongsTo = [
        "ParentCategory" => [
            "className" => "Category",
            "foreignKey" => "parent_id"
        ]
    ];
    
    
    public $hasMany = array(
        "ChildCategory" => [
            "className" => "Category",
            "foreignKey" => "parent_id"
        ],
        "Design" => [
            "className" => "Design",
            "foreignKey" => "master_categories_id"
        ]
    );
    
    public $hasOne = [
        "CategoriesDescription" => array(
            "className" => "CategoriesDescription",
            "foreignKey" => "categories_id"
        )
    ];

    /**
     * property hasAndBelongsToMany
     * description model HABTM relationship
     */
    public $hasAndBelongsToMany = array(
        'Product' =>
        array(
            'className' => 'Product',
            'joinTable' => 'products_to_categories',
            'foreignKey' => 'categories_id',
            'associationForeignKey' => 'products_id',
            'order' => 'Product.products_sort_order ASC'
        )
    );

    /**
     * method getProducts
     * description get all the category products
     *
     * @var $categoryId {{ int }} category id
     */
    public function getProducts($categoryId, $notIncluded) {
        $data = $this->find("first", array(
            "conditions" => ["categories_id" => $categoryId],
            "contain" => [
                "Design" => [
                    "conditions" => array(
                        "NOT" => array(
                            "Design.master_categories_id" => $notIncluded
                        )
                    ),
                    "ProductsDescription" => [
                        "fields" => ["ProductsDescription.products_name", "ProductsDescription.products_description"]
                    ],
                    "fields" => [
                        "Design.products_model", "Design.products_image", "Design.master_categories_id"
                    ]
                ],
                "CategoriesDescription"
            ],
            "fields" => ["Category.categories_id", "Category.parent_id", "Category.categories_image"]
        ));

        return $data;
    }

    /**
     * method get SubCategories
     * description get selected main category sub category
     *
     * @param parentId {{ int }} parent category id
     */
    public function getSubCategories($parentId) {
        $data = $this->find("all", array(
            "conditions" => ["parent_id" => $parentId],
            "contain" => [
                "CategoriesDescription"
            ]
        ));

        return $data;
    }

    // /**
    //  * method getDesignLibraryCategories
    //  * description get all the categories that will be used in the design library
    //  */
    // public function getDesignLibraryCategories()
    // {
    //     return $this->find("all", [
    //       "conditions" => [
    //         "parent_id" => 48
    //       ]
    //     ]);
    // }

    /**
     * 
     * Change the way this are retrieved to save on resources (elvis)
     * 
     * @param type $categoryId
     * @return type
     */
    public function getWithDesigns($categoryId) {
        return $this->find("first", [
                    "conditions" => [
                        "Category.categories_id" => $categoryId
                    ],
                    "contain" => [
                        "CategoriesDescription",
                        "Design" => [
                            "conditions" => [
                                "master_categories_id" => $categoryId,
                                "manufacturers_id" => 1,
                                "products_status" => 1
                            ],
                            "order" => [['products_sort_order']]
                        ]
                    ]
        ]);
    }

}
