<?php

App::uses("AppModel", "Model");

class Charge extends AppModel {

    // we use another database so we set the db
    var $useDbConfig = 'boostpr1_tododash';

    public function getPaid($orderId) {
        $amountPaid = $this->find("all", array(
            "fields" => array(
                "SUM(Charge.amount) AS total"
            ),
            "conditions" => array(
                "orders_id" => $orderId
            )
        ));

        return $amountPaid[0][0]["total"];
    }

}
