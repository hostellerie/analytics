<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.2                                                    |
// +---------------------------------------------------------------------------+
// | install_updates.php                                                       |
// |                                                                           |
// | Configuration updates for the analytics plugin                            |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2026 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs, Trinity Bays, Geeklog Community                      |
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

if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== false) {
    die('This file can not be used on its own!');
}

/**
 * Update Configuration Values for analytics 1.1.1
 * 
 * In this version, we introduced the native Geeklog Configuration API.
 * This function initializes the structure during an upgrade from a version
 * that did not have it.
 *
 * @return boolean true on success
 */
function analytics_update_ConfValues_1_1_1()
{
    global $_CONF, $_PI_CONF;

    $c = config::get_instance();

    require_once $_CONF['path'] . 'plugins/analytics/install_defaults.php';
    
    // Group 'analytics'
    if (!$c->group_exists('analytics')) {
        $c->add('sg_main', NULL, 'subgroup', 0, 0, NULL, 0, true, 'analytics', 0);
        $c->add('tab_main', NULL, 'tab', 0, 0, NULL, 0, true, 'analytics', 0);
        $c->add('fs_main', NULL, 'fieldset', 0, 0, NULL, 0, true, 'analytics', 0);
        
        // Define default values if $_PI_CONF is not populated yet
        $ga_code = isset($_PI_CONF['analytics']['ga_code']) ? $_PI_CONF['analytics']['ga_code'] : '';
        $client_id = isset($_PI_CONF['analytics']['client_id']) ? $_PI_CONF['analytics']['client_id'] : '';
        
        $c->add('ga_code', $ga_code, 'text', 0, 0, NULL, 10, true, 'analytics', 0);
        $c->add('client_id', $client_id, 'text', 0, 0, NULL, 20, true, 'analytics', 0);
    }

    return true;
}

/**
 * Update Configuration Values for analytics 1.1.2
 *
 * @return boolean true on success
 */
function analytics_update_ConfValues_1_1_2()
{
    global $_CONF;
    $c = config::get_instance();

    // Ensure previous updates are applied
    analytics_update_ConfValues_1_1_1();

    if ($c->group_exists('analytics')) {
        $analyticsConfig = $c->get_config('analytics');
        if (is_array($analyticsConfig) && !isset($analyticsConfig['property_id'])) {
            $c->add('property_id', '', 'text', 0, 0, NULL, 15, true, 'analytics', 0);
        }
    }

    return true;
}

?>
