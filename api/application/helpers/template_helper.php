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
 * CTL CodeIgniter Template Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Phil Schanely, Thomas Neal, CTL
 * @link		http://cedarville.edu/ctl
 */

// -----------------------------------------------------------------------------


if (!function_exists('show_view'))
{
    /**
     * Show series of view
     *
     * Automates displaying an application's views
     *
     * @param string|array $views
     * @param array $data
     * @param bool $show_head
     * @param bool $show_foot
     */
    function show_view($views,$data='dso',$show_head=TRUE,$show_foot=TRUE,$return=FALSE,$flashdata_key='feedback') 
    {
		
        $CI =& get_instance();

        if ($data === 'dso')
        {
            $data = $CI->dso->all;
        }
        
        if ($CI->session)
        {
            $flash = $CI->session->flashdata($flashdata_key);
            $flashdata = $flash ? TRUE : FALSE;
            
            if (is_object($data))
            {
                $data->flashdata = $flashdata;
                $data->flash = $flash;
            }
            elseif (is_array($data))
            {
                $data['flashdata'] = $flashdata;
                $data['flash'] = $flash;
            }
        }
        
        $master_head = 'html_head';
        $master_foot = 'html_foot';

        // Convert a single view to an array of views
        if (is_string($views)) {
            $views = array($views);
        }
        
        $content = '';

        // Show the top of a master wrap
        if ($show_head) {
            $content .= $CI->parser->parse($master_head,$data,$return);
        }
        
        // Show the top of a master wrap
        foreach ($views as $view) {
            $content .= $CI->parser->parse($view,$data,$return);
        }

        // Show the bottom of a master wrap
        if ($show_foot) {
            $return .= $CI->parser->parse($master_foot,$data,$return);
        }
        
        return $content;
    }
}