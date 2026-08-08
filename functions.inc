<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.1                                                    |
// +---------------------------------------------------------------------------+
// | functions.inc                                                             |
// |                                                                           |
// | This file does two things: 1) it implements the necessary Geeklog Plugin  |
// | API method and 2) implements all the common code needed by the analytics     |
// | plugin' PHP files.                                                        |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000-2008 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs       - tony AT tonybibbs DOT com                     |
// |          Tom Willett      - twillett AT users DOT sourceforge DOT net     |
// |          Blaine Lang      - langmail AT sympatico DOT ca                  |
// |          Dirk Haun        - dirk AT haun-online DOT de                    |
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


if (strpos(strtolower($_SERVER['PHP_SELF']), 'functions.inc') !== false) {
    die('This file can not be used on its own.');
}

$pi_name    = 'analytics';
$_AN_CONF['version'] = '1.1.1';

// database table names 
$_TABLES['analytics']               = $_DB_table_prefix . 'analytics';
	
// path to the plugin
$plugin_path = $_CONF['path'] . 'plugins/analytics/';

// Set lang file or english by default
$langfile = $plugin_path . 'language/' . $_CONF['language'] . '.php';

if (file_exists($langfile)) {
    require_once $langfile;
} else {
    require_once $plugin_path . 'language/english.php';
}

// Class removed.

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+




// Return the current version of code.
// Used in the Plugin Editor to show the registered version and code version

function plugin_chkVersion_analytics()
{
    global $_AN_CONF;
    return $_AN_CONF['version'];
}


// Automatic uninstall function for plugins
// @return   array
// This code is automatically uninstalling the plugin.
// It passes an array to the core code function that removes
// tables, groups, features and php blocks from the tables.
// Additionally, this code can perform special actions that cannot be
// foreseen by the core code (interactions with other plugins for example)

function plugin_autouninstall_analytics ()
{
    $out = array (
        /* give the name of the tables, without $_TABLES[] */
        'tables' => array(),
        /* give the full name of the group, as in the db */
        'groups' => array('analytics Admin'),
        /* give the full name of the feature, as in the db */
        'features' => array('analytics.edit'),
        /* give the full name of the block, including 'phpblock_', etc */
        'php_blocks' => array('phpblock_analytics'),
        /* give all vars with their name */
        'vars'=> array()
    );

    return $out;
}


function plugin_getheadercode_analytics()
{
    $current_code = '';
    if (class_exists('config')) {
        $c = config::get_instance();
        $analyticsConfig = $c->get_config('analytics');
        if (is_array($analyticsConfig) && isset($analyticsConfig['ga_code'])) {
            $current_code = $analyticsConfig['ga_code'];
        }
    }
    
    if (empty($current_code)) {
        return '';
    }
    
    /* 
    $str = LB . '<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("' . $current_code . '");
pageTracker._trackPageview();
} catch(err) {}</script>'. LB;
    */
    
    $str = LB . "<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . $current_code . "\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '" . $current_code . "');
</script>
" . LB;

    return $str;
}

function plugin_getadminoption_analytics() {
    global $_CONF, $LANG_analytics01;

    if (SEC_hasRights('analytics.edit')) {
        return array($LANG_analytics01['plugin_name'], $_CONF['site_admin_url'] . '/plugins/analytics/index.php');
    }
}

function plugin_cclabel_analytics()
{
    global $_CONF, $LANG_analytics01;

    if (SEC_hasRights ('analytics.edit')) {
        return array ($LANG_analytics01['plugin_name'],
                $_CONF['site_admin_url'] . '/plugins/analytics/index.php',
                plugin_geticon_analytics ());
    }

    return false;
}

// Returns the URL of the plugin's icon
// @return   string      URL of the icon

function plugin_geticon_analytics ()
{
    global $_CONF;
    $icon = $_CONF['site_admin_url'] . '/plugins/analytics/images/logo.png';
    return $icon;
}

// Get path for the template files.
//* @param    string  $path   subdirectory within the base template path
// @return   string          full path to template directory

function analytics_templatePath ($path = '')
{
    global $_CONF;

    if (empty ($path)) {
        $layout_path = $_CONF['path_layout'] . $pi_name;
    } else {
        $layout_path = $_CONF['path_layout'] . $pi_name .  '/' . $path;
    }

    if (is_dir ($layout_path)) {
        $retval = $layout_path;
    } else {
        $retval = $_CONF['path'] . 'plugins/' . $pi_name . '/templates';
        if (!empty ($path)) {
            $retval .= '/' . $path;
        }
    }

    return $retval;
}

// Update the analytics plugin

// @return   int     Number of message to display (true = generic success msg)

function plugin_upgrade_analytics()
{
    global $_TABLES, $_CONF, $_AN_CONF, $_DB_table_prefix;

    $currentVersion = DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='analytics'");

    if ( DB_getItem($_TABLES['plugins'],'pi_version',"pi_name='analytics'") == $_AN_CONF['version']) {
        return true;
    }

    $old_table = $_DB_table_prefix . 'analytics';
    
    // Initialize configuration for the new version
    require_once $_CONF['path'] . 'plugins/analytics/install_updates.php';
    analytics_update_ConfValues_1_1_1();
    
    // Migrate old config to new config system if the table exists
    $table_exists = DB_query("SHOW TABLES LIKE '{$old_table}'");
    if (DB_numRows($table_exists) > 0) {
        $code = DB_getItem($old_table, 'code');
        if (!empty($code) && class_exists('config')) {
            $c = config::get_instance();
            if ($c->group_exists('analytics')) {
                $c->set('ga_code', $code, 'analytics');
            }
        }
        // Drop the old table
        DB_query("DROP TABLE {$old_table}");
    }

    DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='{$_AN_CONF['version']}',pi_homepage='http://www.geeklog.fr',pi_gl_version='2.2.2' WHERE pi_name='analytics' LIMIT 1");
    return true;
}

?>
