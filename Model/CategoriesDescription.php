<?php

App::uses('AppModel', 'Model');

/**
 * CategoriesDescription Model
 *
 * @property Categories $Categories
 */
class CategoriesDescription extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'categories_description';

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'categories_id';
    // The Associations below have been created with all possible keys, those that are not needed can be removed
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        "Category" => array(
            "className" => "Category",
            "foreignKey" => "parent_id"
        ),
        "Product" => array(
            "className" => "Product",
            "foreignKey" => "master_categories_id"
        )
    );

    /**
     * method getSubCategories
     * description gets the title, the category and the products inside, neat right?
     */
    public function getSubCategories($category) {
        $catCondition = ($category == "shape tags") ? ['Category.categories_id' => [35, 39, 40, 38, 34]] : ['Category.parent_id' => 48];
        $data = $this->find("first", array(
            "conditions" => array(
                "CategoriesDescription.categories_name" => $category
            ),
            "contain" => array(
                "Category" => array(
                    "CategoriesDescription" => array(
                        "fields" => array("CategoriesDescription.categories_name")
                    ),
                    "conditions" => [$catCondition, 'Category.categories_status' => 1],
                    "fields" => array("Category.categories_image", "Category.date_added", "Category.categories_status"),
                    "order" => array("Category.sort_order ASC")
                ),
                "Product" => array(
                    "fields" => array("Product.products_id", "Product.products_image", "Product.products_model")
                )
            )
        ));
        
        return $data;
    }

}
