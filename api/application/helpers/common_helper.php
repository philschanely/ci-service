<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CTL CodeIgniter Common Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Phil Schanely, CTL
 * @link		http://cedarville.edu/ctl
 */

// -----------------------------------------------------------------------------

if (!function_exists('redirect_to')) 
{
    /**
    * Redirect to ...
    * 
    * Redirect to a provided URL
    * 
    * @param string $location The desired location; default results in redirect
    * to noaccess.php
    * @return null
    */
    function redirect_to($location = NULL) {
        if ($location != NULL) {
            header("Location: {$location}");
            exit;
        } else {
            header("Location: noaccess.php");
            exit;
        }
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('echoPretty'))
{
    /**
    * Echo "Prettily"
    *
    * Echos a message with a line break following; implements a quick debugging
    * toggle
    *
    * @param <type> $value the message to be displayed
    * @param <type> $cond a boolean used to easily toggle whether this function
    * does its magic or not.
    */
    function echoPretty($value,$cond=true) {
        if ($cond) echo $value . '<br />';
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('echoValue'))
{
    /**
    *
    * Echo a Value
    *
    * Echos a bolded label and a value with a line break following; builds on
    * echoPretty() and also includes a quick toggle for debugging use.
    *
    * @see echoPretty()
    * @param string $label The label to be bolded
    * @param string $value The value to display
    * @param boolean $cond a boolean used to easily toggle whether this function
    * does its magic or not.
    */
    function echoValue($label,$value,$cond=true) {
        if ($cond)  echoPretty("<strong>{$label}</strong>: {$value}");
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('showError'))
{
    /**
    * Show an Error
    *
    * Shows a message with a bolded "Error" preceeding it; builds on echoValue;
    * includes toggle for debugging.
    *
    * @see echoValue()
    * @param string $msg the error message
    * @param <type> $cond a boolean used to easily toggle whether this function
    * does its magic or not
    */
    function showError($msg,$cond=true) {
        if ($cond) echoValue('ERROR', $msg);
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('dumpVar'))
{
    /**
    * Dump a Variable
    *
    * Dumps the data type and contents of a variable and formats it inside <pre>
    * tags. Includes quick toggle for debugging.
    * 
    * @param boolean|integer|float|object|array|string $var the value to dump
    * @param boolean $cond a boolean used to easily toggle whether this function
    * does its magic or not
    */
    function dumpVar($var,$cond=true) {
        if ($cond) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('dumpVarLab'))
{
    /**
    * Dump a Variable with a label
    *
    * Same as dumpVar but with a label
    * 
    * @param string
    * @param boolean|integer|float|object|array|string $var the value to dump
    * @param boolean $cond a boolean used to easily toggle whether this function
    * does its magic or not
    */
    function dumpVarLab($label, $var, $cond=true) {
        if ($cond) {
            echoPretty($label . ': ');
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('dumpVarPretty'))
{
    /**
    * Dump a Variable "Prettily"
    *
    * Displays the provided variable in clean HTML markup based on the data type;
    * - Arrays show as an ordered list (recursively as well)
    * - Booleans show inside a paragraph
    * - Objects show as an ordered list (recursively as well)
    *
    * @param boolean|array|object $var the variable to display
    */
    function dumpVarPretty($var) {
        if (is_array($var)) {
            if (!empty($var)) {
                echo '<p>ARRAY:</p>';
                echo '<ol>';
                foreach ($var as $label => $val) {
                    echo "<li>$label: ";
                    dumpVarPretty($val);
                    echo '</li>';
                }
                echo '</ol>';
            } else {
                echo '<p>ARRAY (empty)</p>';
            }
        } elseif (is_bool($var)) {
            echo '<p>BOOL: ';
            if ($var) {
                echo 'True';
            } elseif (!$var) {
                echo 'False';
            }
            echo '</p>';
        } elseif (is_object($var)) {
            if (!empty($var)) {
                echo '<p>OBJ: '.get_class($var).'</p>';
                echo '<ol>';
                foreach ($var as $label => $val) {
                    echo "<li>$label: ";
                    dumpVarPretty($val);
                    echo '</li>';
                }
                echo '</ol>';
            } else {
                echo '<p>OBJ (empty)</p>';
            }
        } else {
            echo $var;
        }
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('ep'))
{
    /**
    * Alias for echoPretty
    * 
    * @param <type> $value the message to be displayed
    * @param <type> $cond a boolean used to easily toggle whether this function
    * does its magic or not.
    */
    function ep($string, $cond=true) {
        echoPretty($string, $cond);
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('dv'))
{
    /**
    * Alias for dumpVar
    * 
    * @param string
    * @param boolean|integer|float|object|array|string $var the value to dump
    * @param boolean $cond a boolean used to easily toggle whether this function
    * does its magic or not
    */
    function dv($var, $cond=true) {
        dumpVar($var, $cond);
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('dvl'))
{
    /**
    * Alias for dumpVarLab
    * 
    * @param string
    * @param boolean|integer|float|object|array|string $var the value to dump
    * @param boolean $cond a boolean used to easily toggle whether this function
    * does its magic or not
    */
    function dvl($label, $var, $cond=true) {
        dumpVarLab($label, $var, $cond);
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('checkForResults'))
{
    /**
    * Check for database results
    *
    * Checks a provided query object for any number of rows
    * If desired number of rows is found returns provided type
    *
    * @param object $query object
    */
    function checkForResults($query,$return_type='result',$expected_rows=0) {
		$result = NULL;
		if ($query->num_rows() > $expected_rows) 
		{
			$result = $query->$return_type();
		}
		return $result;
	}
}
// -----------------------------------------------------------------------------

if (!function_exists('cfr'))
{
    /**
    * Alias for Check for database results
    *
    * Checks a provided query object for any number of rows
    * If desired number of rows is found returns provided type
    *
    * @param object $query object
    */
    function cfr($table,$return_type='result',$expected_rows=0) {
        $CI =& get_instance();
        $query = $CI->db->get($table);
        $result = NULL;
        if ($query->num_rows() > $expected_rows) 
        {
            $result = $query->$return_type();
        }
        return $result;
    }
}
if (!function_exists('addStylesheet'))
{
    /**
    * Returns a stylesheet array item with the provided path
    *
    * @param string $path_from_app_root The stylesheets path from the application root
    */
    function addStylesheet($path_from_app_root) {
		$stylesheet = array();
		$stylesheet['path'] = $path_from_app_root;
		return $stylesheet;
	}
}
if (!function_exists('createStylesheetList'))
{
    /**
    * Returns a stylesheet array item with sub-rrays representing stylesheets for provided paths
    *
    * @param string $path_from_app_root The stylesheets path from the application root
    */
    function createStylesheetList($stylesheet_paths) {
		$stylesheets = array();
		if (is_array($stylesheet_paths)) {
			foreach($stylesheet_paths as $path) {
				$stylesheets[] = addStylesheet($path);
			}
		}
		else {
			$stylesheets[] = addStylesheet($stylesheet_paths);
		}
		return $stylesheets;
	}
}
if (!function_exists('breadcrumbs'))
{
    /**
    * Returns a bootstrap breadcrumb list
    *
    * @param string $crumb_list The list of indexed list of urls
    */
    function breadcrumbs($crumb_list=array()) {
		$CI =& get_instance();
        $CI->load->helper('html');
        $CI->load->helper('url');
        
		$default_crumb_list = array('Home'=>'');
		$crumb_list = array_merge($default_crumb_list,$crumb_list);
		
        $crumbs = '';
        $crumb_links = array();
        $crumb_count = count($crumb_list);
        $labels = array_keys($crumb_list);
        $paths = array_values($crumb_list);
        for ($i = 0; $i<$crumb_count; $i++)
        {
            $label = $labels[$i];
            $path = $paths[$i];
            if ($i == $crumb_count-1)
            {
                $crumb_links[] = $label;
            }
            else
            {
                $crumb_links[] = anchor($path,$label) . ' <span class="divider">/</span>';
            }
        }
        $crumb_string = ul($crumb_links,array('class'=>'breadcrumb'));
		$crumb_xml = simplexml_load_string($crumb_string);
        $crumb_xml_items = $crumb_xml->li;
        $crumb_count = count($crumb_xml_items);
        $last_crumb = $crumb_xml_items[$crumb_count-1];
        $last_crumb->addAttribute('class','active');
        $crumbs  = $crumb_xml->asXML();
        return $crumbs;
	}
}


if (!function_exists('prepare_options'))
{
    function prepare_options($options=array(), $selected_option=0, $index_for_comparison='ID', $prefix='') {
        $CI =& get_instance();
        $option_list = array();
        foreach ($options as &$option)
        {
            if (is_array($option))
            {
                $new_option = array();
                if (is_array($selected_option))
                {
                    $new_option['is_selected'] = 
                        in_array($option[$index_for_comparison], $selected_option)
                        ? TRUE : FALSE;
                }
                else
                {
                    $new_option['is_selected'] = 
                        $option[$index_for_comparison] == $selected_option
                        ? TRUE : FALSE;
                }
                foreach ($option as $key=>$value)
                {
                    $new_option[$prefix.$key] = $value;
                }
                $option_list[] = $new_option;
            }
            else
            {
                $new_option = array();
                if (is_array($selected_option))
                {
                    $new_option['is_selected'] = 
                        in_array($option->{$index_for_comparison}, $selected_option)
                        ? TRUE : FALSE;
                }
                else
                {
                    $new_option['is_selected'] = 
                        $option->{$index_for_comparison} == $selected_option
                        ? TRUE : FALSE;
                }
                foreach ($option as $key=>$value)
                {
                    $new_option[$prefix.$key] = $value;
                }
                $option_list[] = (object) $new_option;
            }
        }
        
        return $option_list;
    }
}

if (!function_exists('get_to_string'))
{
    /**
    * Returns a bootstrap breadcrumb list
    *
    * @param array $get 
    */
    function get_to_string($get=array()) {
        $get_vars = '';
        if (!empty($get) && is_array($get))
        {
            $is_first = FALSE;
            foreach ($get as $key=>$val) {
                $get_vars .= $is_first ? '&' : '';
                $get_vars .= $key . '=';
                $get_vars .= urlencode($val);
                $is_first = FALSE;
            }
        }
        return $get_vars;
    }
}

if (!function_exists('reorder_range'))
{
    /**
     * Reorder range method to be used in simple rearrange and complex rearrange.
     * NOTE: This does NOT change the targeted item, but does affect those between
     * its original position and the new position.
     *
     * @param string $table_name The name of the table in which the changes occur
     * @param int $new_order The new position the target element is moving to
     * @param int $current_order The current position the target element is moving from
     * @param array $group_terms Any terms to further modify which elements are affected
     * @param array $update_fields Any fields that need to be used to filter the update query
     * @param boolean $bump_full_range Indicates that the whole range should bump up (such as to make room for a new item)
     */
    function reorder_range(
        $table_name, 
        $new_order, 
        $current_order, 
        $group_terms=array(), 
        $update_fields=array(), 
        $bump_full_range=FALSE
    ) 
    {
        $CI =& get_instance();
        // Determine the range parameters
        if ($bump_full_range)
        {
            $lower = $new_order;
            $upper = $current_order;
            $range_terms = array(
                'order >=' => $lower,
                'order <=' => $upper
            );
            $direction_of_change = 1;
        }
        // When new order is higher current order
        elseif ($new_order > $current_order)
        {
            $lower = $current_order;
            $upper = $new_order;
            $range_terms = array(
                'order >' => $lower,
                'order <=' => $upper
            );
            $direction_of_change = -1;
        }
        // When the new order is less than the current order
        else
        {
            $lower = $new_order;
            $upper = $current_order;
            $range_terms = array(
                'order >=' => $lower,
                'order <' => $upper
            );
            $direction_of_change = 1;
        }
        // Select affected range
        $search_terms = array_merge($group_terms, $range_terms);
        $CI->db->where($search_terms);
        $CI->db->order_by('order');
        $affected_items = cfr($table_name, 'result_array');
        if (!empty($affected_items))
        {
            foreach ($affected_items as $item)
            {
                $update_terms = array();
                foreach ($update_fields as $field)
                {
                    $value = $item[$field];
                    $update_terms[$field] = $value; 
                }
                $affected_order = $item['order'] + $direction_of_change;
                $CI->db->where($update_terms);
                $CI->db->update($table_name, array(
                    'order' => $affected_order  
                ));
            }
        }
    }
}


if (!function_exists('simple_rearrange'))
{
    /**
    * Simple Rearrange method for sortable
    *
    * @param string $table_name 
    * @param int $new_order
    * @param array $item_terms (key value pairs for the moved item)
    * @param array $group_terms (other key value pairs for other items, not the moved one)
    * @param array $update_fields (array of fields used to updated affected items)
    */
    function simple_rearrange($table_name, $new_order, $item_terms, $group_terms, $update_fields) {
        $CI =& get_instance();
        // Get target item's info from the db
        $CI->db->where($item_terms);
        $item = checkForResults($CI->db->get($table_name), 'row_array');
        // Store target item's current order
        $current_order = $item['order'];
        // Update item's current order to 0
        $CI->db->where($item_terms);
        $CI->db->update($table_name, array(
            'order' => 0
        ));
        // Update all affected items
        reorder_range($table_name, $new_order, $current_order, $group_terms, $update_fields);

        // Update current's item's order to new order
        $CI->db->where($item_terms);
        $CI->db->update($table_name, array(
            'order' => $new_order
        ));
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('prep_view_results'))
{
    /**
    * 
    * 
    * @param result (can be anything)
    * 
    * 
    */
    function prep_view_results($result, $result_name='result') {
        $CI =& get_instance();

        $result_found_name = $result_name . '_found';
        $result_not_found_name = $result_name . '_not_found';

        if (!empty($result)) 
        {
            $CI->dso->$result_name = $result;
            $CI->dso->$result_found_name = TRUE;
            $CI->dso->$result_not_found_name = FALSE;
        }
        else
        {
            $CI->dso->$result_found_name = FALSE;
            $CI->dso->$result_not_found_name = TRUE;
        } 
    }
}

// -----------------------------------------------------------------------------

if (!function_exists('generate_hash'))
{
    /**
    * 
    * 
    * @param int length
    * @param string character_set
    * 
    * 
    */
    function generate_hash($length=32, $character_set='') {
        $hash = '';

        if (empty($character_set))
        {
            $character_set = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        }

        $min = 0;
        $max = strlen($character_set) -1;

        for ($i = 0; $i < $length; $i++)
        {
            $random_num = rand($min, $max);
            $character = substr($character_set, $random_num, 1);
            $hash .= $character;
        }

        return $hash;
    }
}

// -----------------------------------------------------------------------------


if (!function_exists('get_date_info'))
{
    /**
    * 
    * 
    * @param string date
    * 
    * 
    */
    function get_date_info($date)
    {
        $info = array();
        $info['timestamp'] = strtotime($date);
        $info['day'] = date('j', $info['timestamp']);
        $info['month'] = date('F', $info['timestamp']);
        $info['month_abbr'] = date('M', $info['timestamp']);
        $info['month_number'] = date('n', $info['timestamp']);
        $info['day_of_week'] = date('l', $info['timestamp']);
        $info['day_of_week_abbr'] = date('D', $info['timestamp']);
        $info['day_of_week_number'] = date('w', $info['timestamp']);

        return $info;
    }
}

// -----------------------------------------------------------------------------


if (!function_exists('trim_words'))
{
    /**
    * 
    * 
    * @param string $string
    * 
    * 
    */
    function trim_words($string, $count, $delimiter=' ', $ellipsis=FALSE) {
      $words = explode($delimiter, $string);

      if (count($words) > $count) 
      {

        array_splice($words, $count);
        $string = implode($delimiter, $words);
        
        if (is_string($ellipsis))
        {
          $string .= $ellipsis;
        }
        elseif ($ellipsis)
        {
          $string .= '&hellip;';
        }
      }
      return $string;
    }
}


// -----------------------------------------------------------------------------


if (!function_exists('create_flashdata_feedback'))
{
    /**
    * 
    * 
    * @param string $string
    * 
    */
    function create_flashdata_feedback($feedback, $class)
    {
        $CI =& get_instance();
        
        $feedback_markup = '<p class="alert ' . $class . '">' . $feedback . '</p>';
        $existing_feedback = $CI->session->flashdata('feedback');
        $CI->session->set_flashdata('feedback', $existing_feedback . $feedback_markup);
    }
}

// -----------------------------------------------------------------------------


if (!function_exists('enable_profiler'))
{
    
    function enable_profiler($condition=NULL)
    {
        $CI =& get_instance();
        $condition = $condition === NULL
            ? ENVIRONMENT == 'development'
            : $condition;
        $condition ? $CI->output->enable_profiler(TRUE) : TRUE;
    }
}
