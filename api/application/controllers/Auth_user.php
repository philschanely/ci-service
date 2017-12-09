<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_User extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index($_key='all')
    {
        $auth = (object) array(
            'authenticated' => false,
            'user' => NULL
        );
        $result = $this->user_model->route($_key);
        if (count($result->users) > 0) {
            $auth->authenticated = true;
            $auth->user = $result->users[0];
            unset($auth->user->password);
        }
        echo json_encode($auth);
    }

}
