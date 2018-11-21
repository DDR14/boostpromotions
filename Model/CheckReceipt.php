<?php

App::uses("AppModel", "Model");

class CheckReceipt extends AppModel {

    public $tablePrefix = false;
    public $useTable = "check_receipts";
    public $primaryKey = "id";
    public $validate = array(
        "file" => array(
            "extension" => array(
                "rule" => array("extension", array("gif", "pdf", "png", "jpeg", "jpg")),
                "message" => "gif, png, pdf, jpeg files are only allowed."
            )
        )
    );

}
