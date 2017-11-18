<?php

require_once 'application/libraries/Service.php';

/**
 * Created by IntelliJ IDEA.
 * User: philschanely
 * Date: 4/19/17
 * Time: 2:56 PM
 */

class Task_model extends CI_Model {

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

        $this->entity = 'Task';
        $this->plural = 'tasks';
        $this->single = 'task';
        $this->key = 'id';
        $this->properties = array(
            'id',
            'description',
            'dueDate',
            'owner',
            'category',
            'taskType'
        );
        $this->belongs_to = array(
            'owner' => 'User',
            'category' => 'Category',
            'taskType' => 'Task_Type'
        );
        $this->has_one = array();
        $this->has_many = array();


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
