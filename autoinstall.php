<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.3                                                    |
// +---------------------------------------------------------------------------+
// | autoinstall.php                                                           |
// |                                                                           |
// | This file provides helper functions for the automatic plugin install.     |
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
function plugin_autoinstall_analytics($pi_name)
{
    $pi_name = 'analytics';
    $pi_display_name = 'analytics';
    $pi_admin = $pi_display_name . ' Admin';

    return array(
        'info' => array(
            'pi_name' => $pi_name,
            'pi_display_name' => $pi_display_name,
            'pi_version' => '1.1.3',
            'pi_gl_version' => '2.1.1',
            'pi_homepage' => 'https://github.com/Geeklog-Plugins/analytics'
        ),
        'groups' => array(
            $pi_admin => 'Has full access to ' . $pi_display_name . ' features'
        ),
        'features' => array(
            $pi_name . '.edit' => 'Access to ' . $pi_name . ' editor',
            'config.' . $pi_name . '.tab_main' => 'Access to configure general ' . $pi_name . ' settings'
        ),
        'mappings' => array(
            $pi_name . '.edit' => array($pi_admin),
            'config.' . $pi_name . '.tab_main' => array($pi_admin)
        ),
        'tables' => array()
    );
}

function plugin_load_configuration_analytics($pi_name)
{
    global $_CONF;

    require_once $_CONF['path'] . 'plugins/' . $pi_name . '/install_defaults.php';

    return plugin_initconfig_analytics();
}

function plugin_compatible_with_this_version_analytics($pi_name)
{
    if (version_compare(PHP_VERSION, '5.6.0', '<')) {
        return false;
    }

    if (!function_exists('SEC_createToken') || !class_exists('config') || !function_exists('COM_createHTMLDocument')) {
        return false;
    }

    return true;
}

?>
