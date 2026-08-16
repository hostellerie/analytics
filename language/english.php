<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.3                                                    |
// +---------------------------------------------------------------------------+
// | language/english.php                                                      |
// |                                                                           |
// | English language file for the Geeklog analytics plugin                    |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2001-2026 by the following authors:                         |
// |                                                                           |
// | Authors: Tony Bibbs       - tony AT tonybibbs DOT com                     |
// |          Trinity Bays     - trinity93 AT gmail DOT com                    |
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
$LANG_analytics00 = array(
    'install_header' => 'analytics Plugin Installation',
    'overview' => 'The analytics Plugin adds Google Analytics 4 tracking and an administration dashboard.',
    'preinstall_check' => 'analytics has the following requirements:',
    'geeklog_check' => 'Geeklog v2.1.1 or greater, version reported is <b>%s</b>.',
    'php_check' => 'PHP v5.6.0 or greater, version reported is <b>%s</b>.',
    'preinstall_confirm' => 'For full details on analytics Plugin, please visit <a href="https://github.com/Geeklog-Plugins/analytics" target="_blank">GitHub</a>.'
);

$LANG_analytics01 = array(
    'plugin_name' => 'Analytics'
);

$LANG_configsections['analytics'] = array(
    'label' => 'Analytics',
    'title' => 'Analytics Configuration'
);

$LANG_confignames['analytics'] = array(
    'ga_code' => 'GA4 Measurement ID (e.g., G-XXXX)',
    'property_id' => 'GA4 Property ID (numeric)',
    'client_id' => 'Google OAuth Client ID',
    'hostname' => 'Hostname filter (blank = auto, * = all hosts)'
);

$LANG_configsubgroups['analytics'] = array('sg_main' => 'Main Settings');
$LANG_tab['analytics'] = array('tab_main' => 'Analytics Settings');
$LANG_fs['analytics'] = array('fs_main' => 'Google Analytics 4');

$LANG_analytics_admin = array(
    'setup_required' => 'Google Analytics - Setup Required',
    'setup_req_desc' => 'Please configure a valid <b>GA4 Measurement ID (e.g., G-XXXX)</b> in the Geeklog Configuration to enable website tracking.',
    'tracking_active' => 'Google Analytics - Tracking Active',
    'tracking_active_desc' => 'Your website is currently being tracked with Measurement ID: <b>%s</b>',
    'tracking_active_note' => '<i>To view traffic statistics in this dashboard, configure a valid <b>GA4 Property ID</b> and <b>Google OAuth Client ID</b>.</i>',
    'dashboard_title' => 'Google Analytics 4 Dashboard',
    'dashboard_desc' => 'Authorize access with your Google account to retrieve fresh site statistics.',
    'auth_button' => 'Authorize Google Analytics Access',
    'refresh_button' => 'Refresh Data (Google Auth)',
    'data_wait' => 'Data will appear here after authorization...',
    'loading_data' => 'Loading data from GA4 API...',
    'stats_yesterday' => 'Yesterday',
    'stats_7days' => 'Last 7 Days',
    'stats_30days' => 'Last 30 Days',
    'metric_users' => 'Active Users',
    'metric_views' => 'Page Views',
    'cached_data_note' => 'Data is currently loaded from your local browser cache.',
    'cached_date' => 'Last updated: %s',
    'error' => 'Error:',
    'request_failed' => 'Request failed',
    'dependency_error' => 'Google Identity Services or Chart.js could not be loaded.',
    'permission_error' => 'Access denied. Check that this Google account has access to the configured GA4 property and that the Analytics Data API is enabled.',
    'auth_error' => 'Authorization expired or was rejected. Please authorize Google Analytics access again.',
    'quota_error' => 'The Google Analytics Data API quota has been reached. Please try again later.',
    'hostname_filter_active' => 'Statistics are filtered for hostname: <strong>%s</strong>.',
    'hostname_filter_disabled' => 'Hostname filtering is disabled. Statistics cover the complete GA4 property.',
    'manual_html' => '
<hr>
<details style="margin-top:30px;padding:18px;background:#f9f9f9;border:1px solid #e0e0e0;border-radius:8px;">
<summary style="font-size:1.15em;font-weight:600;cursor:pointer;">Google Analytics dashboard setup</summary>
<div style="margin-top:18px;line-height:1.6;">
<p>Configure the GA4 Measurement ID, numeric Property ID and Google OAuth Client ID in the Analytics configuration.</p>
<p><strong>Hostname filter:</strong> leave this field blank to automatically use the hostname from Geeklog <code>site_url</code>. Enter a hostname to force a specific site, or enter <code>*</code> to display statistics for the complete GA4 property.</p>
<p>The Google account used for authorization needs at least Viewer access to the GA4 property, and the Google Analytics Data API must be enabled in the associated Google Cloud project.</p>
<p>The dashboard uses completed days only: yesterday, the previous 7 completed days, and the previous 30 completed days. Active user KPIs are requested directly for each complete period and are not calculated by adding daily users.</p>
</div>
</details>'
);

?>
