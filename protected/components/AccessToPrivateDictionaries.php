<?php

class AccessToPrivateDictionaries {

  public static function getAccessRulesToDictionaries() {
    return array(
        array('allow', // allow authenticated user to perform all CRUD actions
            'actions' => array('view', 'create', 'update', 'admin', 'delete'),
            'users' => array('Admins'),
        ),
        array('deny', // deny all users
            'actions' => array('index'),
            'users' => array('*'),
        ),
    );
  }

}

?>
