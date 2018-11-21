<?php

App::uses("Component", "Controller");
App::uses("CakeEmail", "Network/Email");
App::uses("File", "Utility");

class UtilityComponent extends Component {

    /**
     * method email
     * description send email
     *
     * @var $view_vars {{ array }} variables that will be used in the email view
     * @var $layout {{ str }} the layout of the email
     * @var $tpl {{ str }} the template of the email
     * @var $subject {{ str }} email subject
     * @var $toAdd {{ str }} recieptients email address
     */
    public function email($view_vars, $layout, $tpl, $subject, $toAdd) {
        $Email = new CakeEmail('default');
        $Email->viewVars($view_vars);

        $Email->template($layout, $tpl)
                ->subject($subject)
                ->emailFormat('html')
                ->to($toAdd)
                ->from('customerservice@boostpromotions.com')
                ->send();
    }

    /**
     * method sendSms
     * description send SMS
     *
     * @var $userData {{ array }} contains the user mobile and carrier data
     * @var $message {{ str }} text message
     */
    public function sendSms($userData, $message) {
        $data = $userData;
        $mobile = preg_replace("/[^0-9]/", "", $data['mobile']);

        $to = $mobile . $data['carrier'];
        mail($to, '', $message);
    }

    /**
     * method generateCountrySelectOptions
     * description generate data for the register form select options box
     */
    public function generateCountrySelectOptions($option) {
        $data = ClassRegistry::init('Country');
        $countries = $data->find('all', ['fields'=>['countries_id', 'countries_name']]);
        
        $tmpData = [];
        if ($option == 1) {
            foreach ($countries as $country) {
                $tmpData[$country["Country"]["countries_id"]] = $country["Country"]["countries_name"];
            }
        } else {
            foreach ($countries as $country) {
                $tmpData[$country["Country"]["countries_name"]] = $country["Country"]["countries_name"];
            }
        }

        return $tmpData;
    }

    /**
     * method upload
     * description upload files into the server
     *
     * @var $fileData {{ array }} file data from the form.
     * @var $uploadFolder {{ str }} upload destination.
     * @var $replace {{ boolean }} set to true if you want to replace the file if it exists default false.
     */
    public function upload($fileData, $uploadFolder, $replace = false) {
        //$folder_url = WWW_ROOT . $uploadFolder; // local
        $folder_url = "../.." . $uploadFolder; // live

        $folder = explode("/", $uploadFolder);
        array_shift($folder);
        $folder = implode("/", $folder);

        // create folder if the folder don't exists
        if (!is_dir($folder_url)) {
            mkdir($folder_url);
        }

        // check if the folder is writable
        if (!is_writable($folder_url)) {
            die('Folder is not writable');
        }

        $fileData["name"] = preg_replace("/[^a-zA-Z0-9.]/", "", $fileData["name"]);
        if (file_exists($folder_url . $fileData["name"])) {

            if ($replace == false) {
                $error = array(
                    "message" => $fileData["name"] . " already exists.",
                    "type" => "error"
                );
                return $error;
            }

            // remove the existing file and replace it with a new one
            unlink($folder_url . $fileData["name"]);
        }

        // upload the new file
        $name = substr(md5(rand(1, 99999)), 0, 6) . "." . $fileData["name"];
        $success = move_uploaded_file($fileData["tmp_name"], $folder_url . "/" . $name);

        if (!$success) {
            $response = array(
                "message" => "System error: Failed to upload $name. Contact webmaster.",
                "type" => "error"
            );
        } else {
            $response = array(
                "url" => $name,
                "message" => "File $name has been successfully uploaded.",
                "type" => "success"
            );
        }

        return $response;
    }

    /**
     * method removeFile
     * description delete uploaded file from the server
     *
     * @var $fileName {{ str }} name of the file
     * @var $folder {{ str }} name of the main folder where you saved or your upload files
     */
    public function removeFile($fileName, $folder) {
        $folder_url = WWW_ROOT . $folder;

        if (file_exists($folder_url . $fileName)) {
            unlink($folder_url . $fileName);
            $response = array(
                "type" => "success",
                "message" => "File Deleted"
            );
        } else {
            $response = array(
                "type" => "error",
                "message" => "File not found"
            );
        }

        return $response;
    }

    /**
     * method checkTagType
     * description check the tag type
     *
     * @param $item {{ array }} contains the product data
     */
    public function checkTagType($item) {
        $tk = array("881", "878", "877", "880", "879", "876");
        $tkExtra = array("882", "883", "884", "885", "886", "887", "888");

        $item["Product"]["master_categories_id"] = (isset($item["Product"]["master_categories_id"])) ? $item["Product"]["master_categories_id"] : null;

        // Check if teachers kits or teachers kits add-ons
        if (in_array($item['Product']['products_id'], $tk)) {
            return 'TEACHERS KITS';
        } else if (in_array($item['Product']['products_id'], $tkExtra)) {
            return 'TEACHERS KITS ADD-ON';
        } else if (in_array($item["Product"]["products_id"], [990, 853])) {
            return "LANYARDS";
        } else {
            $model = $item['Product']['products_model'];
            $explodedModel = explode("-", $model);

            if (count($explodedModel) > 1) {
                if (in_array('MODIFIED', $explodedModel)) {
                    return 'MODIFIED';
                } else if (in_array('CUSTOM', $explodedModel)) {
                    return 'CUSTOM';
                } else if (in_array('STOCK', $explodedModel)) {
                    return 'STOCK';
                } else {
                    return 'OTHER';
                }
            } else {
                return 'OTHER';
            }
        }
    }

    /**
     *  method getTagQty
     * description gets the quantity of tags on a product, for determining price of a lanyard. must be inside a loop
     */
    public function getTagQty($order) {
        if ($order["Product"]["master_categories_id"] == 101 || $order['Product']['products_id'] == 888) {
            $tk = explode('-', $order["Product"]["products_model"]);
            return $tk[1];
        }
        $notIncludedInCount = [519, 194, 96, 192, 24];
        if (!in_array($order["Product"]["master_categories_id"], $notIncludedInCount)) {
            return $order["CustomersBasket"]["customers_basket_quantity"];
        }

        return 0;
    }

    public function getMobileCarriers() {
        return array(
            '@message.alltel.com' => 'Alltel',
            '@mmode.com' => 'AT&T Wireless',
            '@myboostmobile.com' => 'Boost Mobile',
            '@mobile.mycingular.com' => 'Cingular',
            '@mymetropcs.com' => 'MetroPCS',
            '@messaging.nextel.com' => 'Nextel',
            '@text.republicwireless.com' => 'Republic Wireless',
            '@messaging.sprintpcs.com' => 'Sprint PCS',
            '@tmomail.net' => 'T-Mobile',
            '@email.uscc.net' => 'US Cellular',
            '@vtext.com' => 'Verizon',
            '@vmobl.com' => 'Virgin Mobile USA'
        );
    }

}
