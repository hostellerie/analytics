<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.2                                                    |
// +---------------------------------------------------------------------------+
// | install_defaults.php                                                      |
// |                                                                           |
// | Initial Installation Defaults used when loading the online configuration  |
// | records. These settings are only used during the initial installation     |
// | and not referenced any more once the plugin is installed.                 |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008 by the following authors:                              |
// |                                                                           |
// | Authors: Dirk Haun        - dirk AT haun-online DOT de                    |
// |          Ben              - hostellerie.org AT gmail DOT com              |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+
//

if (strpos(strtolower($_SERVER['PHP_SELF']), 'install_defaults.php') !== false) {
    die('This file can not be used on its own!');
}

/*
 * Analytics default settings
 */
global $_CONF, $_PI_CONF;

$_PI_CONF['analytics'] = array(
    'ga_code'     => '',
    'property_id' => '',
    'client_id'   => ''
);

/**
 * Initialize Geeklog configuration
 */
function plugin_initconfig_analytics()
{
    global $_CONF, $_PI_CONF;

    if (!class_exists('config')) {
        return false;
    }
    $c = config::get_instance();

    // Group 'analytics'
    if (!$c->group_exists('analytics')) {
        $c->add('sg_main', NULL, 'subgroup', 0, 0, NULL, 0, true, 'analytics', 0);
        $c->add('tab_main', NULL, 'tab', 0, 0, NULL, 0, true, 'analytics', 0);
        $c->add('fs_main', NULL, 'fieldset', 0, 0, NULL, 0, true, 'analytics', 0);
        
        $ga_code = isset($_PI_CONF['analytics']['ga_code']) ? $_PI_CONF['analytics']['ga_code'] : '';
        $property_id = isset($_PI_CONF['analytics']['property_id']) ? $_PI_CONF['analytics']['property_id'] : '';
        $client_id = isset($_PI_CONF['analytics']['client_id']) ? $_PI_CONF['analytics']['client_id'] : '';

        $c->add('ga_code', $ga_code, 'text', 0, 0, NULL, 10, true, 'analytics', 0);
        $c->add('property_id', $property_id, 'text', 0, 0, NULL, 15, true, 'analytics', 0);
        $c->add('client_id', $client_id, 'text', 0, 0, NULL, 20, true, 'analytics', 0);
        return true;
    }
    return false;
}
?>