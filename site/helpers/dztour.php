<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */

defined('_JEXEC') or die;

abstract class DZTourHelper
{
    public static function sprintf($format, $data)
    {
        preg_match_all( '/ (?<!%) % ( (?: [[:alpha:]_-][[:alnum:]_-]* | ([-+])? [0-9]+ (?(2) (?:\.[0-9]+)? | \.[0-9]+ ) ) ) \$ [-+]? \'? .? -? [0-9]* (\.[0-9]+)? \w/x', $format, $match, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        $offset = 0;
        $keys = array_keys($data);
        foreach( $match as &$value )
        {
            if ( ( $key = array_search( $value[1][0], $keys, TRUE) ) !== FALSE || ( is_numeric( $value[1][0] ) && ( $key = array_search( (int)$value[1][0], $keys, TRUE) ) !== FALSE) )
            {
                $len = strlen( $value[1][0]);
                $format = substr_replace( $format, 1 + $key, $offset + $value[1][1], $len);
                $offset -= $len - strlen( 1 + $key);
            }
        }
        return vsprintf( $format, $data);
    }
    
    public static function getTourName($tourid)
    {
        $db = JFactory::getDbo();
        $query = "SELECT title FROM #__dztour_tours WHERE id = ". (int) $tourid;
        $db->setQuery($query);
        
        return $db->loadResult();
    }
}

