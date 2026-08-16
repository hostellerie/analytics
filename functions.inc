<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.3                                                    |
// +---------------------------------------------------------------------------+
// | functions.inc                                                             |
// |                                                                           |
// | This file does two things: 1) it implements the necessary Geeklog Plugin  |
// | API method and 2) implements all the common code needed by the analytics  |
// | plugin' PHP files.                                                        |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2000-2016 by the following authors:                         |
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

$pi_name = 'analytics';
$_AN_CONF['version'] = '1.1.3';

$plugin_path = $_CONF['path'] . 'plugins/analytics/';
$langfile = $plugin_path . 'language/' . $_CONF['language'] . '.php';

if (file_exists($langfile)) {
    require_once $langfile;
} else {
    require_once $plugin_path . 'language/english.php';
}

/**
 * Return a trimmed scalar configuration value.
 */
function analytics_trimConfigValue($value)
{
    if (is_string($value) || is_numeric($value)) {
        return trim((string) $value);
    }

    return '';
}

/**
 * Normalize a GA4 Measurement ID. Returns an empty string when invalid.
 */
function analytics_normalizeMeasurementId($value)
{
    $value = strtoupper(analytics_trimConfigValue($value));

    if ($value === '' || !preg_match('/^G-[A-Z0-9]+$/', $value)) {
        return '';
    }

    return $value;
}

/**
 * Normalize a GA4 Property ID to the numeric identifier only.
 */
function analytics_normalizePropertyId($value)
{
    $value = analytics_trimConfigValue($value);

    if (stripos($value, 'properties/') === 0) {
        $value = substr($value, strlen('properties/'));
    }

    if ($value === '' || !ctype_digit($value)) {
        return '';
    }

    return $value;
}

/**
 * Normalize a Google OAuth web Client ID. Returns empty when invalid.
 */
function analytics_normalizeClientId($value)
{
    $value = analytics_trimConfigValue($value);

    if ($value === '' || !preg_match('/^[A-Za-z0-9._-]+\.apps\.googleusercontent\.com$/', $value)) {
        return '';
    }

    return $value;
}

/**
 * Normalize a hostname. URLs are accepted for convenience.
 */
function analytics_normalizeHostname($value)
{
    $value = analytics_trimConfigValue($value);
    if ($value === '') {
        return '';
    }

    if ($value === '*') {
        return '*';
    }

    if (strpos($value, '://') !== false) {
        $host = parse_url($value, PHP_URL_HOST);
    } else {
        $host = parse_url('//' . $value, PHP_URL_HOST);
    }

    if ($host === false || $host === null || $host === '') {
        return '';
    }

    $host = strtolower(rtrim(trim($host), '.'));

    if (!preg_match('/^[a-z0-9.-]+$/', $host)) {
        return '';
    }

    return $host;
}

/**
 * Read and normalize all runtime configuration in one place.
 */
function analytics_getConfig()
{
    global $_CONF;

    $raw = array();
    if (class_exists('config')) {
        $c = config::get_instance();
        $config = $c->get_config('analytics');
        if (is_array($config)) {
            $raw = $config;
        }
    }

    $gaCode = isset($raw['ga_code']) ? analytics_normalizeMeasurementId($raw['ga_code']) : '';
    $propertyId = isset($raw['property_id']) ? analytics_normalizePropertyId($raw['property_id']) : '';
    $clientId = isset($raw['client_id']) ? analytics_normalizeClientId($raw['client_id']) : '';
    $configuredHostname = isset($raw['hostname']) ? analytics_normalizeHostname($raw['hostname']) : '';

    if ($configuredHostname === '') {
        $siteUrl = isset($_CONF['site_url']) ? $_CONF['site_url'] : '';
        $configuredHostname = analytics_normalizeHostname($siteUrl);
    }

    $hostnameFilterEnabled = ($configuredHostname !== '' && $configuredHostname !== '*');
    $hostname = $hostnameFilterEnabled ? $configuredHostname : '';

    return array(
        'ga_code' => $gaCode,
        'property_id' => $propertyId,
        'client_id' => $clientId,
        'hostname' => $hostname,
        'hostname_filter_enabled' => $hostnameFilterEnabled
    );
}

/**
 * Encode a PHP value for safe insertion in JavaScript source.
 */
function analytics_jsonEncode($value)
{
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

function plugin_chkVersion_analytics()
{
    global $_AN_CONF;

    return $_AN_CONF['version'];
}

function plugin_autouninstall_analytics()
{
    return array(
        'tables' => array(),
        'groups' => array('analytics Admin'),
        'features' => array('analytics.edit'),
        // Keep the legacy block name here so old installations are cleaned up.
        'php_blocks' => array('phpblock_analytics'),
        'vars' => array()
    );
}

function plugin_getheadercode_analytics()
{
    $analyticsConfig = analytics_getConfig();
    $measurementId = $analyticsConfig['ga_code'];

    if ($measurementId === '') {
        return '';
    }

    $measurementIdHtml = htmlspecialchars($measurementId, ENT_QUOTES, 'UTF-8');
    $measurementIdJs = analytics_jsonEncode($measurementId);

    return LB . '<!-- Global site tag (gtag.js) - Google Analytics -->' . LB
        . '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $measurementIdHtml . '"></script>' . LB
        . '<script>' . LB
        . '  window.dataLayer = window.dataLayer || [];' . LB
        . '  function gtag(){dataLayer.push(arguments);}' . LB
        . '  gtag(\'js\', new Date());' . LB
        . '  gtag(\'config\', ' . $measurementIdJs . ');' . LB
        . '</script>' . LB;
}

function plugin_getadminoption_analytics()
{
    global $_CONF, $LANG_analytics01;

    if (SEC_hasRights('analytics.edit')) {
        return array($LANG_analytics01['plugin_name'], $_CONF['site_admin_url'] . '/plugins/analytics/index.php');
    }

    return false;
}

function plugin_cclabel_analytics()
{
    global $_CONF, $LANG_analytics01;

    if (SEC_hasRights('analytics.edit')) {
        return array(
            $LANG_analytics01['plugin_name'],
            $_CONF['site_admin_url'] . '/plugins/analytics/index.php',
            plugin_geticon_analytics()
        );
    }

    return false;
}

function plugin_geticon_analytics()
{
    global $_CONF;

    return $_CONF['site_admin_url'] . '/plugins/analytics/images/logo.png';
}

function plugin_upgrade_analytics()
{
    global $_TABLES, $_CONF, $_AN_CONF, $_DB_table_prefix;

    $currentVersion = DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name='analytics'");
    if ($currentVersion == $_AN_CONF['version']) {
        return true;
    }

    require_once $_CONF['path'] . 'plugins/analytics/install_updates.php';
    analytics_update_ConfValues_1_1_3();

    // Migrate legacy tracking code if the historical table still exists.
    $oldTable = $_DB_table_prefix . 'analytics';
    $tableExists = DB_query("SHOW TABLES LIKE '{$oldTable}'");
    if (DB_numRows($tableExists) > 0) {
        $code = analytics_trimConfigValue(DB_getItem($oldTable, 'code'));
        if ($code !== '' && class_exists('config')) {
            $c = config::get_instance();
            if ($c->group_exists('analytics')) {
                $c->set('ga_code', $code, 'analytics');
            }
        }
        DB_query("DROP TABLE {$oldTable}");
    }

    DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='{$_AN_CONF['version']}',pi_homepage='https://github.com/Geeklog-Plugins/analytics',pi_gl_version='2.1.1' WHERE pi_name='analytics' LIMIT 1");

    return true;
}

?>
