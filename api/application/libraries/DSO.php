<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter Data Storage Object Class
 *
 * This class enables the creation of central data storage
 *
 * @package		CI Service
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Phil Schanely and Thomas Neal
 * @link		http://phislchanely.com
 */
class DSO {

    var $CI;
    var $data;
    var $ignore_flash;
    var $forced_transfers;

    /**
     * Constructor
     */
    public function __construct($config = array())
    {
        $this->CI =& get_instance();
        $this->data = array();
        $this->ignore_flash = FALSE;
        $this->forced_transfers = array();
        $this->load_defaults(); 
    }
    
    public function add_to($property,$value,$index=NULL)
    {
        // Add property to data if it does not already exist there
        if (!$this->is_set($property))
        {
            $this->$property = array();
        }
        
        // Add value either as indexed ...
        if ($index === NULL)
        {
            $this->data[$property][] = $value;
        }
        // ... or as associative
        else
        {
            $this->data[$property][$index] = $value;
        }
    }
    
    /**
     * Dump values
     * 
     * Looks for provided property in $this->data and passes it to dumpVar if found.
     * Of if no property is provided, dumps the whole $this->data. 
     * 
     * @param type $property
     * @return type 
     */
    public function dump($property=NULL)
    {
        if ($property===NULL)
        {
            dumpVar($this->data);
        } 
        elseif (array_key_exists($property,$this->data))
        {
            dumpVar($this->$property);
        }
        else
        {
            echoPretty('DSO property ' . $property . ' not found.');
        }
    }
    
    /**
     * Is value empty
     * 
     * Checks $this->data for provided property. If found, calls empty() and 
     * returns result. If not found, returns false.
     * 
     * @param string $property
     * @return boolean 
     */
    public function is_empty($property)
    {
        if (array_key_exists($property,$this->data)) 
        {
            return empty($this->data[$property]);
        }
        else
        {
            return TRUE;
        }
    }
    
    /**
     * Is value not empty
     * 
     * Returns inverse of $this->is_empty() with provided property
     * 
     * @param string $property
     * @return type 
     */
    public function is_not_empty($property)
    {
        return !$this->is_empty($property);
    }
    
    /**
     * Is value set
     * 
     * Checks $this->data for the provided value.
     * 
     * @param type $property
     * @return boolean 
     */
    public function is_set($property)
    {
        if (array_key_exists($property,$this->data)) 
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function transfer($values, &$array)
    {
        if (is_array($array))
        {
            if (is_array($values))
            {
                foreach ($values as $value)
                {
                    if ( ! array_key_exists($value, $array))
                        $array[$value] = $this->$value;
                }
            }
            else
            {
                if ( ! array_key_exists($values, $array))
                    $array[$values] = $this->$values;
            }
        }
        elseif (is_object($array))
        {
            if (is_array($values))
            {
                foreach ($values as $value)
                {
                    if ( ! isset($array->$value))
                        $array->$value = $this->$value;
                }
            }
            else
            {
                if ( ! isset($array->$values))
                    $array->$values = $this->$values;
            }
        }
    }
    
    /**
     * Merge values
     * 
     * Merges values currently in $this->data with the values in the provided
     * array. 
     * 
     * @param array $array 
     */
    public function merge($array)
    {
        $this->data = array_merge($this->data,$array);
    }
    
    /**
     * Load default properties
     * 
     * Available for application-level override in order to set global properties
     * and their defaults for use in views 
     */
    public function load_defaults()
    {
        $this->page_title = 'Application Page';
        $this->page_id = 'page-unknown';
        $this->base_url = base_url();
        $this->querystring = $_SERVER['QUERY_STRING'];
    }

    /**
     * load flash data
     * 
     * 
     * 
     * 
     * 
     */
    public function load_flashdata()
    {
        if (!$this->is_set('flashdata'))
        {
            $this->flashdata = (object) array(
                'flash' => '',
                'flash_found' => FALSE
            );
        }

        if ($this->CI->session->flashdata('feedback'))
        {
            $this->flashdata->flash = $this->CI->session->flashdata('feedback');
            $this->flashdata->flash_found = TRUE;
        }
        else
        {
            $this->flashdata->flash_found = FALSE;
        }
    }
    
    public function reload_defaults()
    {
        $this->data = array();
        $this->load_defaults();
    }
	
	/**
     * Sort DSO data to give preference to booleans
     * 
     */
    public function sort_data()
    {
        $booleans = array();
        $arrays = array();
        $objects = array();
        $others = array();

        foreach ($this->data as $key => $value) 
        {
            if (is_bool($value))
            {
                $booleans[$key] = $value;
            } 
            elseif (is_array($value)) 
            {
                $arrays[$key] = $value;
            }
            elseif (is_object($value))
            {
                $objects[$key] = $value;
            }
            else
            {
                $others[$key] = $value;
            }
        }
        
        ksort($booleans);
        ksort($arrays);
        ksort($objects);
        ksort($others);

        $this->data = array_merge($booleans, $arrays, $objects, $others);
        $this->populate_common_values();
    }
    
    public function populate_common_values()
    {
        if ( !empty($this->forced_transfers))
        {
            foreach ($this->data as $key=>&$value)
            {
                if (is_array($value))
                {
                    $this->transfer_common_values($value);
                }
            }
        }
    }
    
    public function transfer_common_values(&$value)
    {
        foreach ($value as $subkey=>&$subvalue)
        {
            if ( ! empty($subvalue) && (is_array($subvalue) || is_object($subvalue)))
            {
                $this->transfer($this->forced_transfers, $subvalue);
                $this->transfer_common_values($subvalue);
            }
        }
    }
	
    /**
     * Magic getter
     * 
     * Used to find provided propertis inside $this->data and return their values.
     * Also contains abitlity to retrieve all data on object with call to $this->all
     * 
     * @param string $varname
     * @return null 
     */
    public function __get($varname)
    {
        // If you call $this->dso->all, this first part is called ...
        if ($varname=='all')
        {
            // First, add flashdata
            if (!$this->ignore_flash)
            {
                $this->load_flashdata();
            }
            
            $this->sort_data(); 

            // ... and everything in $this->data is returned as an object.
            return (object) $this->data;
        }
        // Or if you request a specific item, we check for it in $this->data ...
        elseif (array_key_exists($varname,$this->data))
        {
            // ... and return it if it is found.
            return $this->data[$varname];
        }
        // Or if it is not found, we return NULL.
        else 
        {
            return NULL;
        }
    }
	
    /**
     * Magic setter
     * 
     * User to set the provided property and value in $this->data
     * 
     * @param string $varname
     * @param type $value 
     */
    public function __set($varname, $value)
    {
        $this->data[$varname] = $value;
    }
}

// END CI_DSO class

/* End of file DSO.php */
/* Location: ./system/libraries/DSO.php */