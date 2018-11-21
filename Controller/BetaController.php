<?php

/*
 * BAND AID SOLUTION TO OLD LINKS
 */

App::uses('AppController', 'Controller');

/**
 * CakePHP BetaController
 * @author Meristone
 */
class BetaController extends AppController {

    public function beforeFilter() {
        $this->redirect(substr($this->here, 5));
    }

}
