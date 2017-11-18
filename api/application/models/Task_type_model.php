<?php

require_once 'application/libraries/Service.php';

/**
 * Created by IntelliJ IDEA.
 * User: philschanely
 * Date: 4/19/17
 * Time: 2:56 PM
 */

class Task_type_model extends CI_Model {

    var $entity;
    var $key;
    var $properties;
    var $plural;
    var $single;
    var $service;
    var $_entries;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->entity = 'Task Type';
        $this->plural = 'taskTypes';
        $this->single = 'taskType';
        $this->key = 'id';
        $this->properties = array(
            'id',
            'name',
            'order',
            'alias'
        );
        $this->belongs_to = array();
        $this->has_one = array();
        $this->has_many = array(
           'taskType' => 'Task'
        );

        $this->service = new Service(array(
            'entity' => $this->entity,
            'properties' => $this->properties,
            'key' => $this->key,
            'plural' => $this->plural,
            'single' => $this->single,
            'belongs_to' => $this->belongs_to,
            'has_one' => $this->has_one,
            'has_many' => $this->has_many
        ));
    }

    public function route($_key=NULL)
    {
        return $this->service->route($_key);
    }

}
