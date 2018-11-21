<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');

/**
 * CakePHP TemplatesController
 * @author Meristone
 */
class TemplateController extends AppController {

    var $uses = [];

    public function index($name) {
        $this->set('name', $name);
    }

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

}
