<?php

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

if (!function_exists('sort_lego'))
{
    function sort_lego(&$data, $sorts=array())
    {
        ep("Sorting legos...");

        // Parse sorting properties
        if (!empty($sorts))
        {
            $updated_data = array();
            foreach ($sorts as $sort)
            {
                $sort_parts = explode('.', $sort);
                if (count($sort_parts) > 1)
                {
                    sort_parts($data, $sort, $sort_parts);
                }
            }
        }

        // Make sorting proerties available at root


        // Sort array of objects



        // Remove sorting properties from root

    }
}

if (!function_exists('sort_parts'))
{
    function sort_parts(&$data, $original_sort, $sort_parts, $level=0)
    {
        $next_level = $level+1;
        $level_part = $sort_parts[$level];
        $next_level_part = $sort_parts[$next_level];
        foreach($data as &$item)
        {
            if (isset($item->{$sort_parts[$level]}))
            {
                if (count($sort_parts) === ($next_level+1))
                {
                    if (TRUE)
                    {
                        $item->{$original_sort} = $item->{$sort_parts[$level]}->{$sort_parts[$next_level]};
                    }

                }
            }
        }
    }
}
