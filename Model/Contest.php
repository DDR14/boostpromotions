<?php

App::uses("AppModel", "Model");

class Contest extends AppModel {

    public $useTable = "contests";
    public $primaryKey = "id";
    public $tablePrefix = 'gw_';

}
