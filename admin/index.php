<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.1                                                    |
// +---------------------------------------------------------------------------+
// | admin/index.php                                                           |
// |                                                                           |
// | Geeklog analytics administration page                                     |
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

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

if (!SEC_hasRights('analytics.edit')) {
    $display = COM_siteHeader('menu', $MESSAGE[30]);
    $display .= COM_startBlock($MESSAGE[30], '', COM_getBlockTemplate('_msg_block', 'header'));
    $display .= $MESSAGE[36];
    $display .= COM_endBlock(COM_getBlockTemplate('_msg_block', 'footer'));
    $display .= COM_siteFooter();
    COM_accessLog("User {$_USER['username']} tried to illegally access the analytics administration screen.");
    echo $display;
    exit;
}

$display = '';
$client_id = '';
$property_id = '';
$ga_code = '';

if (class_exists('config')) {
    $c = config::get_instance();
    $analyticsConfig = $c->get_config('analytics');
    if (is_array($analyticsConfig)) {
        $client_id = isset($analyticsConfig['client_id']) ? $analyticsConfig['client_id'] : '';
        $property_id = isset($analyticsConfig['property_id']) ? $analyticsConfig['property_id'] : '';
        $ga_code = isset($analyticsConfig['ga_code']) ? $analyticsConfig['ga_code'] : '';
    }
}

$language_file = $_CONF['path'] . 'plugins/analytics/language/' . $_CONF['language'] . '.php';
if (file_exists($language_file)) {
    require_once $language_file;
} else {
    require_once $_CONF['path'] . 'plugins/analytics/language/english.php';
}

