<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.2                                                    |
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


// Language for plugin install $LANG_analytics00
$LANG_analytics00 = array(
    'install_header'    => 'analytics Plugin Installation',
    'overview'          => 'The analytics Plugin add the google analytics code in your header template.',
	'preinstall_check'  => 'analytics has the following requirements:',
	'geeklog_check'     => 'Geeklog v1.5.0 or greater, version reported is <b>%s</b>.',
    'php_check'         => 'PHP v4.3.0 or greater, version reported is <b>%s</b>.',
    'preinstall_confirm' => "For full details on analytics Plugin, please visit <a href=\"https://github.com/Geeklog-Plugins/analytics\" target=\"_blank\">GitHub</a>.",
);

// Language for plugin users
$LANG_analytics01 = array(
    'plugin_name'             => 'Analytics',
);

// Localization of the Admin Configuration UI
$LANG_configsections['analytics'] = array(
    'label' => 'Analytics',
    'title' => 'Analytics Configuration'
);

$LANG_confignames['analytics'] = array(
    'ga_code'     => 'GA4 Measurement ID (e.g., G-XXXX)',
    'property_id' => 'GA4 Property ID (numeric)',
    'client_id'   => 'Google OAuth Client ID'
);

$LANG_configsubgroups['analytics'] = array(
    'sg_main' => 'Main Settings'
);

$LANG_tab['analytics'] = array(
    'tab_main'  => 'Analytics Settings'
);

$LANG_fs['analytics'] = array(
    'fs_main'   => 'Google Analytics 4'
);

$LANG_analytics_admin = array(
    'setup_required' => 'Google Analytics - Setup Required',
    'setup_req_desc' => 'Please configure your <b>GA4 Measurement ID (e.g., G-XXXX)</b> in the Geeklog Configuration to enable website tracking.',
    'tracking_active' => 'Google Analytics - Tracking Active',
    'tracking_active_desc' => 'Your website is currently being tracked with Measurement ID: <b>%s</b>',
    'tracking_active_note' => '<i>Note: To view your traffic statistics directly in this dashboard, please configure your <b>GA4 Property ID (numeric)</b> and <b>Google OAuth Client ID</b> in the Geeklog Configuration.</i>',
    'dashboard_title' => 'Google Analytics 4 Dashboard',
    'dashboard_desc' => 'To view your site statistics, you must authorize access with your Google account.',
    'auth_button' => 'Authorize Google Analytics Access',
    'refresh_button' => 'Refresh Data (Google Auth)',
    'traffic_overview' => 'Traffic Overview',
    'data_wait' => 'Data will appear here after authorization...',
    'loading_data' => 'Loading data from GA4 API...',
    'stats_yesterday' => 'Yesterday',
    'stats_7days' => 'Last 7 Days',
    'stats_30days' => 'Last 30 Days',
    'metric_users' => 'Active Users',
    'metric_views' => 'Page Views',
    'cached_data_note' => 'Data is currently loaded from your local browser cache.',
    'cached_date' => 'Last updated: %s',
    'last_30_days' => 'Last 30 Days',
    'active_users' => 'Active Users:',
    'page_views' => 'Page Views:',
    'demo_note' => 'Note: This is a basic demo of the GA4 Data API. A full dashboard would include charts.',
    'error' => 'Error:',
    'request_failed' => 'Request failed:',
    'manual_html' => '
<hr>
<details style="margin-top: 40px; padding: 20px; background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <summary style="font-size: 1.3em; font-weight: 600; cursor: pointer; color: #4285F4; outline: none; padding: 5px 0;">Viewing Google Analytics Data in the Plugin (Click to expand)</summary>
    <div style="margin-top: 20px; color: #444; line-height: 1.6;">
    <p>To view Google Analytics statistics directly from the Analytics plugin administration page, you must configure both GA4 tracking and API access.</p>

    <h3 style="color: #222; margin-top: 25px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">1. Configure the GA4 Measurement ID</h3>
    <p>Enter your GA4 Measurement ID in the plugin configuration.</p>
    <p>Example:<br><code style="background: #eee; padding: 2px 6px; border-radius: 4px;">G-XXXXXXXXXX</code></p>
    <p>You can find it in <a href="https://analytics.google.com/" target="_blank" style="color: #4285F4; text-decoration: none;">Google Analytics</a> under:<br>
    <strong>Admin &rarr; Data Streams &rarr; Web &rarr; Measurement ID</strong></p>
    <p>This ID is used to track visits to your website.</p>

    <h3 style="color: #222; margin-top: 25px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">2. Configure the GA4 Property ID</h3>
    <p>Enter the numeric Google Analytics Property ID.</p>
    <p>Example:<br><code style="background: #eee; padding: 2px 6px; border-radius: 4px;">123456789</code></p>
    <p>Do not use the Measurement ID (<code style="background: #eee; padding: 2px 6px; border-radius: 4px;">G-XXXXXXXXXX</code>) in this field.</p>
    <p>You can find the Property ID in <a href="https://analytics.google.com/" target="_blank" style="color: #4285F4; text-decoration: none;">Google Analytics</a> under:<br>
    <strong>Admin &rarr; Property Settings</strong></p>

    <h3 style="color: #222; margin-top: 25px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">3. Configure a Google OAuth Client ID</h3>
    <p>The plugin uses Google OAuth 2.0 to securely access your Analytics data.</p>
    <p>Create or use an OAuth 2.0 Client ID in <a href="https://console.cloud.google.com/" target="_blank" style="color: #4285F4; text-decoration: none;">Google Cloud</a> and enter it in the plugin configuration.</p>
    <p>The Google Analytics Data API must also be enabled for the associated Google Cloud project.</p>

    <h3 style="color: #222; margin-top: 25px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">4. Make Sure Your Google Account Has Access</h3>
    <p>The Google account used to authorize the plugin must have access to the corresponding GA4 property.</p>
    <p>In Google Analytics, go to:<br>
    <strong>Admin &rarr; Property Access Management</strong></p>
    <p>Make sure your Google account has at least the <strong>Viewer</strong> role.</p>

    <h3 style="color: #222; margin-top: 25px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">5. Authorize Access</h3>
    <p>Open the Analytics plugin administration page and click:<br>
    <strong>Authorize Google Analytics Access</strong> (or Refresh Data)</p>
    <p>Select the Google account that has access to the GA4 property.</p>
    <p>After authorization, the plugin can retrieve your Analytics statistics using the Google Analytics Data API.</p>

    <div style="margin-top: 30px; padding: 15px; border-left: 4px solid #EA4335; background: #fdf5f5; border-radius: 0 4px 4px 0;">
        <h3 style="color: #EA4335; margin-top: 0;">Important</h3>
        <p>The <strong>Measurement ID</strong> and <strong>Property ID</strong> must belong to the same GA4 property.</p>
        <p>If you see:<br>
        <code style="background: #fff; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 5px; margin-bottom: 5px;">User does not have sufficient permissions for this property</code><br>
        check that you authorized the plugin with the correct Google account and that this account has access to the configured GA4 property.</p>
    </div>
    </div>
</details>
'
);
?>
