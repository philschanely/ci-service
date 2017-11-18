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
        $result = $this->user_model->route($_key);
        echo json_encode($result);
    }

}