if (empty($ga_code)) {
    $content = '<h3>' . $LANG_analytics_admin['setup_required'] . '</h3>';
    $content .= '<p>' . $LANG_analytics_admin['setup_req_desc'] . '</p>';
} elseif (empty($client_id) || empty($property_id)) {
    $content = '<h3>' . $LANG_analytics_admin['tracking_active'] . '</h3>';
    $content .= '<p>' . sprintf($LANG_analytics_admin['tracking_active_desc'], htmlspecialchars($ga_code)) . '</p>';
    $content .= '<p>' . $LANG_analytics_admin['tracking_active_note'] . '</p>';
} else {
    $content = '
    <div style="background: #fdfdfd; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eaeaea;">
        <h2 style="margin-top: 0; color: #333; font-weight: 600;">' . $LANG_analytics_admin['dashboard_title'] . '</h2>
        <p id="dashboard-desc" style="color: #666; margin-bottom: 25px;">' . $LANG_analytics_admin['dashboard_desc'] . '</p>
        
        <div id="cache-info" style="display:none; margin-bottom: 20px; font-size: 0.9em; color: #888; background: #f0f4f8; padding: 10px 15px; border-radius: 6px; border-left: 4px solid #4285F4;">
            <p style="margin: 0;">' . $LANG_analytics_admin['cached_data_note'] . ' <strong><span id="cache-date-label"></span></strong></p>
        </div>

        <div id="dashboard-section" style="display:none;">
            
            <!-- KPI Blocks -->
            <div style="display: flex; gap: 20px; margin-bottom: 25px;">
                <div style="flex: 1; padding: 20px; background: #fff; border: 1px solid #eef2f5; border-radius: 8px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <h4 style="margin: 0 0 15px 0; color: #777; font-weight: 500; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">' . $LANG_analytics_admin['stats_yesterday'] . '</h4>
                    <div style="font-size: 28px; font-weight: 700; color: #4285F4; line-height: 1;" id="kpi-y-users">--</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px; margin-bottom: 12px;">' . $LANG_analytics_admin['metric_users'] . '</div>
                    <div style="font-size: 24px; font-weight: 600; color: #34A853; line-height: 1;" id="kpi-y-views">--</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px;">' . $LANG_analytics_admin['metric_views'] . '</div>
                </div>
                <div style="flex: 1; padding: 20px; background: #fff; border: 1px solid #eef2f5; border-radius: 8px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <h4 style="margin: 0 0 15px 0; color: #777; font-weight: 500; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">' . $LANG_analytics_admin['stats_7days'] . '</h4>
                    <div style="font-size: 28px; font-weight: 700; color: #4285F4; line-height: 1;" id="kpi-7-users">--</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px; margin-bottom: 12px;">' . $LANG_analytics_admin['metric_users'] . '</div>
                    <div style="font-size: 24px; font-weight: 600; color: #34A853; line-height: 1;" id="kpi-7-views">--</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px;">' . $LANG_analytics_admin['metric_views'] . '</div>
                </div>
                <div style="flex: 1; padding: 20px; background: #fff; border: 1px solid #eef2f5; border-radius: 8px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                    <h4 style="margin: 0 0 15px 0; color: #777; font-weight: 500; text-transform: uppercase; font-size: 11px; letter-spacing: 1px;">' . $LANG_analytics_admin['stats_30days'] . '</h4>
                    <div style="font-size: 28px; font-weight: 700; color: #4285F4; line-height: 1;" id="kpi-30-users">--</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px; margin-bottom: 12px;">' . $LANG_analytics_admin['metric_users'] . '</div>
                    <div style="font-size: 24px; font-weight: 600; color: #34A853; line-height: 1;" id="kpi-30-views">--</div>
                    <div style="font-size: 12px; color: #999; margin-top: 4px;">' . $LANG_analytics_admin['metric_views'] . '</div>
                </div>
            </div>

            <!-- Chart Container -->
            <div id="chart-container" style="padding: 20px; border: 1px solid #eef2f5; background: #fff; border-radius: 8px; min-height: 350px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                <canvas id="ga4Chart"></canvas>
                <p id="loading-msg" style="text-align:center; color:#666; margin-top: 150px;">' . addslashes($LANG_analytics_admin['data_wait']) . '</p>
            </div>
        </div>
        
        <div id="auth-section" style="margin-top: 25px; text-align: center;">
            <button id="authorize-btn" style="padding: 12px 24px; background: #4285F4; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; box-shadow: 0 2px 4px rgba(66, 133, 244, 0.3); transition: background 0.2s;">
                ' . $LANG_analytics_admin['auth_button'] . '
            </button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <script>
        const CLIENT_ID = "' . addslashes($client_id) . '";
        const PROPERTY_ID = "' . addslashes($property_id) . '";
        const SCOPES = "https://www.googleapis.com/auth/analytics.readonly";

        let tokenClient;
        let gaChart = null;

        function renderDashboard(chartData) {
            document.getElementById("dashboard-section").style.display = "block";
            document.getElementById("loading-msg").style.display = "none";
            
            // Populate KPIs
            document.getElementById("kpi-y-users").innerText = chartData.kpi.yesterday.users;
            document.getElementById("kpi-y-views").innerText = chartData.kpi.yesterday.views;
            document.getElementById("kpi-7-users").innerText = chartData.kpi.last7.users;
            document.getElementById("kpi-7-views").innerText = chartData.kpi.last7.views;
            document.getElementById("kpi-30-users").innerText = chartData.kpi.last30.users;
            document.getElementById("kpi-30-views").innerText = chartData.kpi.last30.views;

            // Render Chart
            const ctx = document.getElementById("ga4Chart").getContext("2d");
            
            if (gaChart) {
                gaChart.destroy();
            }
            
            gaChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: "' . addslashes($LANG_analytics_admin['metric_users']) . '",
                            data: chartData.users,
                            borderColor: "#4285F4",
                            backgroundColor: "rgba(66, 133, 244, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: "' . addslashes($LANG_analytics_admin['metric_views']) . '",
                            data: chartData.views,
                            borderColor: "#34A853",
                            backgroundColor: "transparent",
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: "index",
                        intersect: false,
                    },
                    plugins: {
                        legend: { position: "top" }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        function checkCacheAndInit() {
            const cachedData = localStorage.getItem("ga4_dashboard_data");
            const cacheTime = localStorage.getItem("ga4_dashboard_time");
            
            if (cachedData && cacheTime) {
                try {
                    const parsedData = JSON.parse(cachedData);
                    const cacheDate = new Date(parseInt(cacheTime));
                    
                    // Display cached data immediately
                    renderDashboard(parsedData);
                    
                    // Show cache info and hide description
                    document.getElementById("cache-info").style.display = "block";
                    const descEl = document.getElementById("dashboard-desc");
                    if (descEl) descEl.style.display = "none";
                    
                    const dateStr = cacheDate.toLocaleString();
                    const cacheMsg = "' . addslashes($LANG_analytics_admin['cached_date']) . '";
                    document.getElementById("cache-date-label").innerText = cacheMsg.replace("%s", dateStr);
                    
                    const btn = document.getElementById("authorize-btn");
                    btn.innerText = "' . addslashes($LANG_analytics_admin['refresh_button']) . '";
                    btn.style.background = "#fff";
                    btn.style.color = "#4285F4";
                    btn.style.border = "1px solid #4285F4";
                    
                } catch(e) {
                    // Invalid cache
                    localStorage.removeItem("ga4_dashboard_data");
                }
            }
            
            // Initialize GSI
            tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: CLIENT_ID,
                scope: SCOPES,
                callback: (tokenResponse) => {
                    if (tokenResponse && tokenResponse.access_token) {
                        document.getElementById("auth-section").style.display = "none";
                        document.getElementById("cache-info").style.display = "none";
                        document.getElementById("dashboard-section").style.display = "block";
                        document.getElementById("loading-msg").innerText = "' . addslashes($LANG_analytics_admin['loading_data']) . '";
                        document.getElementById("loading-msg").style.display = "block";
                        
                        // Hide chart canvas while loading new data
                        if(gaChart) gaChart.destroy();
                        
                        fetchAnalyticsData(tokenResponse.access_token);
                    }
                },
            });
            
            document.getElementById("authorize-btn").addEventListener("click", () => {
                tokenClient.requestAccessToken();
            });
        }
        
        window.onload = function() {
            // Wait for Chart.js and GSI to be available
            var depsCheck = setInterval(function() {
                if (typeof google !== "undefined" && google.accounts && typeof Chart !== "undefined") {
                    clearInterval(depsCheck);
                    checkCacheAndInit();
                }
            }, 100);
        };

        function fetchAnalyticsData(token) {
            let formattedProperty = PROPERTY_ID.startsWith("properties/") ? PROPERTY_ID : "properties/" + PROPERTY_ID;

            fetch("https://analyticsdata.googleapis.com/v1beta/" + formattedProperty + ":runReport", {
                method: "POST",
                headers: {
                    "Authorization": "Bearer " + token,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    "dateRanges": [{ "startDate": "30daysAgo", "endDate": "today" }],
                    "dimensions": [{ "name": "date" }],
                    "metrics": [{ "name": "activeUsers" }, { "name": "screenPageViews" }],
                    "orderBys": [{ "dimension": { "dimensionName": "date" } }]
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("loading-msg").innerHTML = "<span style=\"color:red;\">' . addslashes($LANG_analytics_admin['error']) . ' " + data.error.message + "</span>";
                    return;
                }
                
                let processedData = {
                    labels: [],
                    users: [],
                    views: [],
                    kpi: {
                        yesterday: { users: 0, views: 0 },
                        last7: { users: 0, views: 0 },
                        last30: { users: 0, views: 0 }
                    }
                };
                
                if (data.rows && data.rows.length > 0) {
                    // Sort rows by date just in case API returns out of order
                    data.rows.sort((a,b) => a.dimensionValues[0].value.localeCompare(b.dimensionValues[0].value));
                    
                    const totalDays = data.rows.length;
                    
                    data.rows.forEach((row, index) => {
                        // Parse YYYYMMDD
                        let dateStr = row.dimensionValues[0].value;
                        let formattedDate = dateStr.substring(6,8) + "/" + dateStr.substring(4,6);
                        
                        let u = parseInt(row.metricValues[0].value) || 0;
                        let v = parseInt(row.metricValues[1].value) || 0;
                        
                        processedData.labels.push(formattedDate);
                        processedData.users.push(u);
                        processedData.views.push(v);
                        
                        // KPIs
                        processedData.kpi.last30.users += u;
                        processedData.kpi.last30.views += v;
                        
                        if (index >= totalDays - 7) {
                            processedData.kpi.last7.users += u;
                            processedData.kpi.last7.views += v;
                        }
                        
                        // Yesterday (assuming last row is today, yesterday is totalDays - 2)
                        // Note: Depending on timezone, "today" might have very little data. GA4 API standardizes this.
                        if (index === totalDays - 2) {
                            processedData.kpi.yesterday.users += u;
                            processedData.kpi.yesterday.views += v;
                        }
                    });
                }
                
                // Cache data
                localStorage.setItem("ga4_dashboard_data", JSON.stringify(processedData));
                localStorage.setItem("ga4_dashboard_time", Date.now().toString());
                
                renderDashboard(processedData);
            })
            .catch(error => {
                document.getElementById("loading-msg").innerHTML = "<span style=\"color:red;\">' . addslashes($LANG_analytics_admin['request_failed']) . ' " + error + "</span>";
            });
        }
    </script>
    ';
}

$content .= $LANG_analytics_admin['manual_html'];

$display .= COM_createHTMLDocument($content, array('pagetitle' => $LANG_analytics_admin['dashboard_title']));
echo $display;

?>
