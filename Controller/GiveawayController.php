<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');

/**
 * CakePHP GiveawayController
 * @author Meristone
 * 
 * controls boostpromotions.com/what is happening
 * 
 */
class GiveawayController extends AppController {

    var $uses = ['Contest'];

    public function index($id = 1) {

        if (strtolower($id) == 'usssa') {
            $this->layout = '';
            $this->render('usssa');
            return;
        }

        $contest = $this->Contest->find('first', [
            'conditions' => ['OR' => ['id' => $id, 'slug' => $id]]
        ]);

        if (!$contest) {
            throw new NotFoundException('Missing Controller');
        }

        $this->set(compact('contest', 'id'));
    }

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

}
