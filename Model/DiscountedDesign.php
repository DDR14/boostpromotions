<?php

App::uses("AppModel", "Model");

class DiscountedDesign extends AppModel {

    public $primaryKey = 'dd_id';
    public $belongsTo = [
        'Design' => [
            "className" => "Design",
            "foreignKey" => "design_id"
        ]
    ];

}
