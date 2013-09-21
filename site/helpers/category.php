<?php
/**
 * @version     1.0.0
 * @package     com_dztour
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      DZ Team <dev@dezign.vn> - dezign.vn
 */
// no direct access
defined('_JEXEC') or die;

class DZTourLocationsCategories extends JCategories
{
    public function __construct($options = array())
    {
        $options['table'] = '#__dztour_tours';
        $options['extension'] = 'com_dztour.tours.locationid';

        parent::__construct($options);
    }
}

class DZTourTypesCategories extends JCategories
{
    public function __construct($options = array())
    {
        $options['table'] = '#__dztour_tours';
        $options['extension'] = 'com_dztour.tours.typeid';

        parent::__construct($options);
    }
}