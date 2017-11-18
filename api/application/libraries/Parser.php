<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Parser Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/parser.html
 */
class CI_Parser {

    /**
     * Left delimiter character for pseudo vars
     *
     * @var string
     */
    public $l_delim = '{';

    /**
     * Right delimiter character for pseudo vars
     *
     * @var string
     */
    public $r_delim = '}';

    /**
     * Reference to CodeIgniter instance
     *
     * @var object
     */
    protected $CI;
    
    // --------------------------------------------------------------------
    // CUSTOM PROPERTIES
    // --------------------------------------------------------------------
    
    /**
     * @var string The string signal used in conditional tag pairs such as:
     * {is_true?}Show this if is_true is true.{/is_true?}
     */
    var $conditional_signal = '?';
    
    /**
     * @var string The string signal used for calling values from within an object
     * such as: {object.value}
     */
    var $object_signal = '.';

    /**
     * @var string The string signal used between a variable and the subview
     * to call and substitute such as: {variable~~>path/to/subview}
     */
    var $subview_signal = '~~>';

    // --------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @return	void
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        log_message('info', 'Parser Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Parse a template
     *
     * Parses pseudo-variables contained in the specified template view,
     * replacing them with the data in the second param
     *
     * @param	string
     * @param	array
     * @param	bool
     * @return	string
     */
    public function parse($template, $data, $return = FALSE)
    {
        $template = $this->CI->load->view($template, $data, TRUE);
        return $this->_parse($template, $data, $return);
    }

    // --------------------------------------------------------------------

    /**
     * Parse a String
     *
     * Parses pseudo-variables contained in the specified string,
     * replacing them with the data in the second param
     *
     * @param	string
     * @param	array
     * @param	bool
     * @return	string
     */
    public function parse_string($template, $data, $return = FALSE)
    {
        return $this->_parse($template, $data, $return);
    }

    // --------------------------------------------------------------------

    /**
     * CUSOTM OVERRIDE
     * Parse a template
     *
     * Parses pseudo-variables contained in the specified template,
     * replacing them with the data in the second param
     *
     * @param	string
     * @param	array
     * @param	bool
     * @return	string
     */
    protected function _parse($template, $data, $return = FALSE, $subview_name='root')
    {
        if ($template === '')
        {
            return FALSE;
        }
        
        // Match variables
        $pattern1_matches = array();
        $pattern1 = '#\{([a-zA-Z0-9\_\-]+)[\.a-zA-Z0-9\_\-]*\?*\}#s';
        preg_match_all($pattern1, $template, $pattern1_matches);
        
        // Match subview calls
        $pattern2_matches = array();
        $pattern2 = '#\{([a-zA-Z0-9\_\-\.]+)\~\~\>[a-zA-Z0-9_\-\/]*\?*\}#s';
        preg_match_all($pattern2, $template, $pattern2_matches);
        
        $matches = array_merge($pattern1_matches[1], $pattern2_matches[1]);
        
        $replace = array();
        
        if (is_array($data) || is_object($data))
        {
            foreach ($data as $key => $val)
            {
                if (in_array($key, $matches))
                {
                    $this->_replace_value($key, $val, $template, $replace);
                }
            }
        }

        if ($return === FALSE)
        {
            $this->CI->output->append_output($template);
        }
        
        return $template;
    }

    // --------------------------------------------------------------------

    /**
     * Set the left/right variable delimiters
     *
     * @param	string
     * @param	string
     * @return	void
     */
    public function set_delimiters($l = '{', $r = '}')
    {
        $this->l_delim = $l;
        $this->r_delim = $r;
    }

    // --------------------------------------------------------------------

    /**
     * CUSTOM OVERRIDE
     * Parse a single key/value
     *
     * @param	string
     * @param	string
     * @param	string
     * @return	string
     */
    protected function _parse_single($key, $val, &$string)
    {
        $string = str_replace($this->l_delim.$key.$this->r_delim, (string) $val, $string);
        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * CUSTOM OVERRIDE
     * Parse a tag pair
     *
     * Parses tag pairs: {some_tag} string... {/some_tag}
     *
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    protected function _parse_pair($variable, $data, &$string)
    {
        $subview_matches = $this->_match_subview_call($variable, $string);
        if ($subview_matches !== FALSE)
        {
            // Load the subview found in $match[1]
            for ($i=0; $i < count($subview_matches[0]); $i++)
            {
                $subview_result = '';
                
                $template = $this->CI->load->view($subview_matches[1][$i], '', TRUE);
                $j = 1;
                foreach ($data as $datum)
                {
                    if (is_array($datum) || is_object($datum))
                    {
                        $subview_result .= $this->_parse($template, $datum, TRUE, $variable . '-' . $j);
                    }
                    $j++;
                }
                $string = str_replace($subview_matches[0][$i], $subview_result, $string);
            }
            return TRUE;
        }
        
        $replace = array();
        $matches = array();
        preg_match_all(
            '#'.preg_quote($this->l_delim.$variable.$this->r_delim).'(.+?)'.preg_quote($this->l_delim.'/'.$variable.$this->r_delim).'#s',
            $string,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match)
        {
            $array_output = '';
            foreach ($data as $row)
            {
                $temp = array();
                if (is_array($row) || is_object($row))
                {
                    $array_template = $match[1];
                    foreach ($row as $key => $val)
                    {
                        $this->_replace_value($key, $val, $array_template, $temp);   
                    }
                    $array_output .= $array_template;
                }
            }
            $string = str_replace($match[0], $array_output, $string);
        }
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------
    // Custom methods
    // --------------------------------------------------------------------

    /**
     * CUSTOM: Matches a boolean pair: {is_true?}...{/is_true?}
     * 
     * @param string $variable The name of the variable to search for
     * @param string $string The template string in which to search
     * 
     * @return boolean|array The match results
     */
    protected function _match_conditional_pair($variable, $string)
    {
        $match = array();
        $pattern = "|" 
             . preg_quote($this->l_delim . $variable . $this->conditional_signal . $this->r_delim) 
             . "(.+?)" 
             . preg_quote($this->l_delim . '/' . $variable . $this->conditional_signal . $this->r_delim) 
             . "|s";
        $preg_match = preg_match($pattern, $string, $match);
        
        if (!$preg_match)
        {
            return FALSE;
        }

        return $match;
    }
    
    // --------------------------------------------------------------------

    /**
     * CUSTOM: Match a subview call: {variable~~>path/to/subview}
     * 
     * @param string $variable The name of the variable to search for
     * @param string $string The template string in which to search
     * 
     * @return bool|array FALSE or the match array
     */
    protected function _match_subview_call($variable, $string)
    {
        $match = array();
        $pattern = "|" 
            . preg_quote($this->l_delim . $variable . $this->subview_signal) 
            . "(.+?)" 
            . preg_quote($this->r_delim) 
            . "|s";
        $preg_match = preg_match_all($pattern, $string, $match);
        if (!$preg_match)
        {
            return FALSE;
        }
        return $match;
    }
    
    // --------------------------------------------------------------------

    /**
     * CUSTOM: Parse a boolean tag pair
     *
     * Parses tag pairs:  {some_tag} string... {/some_tag}
     * 
     * @param string $variable The name of the variable to search for
     * @param bool $value The boolean value of that variable
     * @param string $string The string template in which to search
     * @param array|object|null $parent_data The object or array containing surrounding
     * data that should be searched in any match in order to deal with nested conditions
     * 
     * @return array The resulting matched content
     */
    protected function _parse_boolean_pair($variable, $value, &$string)
    {
        $this->CI->benchmark->mark('match_bool_' . $variable . '_start');
        
        $match_str = '#' . preg_quote($this->l_delim . $variable . $this->conditional_signal . $this->r_delim)
                . '(.*?)'
                . preg_quote($this->l_delim . '/' . $variable . $this->conditional_signal . $this->r_delim)
                .'#s';
        $matches = array();
        preg_match_all(
            $match_str,
            $string,
            $matches,
            PREG_SET_ORDER
        );
        
        foreach ($matches as $match)
        {
            if ($value === TRUE)
            {
                $string = str_replace($match[0], $match[1], $string);
            }
            else
            {
                $string = str_replace($match[0], '', $string);
            }
        }
        
        $this->CI->benchmark->mark('match_bool_' . $variable . '_end');
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
     * 
     * CUSTOM: Parse a tag object in dot notation
     *
     * Parses tag as obj:  {some_tag.property}
     * 
     * @param string $variable The variable object name
     * @param object $data The object data to substitute in the template
     * @param string $string The template string in which to search
     * 
     * @return array The resulting matched content
     */
    protected function _parse_obj($variable, $data, &$string)
    {
        $replace = array();
        $subview_match = $this->_match_subview_call($variable, $string);
        if ($subview_match !== FALSE)
        {
            // Load the subview found in $match[1]
            $template = $this->CI->load->view($subview_match[1][0], $data, TRUE);
            $subview_result = $this->_parse($template, $data, TRUE, $variable);
            $string = str_replace($subview_match[0][0], $subview_result, $string);
            return TRUE;
        }
        
        // Otherwise parse as a normal simple value with dot notation
        foreach ($data as $key => $val)
        {
            $this->_replace_value(
                $variable . $this->object_signal . $key, 
                $val, 
                $string, 
                $replace
            );
        }
        
        return TRUE;
    }
    
    // --------------------------------------------------------------------

    /**
     * CUSTOM: Replace value 
     * 
     * Replace a variable in the provided template using the best method
     * based on its data type.
     * 
     * @param string $key the variable to search for
     * @param mixed $val the value to replace
     * @param string $template the string to search in 
     * @param array $match_list the list of matches this search adds onto
     * @param array|object $parent_data the parent data set for nested searches
     * 
     * @return array
     */
    protected function _replace_value($key, $val, &$template, &$match_list)
    {
        // Treat each data type appropriately
        if (is_array($val))
        {
            $this->_parse_pair($key, $val, $template);
        }
        elseif (is_object($val))
        {
            $this->_parse_obj($key, $val, $template);
        }
        elseif (is_bool($val))
        {
            $this->_parse_boolean_pair($key, $val, $template);
        }
        else
        {
            $this->_parse_single($key, (string) $val, $template);
        }
        
        return TRUE;
    }
    
    protected function _replace_values($string, $replace_values, $variable='root')
    {
        /*
        foreach ($replace_values as $search => $replace)
        {
            $string = str_replace($search, $replace, $string);
        }
        
        return $string;
         * 
         */
        return strtr($string, $replace_values);
    }
}


/* SNIPPET #1
    foreach ($data as $key => $val)
    {
        $replace = array_merge(
            $replace,
            is_array($val)
                ? $this->_parse_pair($key, $val, $template)
                : $this->_parse_single($key, (string) $val, $template)
        );
    }
    unset($data);
    $template = strtr($template, $replace);
 */
/* SNIPPET #2
    foreach ($row as $key => $val)
    {
        if (is_array($val))
        {
            $pair = $this->_parse_pair($key, $val, $match[1]);
            if ( ! empty($pair))
            {
                $temp = array_merge($temp, $pair);
            }

            continue;
        }

        $temp[$this->l_delim.$key.$this->r_delim] = $val;
    }

    $str .= strtr($match[1], $temp);
 */