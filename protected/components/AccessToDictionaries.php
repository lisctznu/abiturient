<?php

class AccessToDictionaries {

  public static function getAccessRulesToDictionaries() {
    return array(
        array('allow', // allow all users to perform 'update' and 'admin' actions
            'actions' => array('update', 'admin'),
            'users' => array('Users'),
        ),
        array('allow', // allow authenticated user to perform all CRUD actions
            'actions' => array('view', 'create', 'update', 'admin', 'delete'),
            'roles' => array('Admins', "Root"),
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

}

?>
