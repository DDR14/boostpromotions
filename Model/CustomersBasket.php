<?php

App::uses('AppModel', 'Model');
App::uses("Ups", "Lib");

/**
 * CustomersBasket Model
 *
 * @property Customers $Customers
 * @property Products $Products
 */
class CustomersBasket extends AppModel {

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'customers_basket';

    /**
     * Primary key field
     *
     * @var string
     */
    public $primaryKey = 'customers_basket_id';
    public $hasMany = array(
        'ProductsDiscountQuantity' => array(
            'className' => 'ProductsDiscountQuantity',
            'foreignKey' => false,
            'finderQuery' => 'SELECT ProductsDiscountQuantity.* FROM zen_products_discount_quantity AS ProductsDiscountQuantity
                INNER JOIN zen_customers_basket
WHERE ProductsDiscountQuantity.products_id IN
(SELECT products_id FROM zen_customers_basket WHERE customers_basket_id = {$__cakeID__$})'
    ));

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'customers_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Product' => array(
            'className' => 'Product',
            'foreignKey' => 'products_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ProductsDescription' => array(
            'className' => 'ProductsDescription',
            'foreignKey' => false,
            'conditions' => 'ProductsDescription.products_id = Product.products_id'
        )
    );

    /**
     * property validate
     * description model validation rules
     * all cart should have 25 minimum
     */
    public $validate = array(
        "imageFile" => array(
            "extension" => array(
                "rule" => array("extension", array("gif", "pdf", "png", "jpeg", "jpg", "psd", "docx", "doc")),
                "message" => "gif, png, pdf, jpeg, jpg files are only allowed."
            )
        )
    );

    // model methods

    /**
     * method isOwnedBy
     * description check the customer owned the data
     */
    public function isOwnedBy($itemId, $customer) {
        return $this->field('customers_basket_id', array('customers_basket_id' => $itemId, 'customers_id' => $customer)) !== false;
    }

