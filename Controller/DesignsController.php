<?php

App::uses("AppController", "Controller");

class DesignsController extends AppController {

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
                "index", "view", "ajax_getSubCategories", "ajax_getSubCategoriesItem", "ajax_getTagsInList", "ajax_designsLibData", "ajax_getDesignLibItems", "ajax_searchDesign", "ajax_viewDesign"
        );
    }

    /**
     * method templates
     * url /products/templates
     *
     * @param $categoryId {{ int  default null }}
     */
    public function index($categoryId = "All Designs") {
        // load subcategoryItems
        $condition = "";
        if ($categoryId != "All Designs") {
            $condition = "AND Category.categories_id = $categoryId ";
        }

        // display the all the designs here
        $designs = $this->Design->query(
                "SELECT Category.categories_name, Category.categories_id, Category.categories_description "
                . "FROM zen_categories_description Category "
                . "INNER JOIN zen_categories b "
                . "ON Category.categories_id = b.categories_id "
                . "WHERE b.parent_id = 48 AND b.categories_status = 1 "
                . $condition
                . "ORDER BY b.sort_order ASC"
        );

        if (empty($designs)) {
            $this->redirect([
                "controller" => "pages",
                "action" => "display",
                "404"
            ]);
        }

        foreach ($designs as $key => $value) {
            $designs[$key]['Designs'] = $this->Design->getByMasterCategoriesId($value['Category']['categories_id']);
        }

        $this->set(compact("designs", "categoryId"));
    }

    /**
     * method viewDesign
     * url/products/viewDesign/:id
     *
     * @param $id {{ int }} selected tag design id
     */
    public function view($id) {
        $notIncludedOnDesignsLib = array(14, 105, 123, 98, 233, 192, 26, 24, 294, 295, 296, 297, 298, 70, 64, 67, 65, 66, 68, 34, 36, 37, 38, 39, 40);

        $design = $this->Design->find('first', [
            'conditions' => [
                'Design.products_id' => $id,
                "NOT" => array(
                    "Design.master_categories_id" => $notIncludedOnDesignsLib
                )
            ],
            'contain' => [
                'CategoriesDescription',
                'Category'
            ],
            'fields' => [
                'Category.parent_id',
                'Design.products_id',
                'Design.products_model',
                'Design.products_image',
                'Design.design_name',
                'Design.master_categories_id',
                'CategoriesDescription.categories_name'
            ]
        ]);

        if (empty($design)) {
            return $this->redirect([
                        "controller" => "pages",
                        "action" => "display",
                        "404"
            ]);
        }

        $this->set('design', $design);
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

    // Ajax methods

    /**
     * method ajax_getSubCategories
     * description get selected main category sub categories
     *
     * @var $categoryName {{ str }} selected main category name
     */
    public function ajax_getSubCategories($categoryName) {
        $subCategories = $this->Category->CategoriesDescription->getSubCategories($categoryName);
        $response = array(
            "data" => $subCategories,
            "status" => "success"
        );

        return $this->set(array(
                    "response" => $response,
                    "_serialize" => array("response")
        ));
    }

    /**
     * method ajax_getSubCategories
     * description get selected main category sub categories
     *
     * @var $categoryName {{ str }} selected main category name
     */
    public function ajax_viewDesign($id) {
        $design = $this->Design->find('first', [
            'conditions' => [
                'Design.products_id' => $id
            ],
            'contain' => [
                'Category' => ["limit" => 1],
                "DiscountedDesign" => [
                    'fields' => "dd_new_products_price",
                    "conditions" => ['DiscountedDesign.expires_date >' => date('Y-m-d')]
                ]
            ],
            'fields' => [
                'Design.products_id',
                'Design.products_model',
                'Design.products_image',
                'Design.master_categories_id',
                'Design.design_name'
            ]
        ]);

        $response = array(
            "data" => $design,
            "status" => "success"
        );

        return $this->set(array(
                    "response" => $response,
                    "_serialize" => array("response")
        ));
    }

    /**
     * method ajax_getSubCategoriesItem
     * description get sub categories item
     *
     * @var  $subCategoryId {{ int }} selected sub category Id
     */
    public function ajax_getSubCategoriesItem($subCategoryId = null) {
        if (is_null($subCategoryId)) {
            $response = array(
                "data" => "Error",
                "type" => "error"
            );
        } else {
            $category = $this->Category->getOne($subCategoryId);
            $category['Design'] = $this->Design->getByMasterCategoriesId($subCategoryId);
            $response = array(
                "response" => $category,
                "status" => "success"
            );
        }

        return $this->set(array(
                    "response" => $response,
                    "_serialize" => array("response")
        ));
    }

    /**
     * method ajax_getDesignLibItems
     * description all the designlib items
     */
    public function ajax_getDesignLibItems() {
        $items = $this->Design->getAllDesigns();
        return $this->set([
                    "response" => $items,
                    "_serialize" => array("response")
        ]);
    }

    /**
     * method ajax_getTagsInList
     * description get all the tags the are present in the list of post data
     */
    public function ajax_getTagsInList() {
        $requestData = $this->request->data;

        if ($this->request->is("post")) {
            $data = $this->Design->getTagsInList($requestData["List"]);
            $response = [
                "status" => "success",
                "data" => $data
            ];
        } else {
            $response = [
                "status" => "error",
                "data" => "Unauthorize access"
            ];
        }

        return $this->set([
                    "response" => $response,
                    "_serialize" => array("response")
        ]);
    }

    /**
     * method ajax_designsLibData
     * description get all the tags data from the design library main category
     */
    public function ajax_designsLibData() {
        $response = $this->Design->getAllDesigns();
        return $this->set([
                    "response" => $response,
                    "_serialize" => array("response")
        ]);
    }

    /**
     * method ajax_searchDesign
     * description get the product according to search keyword
     *
     * @param keyword {{ str }} search keyword
     */
    public function ajax_searchDesign($keyword) {
        $response = $this->Design->getByModel($keyword);
        return $this->set([
                    "response" => $response,
                    "_serialize" => ["response"]
        ]);
    }

}
