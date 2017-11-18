<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service
{
    private $CI;

    var $entity;
    var $entity_view;
    var $key;
    var $properties;
    var $calls;
    var $last_call;
    var $logs;
    var $plural;
    var $single;
    var $belongs_to;
    var $has_one;
    var $has_many;
    var $entries;
    var $sort_helper;

    function __construct($_params)
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('sort_helper');
        $this->configure($_params);
    }

    public function configure($_params)
    {
        // Pass provided parameters onto this object
        foreach ($_params as $key=>$val)
        {
            $this->$key = $val;
        }
        // Load any models needed based on relationships
        $this->load_models();
        // Clean up any optional parameters
        if (!isset($this->entity_view))
        {
            $this->entity_view = $this->entity;
        }
    }

    public function delete($_key)
    {
        if ($_key)
        {
            $this->CI->db->where($this->key, $_key);
            $this->CI->db->delete($this->entity);
            return TRUE;
        }
        return FALSE;
    }

    public function get($_key='all', $_data=array())
    {
        $results = NULL;

        if ($_key === 'all')
        {
            $this->parse_for_query_commands($_data);
            $results = cfr($this->entity_view, 'result');
            if (!empty($results))
            {
                foreach ($results as &$result)
                {
                    # $this->get_related($result);
                    $this->objectify($result);
                }
            }
        }
        elseif ($_key > 0)
        {
            $this->CI->db->where($this->key, $_key);
            $results = cfr($this->entity_view, 'row');
            $this->objectify($results);
        }

        return $results;
    }

    /**
     * For use by parent entitites, this method accepts a key value assumed to
     * match this entity's primary key. Streamlined for bulk calls, this method
     * loads and entries if they are not already loaded and then searches them
     * for the single desired match. If found it is return as an object.
     *
     * @param  string|int   $_key   The value to match in this entity's primary key field
     * @return object|NULL          Returns either the matching entry or NULL.
     */
    public function get_one($_key)
    {
        if (empty($this->entries))
        {
            $this->entries = $this->get('all');
        }

        if (!empty($this->entries))
        {
            foreach ($this->entries as $entry)
            {
                if ($entry->{$this->key} == $_key)
                {
                    return $entry;
                }
            }
        }
        return NULL;
    }

    /**
     * For use by parent entities, this method accepts a field and value
     * with which to query the list of entries for this entity and compile
     * a set of matches to return to that parent entity. It is streamlined to load
     * all entries for this entity first in order to speed up the matching process.
     *
     * @param  string $_field The field in which to search for a match. Most often
     * the field on this entity that contains a foreign key.
     * @param  mixed $_val   The matching value for the given field.
     * @return array         Returns a list of matching entries as objects.
     */
    public function get_many($_field, $_val)
    {
        // First ensure that entries for this method are loaded
        // in order to ensure faster processing for bulk requests
        if (empty($this->_entries))
        {
            $this->entries = $this->get('all');
        }
        // Build list of matching entries
        $matches = array();
        if (!empty($this->_entries))
        {
            foreach ($this->_entries as $entry)
            {
                if ($entry->$_field == $_val)
                {
                    $matches[] = $entry;
                }
            }
        }
        return $matches;
    }


    public function get_related(&$item)
    {
        // TODO: Determine if this is still needed given the objectify() method.
        // if (!empty($this->has_one))
        // {
        //     foreach ($this->has_one as $one_field => $one_entity)
        //     {
        //         $one_model = strtolower($one_entity) . '_model';
        //         $item->{$one_field} =
        //             $this->CI->{$one_model}->service->get_one($item->{$one_field});
        //     }
        // }

        // TODO: Build out this feature (see above)
        if (!empty($this->has_many))
        {

        }
    }

    public function load_models()
    {
        // Build a list of models to load
        // Based on relationship lists
        $models = array();
        if (!empty($this->has_many))
        {
            foreach ($this->has_many as $field=>$entity)
            {
                $models[] = strtolower($entity) . '_model';
            }
        }
        if (!empty($this->has_one))
        {
            foreach ($this->has_one as $field=>$entity)
            {
                $models[] = strtolower($entity) . '_model';
            }
        }
        if (!empty($this->belongs_to))
        {
            foreach ($this->belongs_to as $field=>$entity)
            {
                $models[] = strtolower($entity) . '_model';
            }
        }
        // Load the models
        if (!empty($models))
        {
            $this->CI->load->model($models);
        }
    }

    public function objectify(&$_item)
    {
        $new_item = new StdClass();
        if (empty($_item))
        {
            return FALSE;
        }
        foreach ($_item as $key=>$val)
        {
            $key_parts = explode('__', $key);
            if (count($key_parts) > 1)
            {
                $part1 = $key_parts[0];
                $part2 = $key_parts[1];
                if (!isset($_item->{$part1}))
                {
                    $_item->{$part1} = new StdClass();
                }
                $_item->{$part1}->{$part2} = $val;
            }
            else
            {
                $new_item->$key = $val;
            }
        }
        $_item = $new_item;
        return TRUE;
    }


    public function parse_for_properties($_data=array())
    {
        $props = array();
        if (empty($_data))
        {
            return $props;
        }

        foreach ($_data as $key=>$val)
        {
            if (in_array($key, $this->properties))
            {
                $props[$key] = $val;
            }
        }

        return $props;
    }

    public function parse_for_query_commands($_data)
    {
        if (empty($_data))
        {
            return FALSE;
        }

        foreach ($_data as $key=>$val)
        {
            switch ($key)
            {
                case 'filter':
                    $this->CI->db->where($val);
                    break;
                case 'sort':
                    if (is_array($val))
                    {
                        foreach ($val as $sort)
                        {
                            $parse_sort = str_replace('.', '__', $sort);
                            $this->CI->db->order_by($parse_sort);
                        }
                    }
                    elseif (is_string($val))
                    {
                        $parse_sort = str_replace('.', '__', $val);
                        $this->CI->db->order_by($parse_sort);
                    }
                    break;
            }
        }
    }

    public function post($_data)
    {
        $results = NULL;

        if (!empty($_data))
        {
            $post_data = $this->parse_for_properties($_data);
            $this->CI->db->insert($this->entity, $post_data);
            $key = $this->CI->db->insert_id();
            $this->CI->db->where($this->key, $key);
            $results = cfr($this->entity_view, 'row');
            $this->objectify($results);
        }

        return $results;
    }

    /**
     * Method for handling updates to this entity
     * @param  string|int $_key  Primary key value used to select the specific
     * item being updated from this entity.
     * @param  array $_data Data to be used in the update
     * @return object Returns the complete updated item as an objct.
     */
    public function put($_key, $_data)
    {
        $results = NULL;

        if (!empty($_data))
        {
            $put_data = $this->parse_for_properties($_data);
            $this->CI->db->where($this->key, $_key);
            $this->CI->db->update($this->entity, $put_data);

            $this->CI->db->where($this->key, $_key);
            $results = cfr($this->entity_view, 'row');
            $this->objectify($results);
        }

        return $results;
    }

    /**
     * Routes an incoming request to the appropriate service method,
     * allowing those methods to also stand alone for direct calls
     * from the related model. Sends data fromt he appropriate input strea
     * on and the provided key on to the method based on the HTTP verb for the
     * request.
     *
     * @param  string|int $_key The keyword or key value for a given request.
     * @return mixed       Returns the resulting data from the service method,
     * or FALSE if not routed successfully.
     */
    public function route($_key)
    {
        $method = $this->CI->input->method(TRUE);
        switch($method)
        {
            case "DELETE":
                return $this->delete($_key);
                break;
            case "POST":
                $data = $this->CI->input->post();
                return $this->post($data);
                break;
            case "PUT":
                $data = $this->CI->input->input_stream();
                return $this->put($_key, $data);
                break;
            case "GET":
            default:
                $data = $this->CI->input->get();
                $results = $this->get($_key, $data);
                if ($_key === 'all') {
                    $results = (object) array(
                        $this->plural => $results
                    );
                }
                return $results;
                break;
        }
        return FALSE;
    }

    private function _log()
    {

    }

}

function compare_items($item1, $item2)
{
    $val = 0;

}