    /**
     * method getCustomerItems
     * description get the customers shopping cart item
     *
     * @var $customerId {{ int }} customer id
     */
    public function getCustomerItems($customerId) {
        //just wasted time trying to convert to cakephp standard
        $data = $this->query("SELECT CustomersBasket.customers_basket_quantity,
CustomersBasket.customers_basket_id,
Product.require_artwork,
Product.products_id,
Product.products_weight,
Product.master_categories_id,
Product.products_model,
Product.products_image,
Product.products_quantity_order_min,
ProductsDescription.products_name,
CustomersBasket.title,
CustomersBasket.background,
CustomersBasket.footer,
CustomersBasket.upload,
CustomersBasket.customs,
CustomersBasket.website,
IF(ISNULL(ProductsDiscountQuantity.discount_price),Product.products_price,ProductsDiscountQuantity.discount_price) AS products_price
FROM zen_customers_basket AS CustomersBasket
INNER JOIN zen_products AS Product
ON CustomersBasket.products_id = Product.products_id
INNER JOIN zen_products_description AS ProductsDescription
ON ProductsDescription.products_id = CustomersBasket.products_id
LEFT JOIN (SELECT MIN(x.discount_price) AS discount_price,
y.customers_basket_id FROM zen_products_discount_quantity x
INNER JOIN zen_customers_basket y
ON x.products_id = y.products_id
WHERE y.customers_id =  ?
AND y.customers_basket_quantity >= discount_qty
GROUP BY y.customers_basket_id) AS ProductsDiscountQuantity
ON ProductsDiscountQuantity.customers_basket_id = CustomersBasket.customers_basket_id
WHERE CustomersBasket.customers_id = ?", [$customerId, $customerId], false);


        return $data;
    }

    /**
     * method getProductPrice
     * description  get discounted price according to quantity
     *
     * @var $products_id {{ int }} products id
     * @var $qty {{ int }} quantity
     */
    public function getProductPrice($products_id, $qty) {
        $data = $this->query("SELECT 
Product.require_artwork,
Product.products_id,
Product.products_weight,
Product.master_categories_id,
Product.products_model,
Product.products_image,
Product.products_quantity_order_min,
ProductsDescription.products_name,
IF(ISNULL(ProductsDiscountQuantity.discount_price),Product.products_price,ProductsDiscountQuantity.discount_price) AS products_price
FROM zen_products AS Product
INNER JOIN zen_products_description AS ProductsDescription
ON ProductsDescription.products_id = Product.products_id
LEFT JOIN (SELECT MIN(x.discount_price) AS discount_price,
x.products_id FROM zen_products_discount_quantity x
WHERE x.products_id =  ?
AND ? >= x.discount_qty
GROUP BY x.products_id) AS ProductsDiscountQuantity
ON ProductsDiscountQuantity.products_id = Product.products_id
WHERE Product.products_id = ?", [$products_id, $qty, $products_id], false);

        return $data;
    }

    /**
     * method flatBoxPrice
     * description get the shipping rate of the order
     *
     * @var customersId {{ int }} selected customers id
     * @var countryId {{ int }} delivery address country Id
     */
    public function flatBoxPrice($basketData, $delivery_country) {

        $tags = 0;
        $chain4 = 0;
        $chain24 = 0;
        $shirts = 0;
        $keyfob = 0;
        $lanyard = 0;
        $totalWeight = 0;

        foreach ($basketData as $row) {
            //we should get everything in their basket here
            $products_quantity = $row['CustomersBasket']['customers_basket_quantity'];
            $products_model = $row['Product']['products_model'];
            $master_categories_id = $row['Product']['master_categories_id'];

            // get the total weight
            if (!is_null($row['Product']['products_weight'])) {
                $weight = ($row['Product']['products_weight'] * $row['CustomersBasket']['customers_basket_quantity']);
                $totalWeight += $weight;
            }

            $first3 = substr($products_model, 0, 7);
            if (in_array($row["Product"]["products_id"], [990, 853])) {
                //they have lanyards.  Just charge them UPS
                $lanyard = 2;
            } elseif ($master_categories_id == 257) {
                //they have custom key fobs.
                $keyfob += $products_quantity;
            } elseif ($first3 == "CHA-001") {
                //they have 3 inch chains
                $chain4 += $products_quantity;
            } elseif ($first3 == "CHA-002" || $row["Product"]["products_id"] == 4482) {
                //they have 24 inch chains
                $chain24 += $products_quantity;
            } elseif (in_array($products_model, ["TK-250-DT", "TK-250-CO"])) {
                $tags += 250 * $products_quantity;
            } elseif (in_array($products_model, ["TK-300-CO", "TK-300-DT"])) {
                $tags += 300 * $products_quantity;
            } elseif (in_array($products_model, ["TK-350-DT", "TK-350-CO"])) {
                $tags += 350 * $products_quantity;
            } elseif ($products_model == "tk-25-chainsl") {
                $chain24 += 25 * $products_quantity;
            } elseif ($products_model == "tk-25-chains") {
                $chain4 += 25 * $products_quantity;
            } elseif ($products_model == "tk-30-chainslong") {
                $chain24 += 30 * $products_quantity;
            } elseif ($products_model == "tk-30-chains") {
                $chain4 += 30 * $products_quantity;
            } elseif ($products_model == "tk-35-chainslong") {
                $chain24 += 35 * $products_quantity;
            } elseif ($products_model == "tk-35-chains") {
                $chain4 += 35 * $products_quantity;
            } elseif ($products_model == "tk-30-dogtags") {
                $tags += 30 * $products_quantity;
            } else {
                $tags += $products_quantity;
            }
        }

        //right here we should have the exact quantity of all the items.  Lets see if it fits into a small box.
        $fitTag = $tags / 500;
        $fitChain24 = $chain24 / 300;
        $fitChain4 = $chain4 / 2000;
        $fitKeyfob = $keyfob / 50;

        if ($lanyard > 0) {
            $price = [
                "error" => 'Temporary No Box for lanyards until makayla sends me the formula'
            ];

            return $price;
        }

        $total = $fitTag + $fitChain24 + $fitChain4 + $fitKeyfob + $shirts + $lanyard;

        if ($total > 1) {

            // I think this is medium box
            $fitTag = $tags / 1500;
            $fitChain24 = $chain24 / 1600;
            $fitChain4 = $chain4 / 10000;
            $fitKeyfob = $keyfob / 250;

            $total = $fitTag + $fitChain24 + $fitChain4 + $fitKeyfob + $shirts + $lanyard;

            if ($total > 1) {

                //try large box
                $fitTag = $tags / 5000;
                $fitChain24 = $chain24 / 3600;
                $fitChain4 = $chain4 / 18000;
                $fitShirts = $shirts / 5;
                $fitKeyfob = $keyfob / 500;

                $total = $fitTag + $fitChain24 + $fitChain4 + $fitKeyfob + $fitShirts + $lanyard;

                // THE CODE WILL ALLWAYS RETURN THIS VALUE WHEN THERE IS LANYARDS
                // BECAUSE THE VALUE OF THE LANYARD IS SET TO 2 BY DEFAULT
                // THATS WHY IF THE TOTAL IS ALWAYS GREATER THAN 1 IF THERE IS LANYARDS
                if ($total > 1) {
                    //it doesn't even fit into a large box lets jack up the price to $400 or something crazy until we can get it disabled.
                    $price = 400;
                } else {
                    return ($delivery_country == 'United States' || $delivery_country == 'US') ? 17.95 : 59.95;
                }
            } else {
                return ($delivery_country == 'United States' || $delivery_country == 'US') ? 12.95 : 45.95;
            }
        } else {
            return ($delivery_country == 'United States' || $delivery_country == 'US') ? 7.95 : 24.95;
        }
        return $price;
    }

    /**
     * method upsCharge
     * description get the shipping rate from the ups api
     *
     * @var customersId {{ int }} selected customers id
     * @var countryId {{ int }} delivery address country id
     */
    public function upsCharge($basketData, $delivery_country, $delivery_post_code) {
        $totalWeight = 0;

        foreach ($basketData as $row) {
            // get the total weight
            if (!is_null($row['Product']['products_weight'])) {
                $weight = ($row['Product']['products_weight'] * $row['CustomersBasket']['customers_basket_quantity']);
                $totalWeight += $weight;
            }
        }

        // ups doesnt accept zero weight send 1 ounce (0.0625) minimum
        $finalWeight = ($totalWeight <= 0) ? 0.0625 : $totalWeight;

        $rate = new Ups;
        $rate->upsProduct("GND"); // See upsProduct() function for codes
        $rate->origin("92508", "US"); // Use ISO country codes!
        $rate->dest(str_replace(' ', '', $delivery_post_code), $delivery_country); // Use ISO country codes!
        $rate->rate("RDP"); // See the rate() function for codes
        $rate->container("CP"); // See the container() function for codes
        $rate->weight($finalWeight);
        $rate->rescom("RES"); // See the rescom() function for codes
        $quote = $rate->getQuote();

        $upsErrorMsg = [
            "The requested service is unavailable between the selected locations.",
            "Invalid ConsigneePostalCode"
        ];

        if (!in_array($quote, $upsErrorMsg)) {
            $upsCharge = [
                "charge" => $quote,
                "type" => "Ground"
            ];
        } else {
            $upsCharge = [
                "error" => $quote
            ];
        }

        return $upsCharge;
    }

    /**
     * method getCustomerBasketItem
     * description get a sigle customers basket data selectd by id
     *
     * @var customersBasketId {{ int }} customers basket id
     */
    public function getCustomerBasketItem($customersBasketId, $customerId) {
        return $this->find("first", array(
                    "conditions" => array(
                        "customers_basket_id" => $customersBasketId,
                        "customers_id" => $customerId
                    ),
                    "contain" => array(
                        "Product" => array(
                            "ProductsDiscountQuantity",
                            "ProductsDescription" => array(
                                "fields" => array(
                                    "ProductsDescription.products_name", "ProductsDescription.products_description"
                                )
                            ),
                            "Manufacturer" => array(
                                "fields" => array("Manufacturer.manufacturers_name")
                            ),
                            "fields" => array(
                                "Product.products_model",
                                "Product.products_image",
                                "Product.products_id",
                                "Product.products_price",
                                "Product.master_categories_id",
                                "Product.products_weight",
                                "Product.products_quantity_order_min",
                                "Product.require_artwork"
                            )
                        )
                    )
        ));
    }

}
