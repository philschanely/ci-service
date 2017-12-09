<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index($_key='all')
    {
        $result = $this->user_model->route($_key);
        if (is_array($result)) {
          foreach ($result as &$user) {
            unset($user->password);
          }
        } elseif (is_object($result)) {
          unset($result->password);
        }
        echo json_encode($result);
    }

}
