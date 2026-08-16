<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | analytics plugin 1.1.3                                                    |
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
    $content = COM_startBlock($MESSAGE[30], '', COM_getBlockTemplate('_msg_block', 'header'));
    $content .= $MESSAGE[36];
    $content .= COM_endBlock(COM_getBlockTemplate('_msg_block', 'footer'));
    COM_accessLog("User {$_USER['username']} tried to illegally access the analytics administration screen.");
    echo COM_createHTMLDocument($content, array('pagetitle' => $MESSAGE[30]));
    exit;
}

$languageFile = $_CONF['path'] . 'plugins/analytics/language/' . $_CONF['language'] . '.php';
if (file_exists($languageFile)) {
    require_once $languageFile;
} else {
    require_once $_CONF['path'] . 'plugins/analytics/language/english.php';
}

$analyticsConfig = analytics_getConfig();
$gaCode = $analyticsConfig['ga_code'];
$propertyId = $analyticsConfig['property_id'];
$clientId = $analyticsConfig['client_id'];
$hostname = $analyticsConfig['hostname'];
$hostnameFilterEnabled = $analyticsConfig['hostname_filter_enabled'];

$content = '';

if ($gaCode === '') {
    $content .= '<h3>' . $LANG_analytics_admin['setup_required'] . '</h3>';
    $content .= '<p>' . $LANG_analytics_admin['setup_req_desc'] . '</p>';
} elseif ($clientId === '' || $propertyId === '') {
    $content .= '<h3>' . $LANG_analytics_admin['tracking_active'] . '</h3>';
    $content .= '<p>' . sprintf($LANG_analytics_admin['tracking_active_desc'], htmlspecialchars($gaCode, ENT_QUOTES, 'UTF-8')) . '</p>';
    $content .= '<p>' . $LANG_analytics_admin['tracking_active_note'] . '</p>';
} else {
    $filterDescription = $hostnameFilterEnabled
        ? sprintf($LANG_analytics_admin['hostname_filter_active'], htmlspecialchars($hostname, ENT_QUOTES, 'UTF-8'))
        : $LANG_analytics_admin['hostname_filter_disabled'];

    $jsClientId = analytics_jsonEncode($clientId);
    $jsPropertyId = analytics_jsonEncode($propertyId);
    $jsHostname = analytics_jsonEncode($hostname);
    $jsFilterEnabled = $hostnameFilterEnabled ? 'true' : 'false';

    $jsStrings = array(
        'users' => $LANG_analytics_admin['metric_users'],
        'views' => $LANG_analytics_admin['metric_views'],
        'loading' => $LANG_analytics_admin['loading_data'],
        'error' => $LANG_analytics_admin['error'],
        'requestFailed' => $LANG_analytics_admin['request_failed'],
        'cachedDate' => $LANG_analytics_admin['cached_date'],
        'refresh' => $LANG_analytics_admin['refresh_button'],
        'dependencyError' => $LANG_analytics_admin['dependency_error'],
        'permissionError' => $LANG_analytics_admin['permission_error'],
        'authError' => $LANG_analytics_admin['auth_error'],
        'quotaError' => $LANG_analytics_admin['quota_error']
    );

    $content .= '
    <div style="background:#fdfdfd;padding:25px;border-radius:8px;border:1px solid #eaeaea;">
        <h2 style="margin-top:0;">' . $LANG_analytics_admin['dashboard_title'] . '</h2>
        <p id="dashboard-desc">' . $LANG_analytics_admin['dashboard_desc'] . '</p>
        <p style="font-size:0.9em;color:#666;">' . $filterDescription . '</p>

        <div id="cache-info" style="display:none;margin-bottom:20px;font-size:0.9em;background:#f0f4f8;padding:10px 15px;border-radius:6px;">
            <span>' . $LANG_analytics_admin['cached_data_note'] . ' <strong><span id="cache-date-label"></span></strong></span>
        </div>

        <div id="dashboard-section" style="display:none;">
            <div style="display:flex;gap:20px;margin-bottom:25px;flex-wrap:wrap;">
                <div style="flex:1;min-width:180px;padding:20px;background:#fff;border:1px solid #eef2f5;border-radius:8px;text-align:center;">
                    <h4>' . $LANG_analytics_admin['stats_yesterday'] . '</h4>
                    <div style="font-size:28px;font-weight:700;" id="kpi-y-users">--</div>
                    <div style="font-size:12px;">' . $LANG_analytics_admin['metric_users'] . '</div>
                    <div style="font-size:24px;font-weight:600;margin-top:12px;" id="kpi-y-views">--</div>
                    <div style="font-size:12px;">' . $LANG_analytics_admin['metric_views'] . '</div>
                </div>
                <div style="flex:1;min-width:180px;padding:20px;background:#fff;border:1px solid #eef2f5;border-radius:8px;text-align:center;">
                    <h4>' . $LANG_analytics_admin['stats_7days'] . '</h4>
                    <div style="font-size:28px;font-weight:700;" id="kpi-7-users">--</div>
                    <div style="font-size:12px;">' . $LANG_analytics_admin['metric_users'] . '</div>
                    <div style="font-size:24px;font-weight:600;margin-top:12px;" id="kpi-7-views">--</div>
                    <div style="font-size:12px;">' . $LANG_analytics_admin['metric_views'] . '</div>
                </div>
                <div style="flex:1;min-width:180px;padding:20px;background:#fff;border:1px solid #eef2f5;border-radius:8px;text-align:center;">
                    <h4>' . $LANG_analytics_admin['stats_30days'] . '</h4>
                    <div style="font-size:28px;font-weight:700;" id="kpi-30-users">--</div>
                    <div style="font-size:12px;">' . $LANG_analytics_admin['metric_users'] . '</div>
                    <div style="font-size:24px;font-weight:600;margin-top:12px;" id="kpi-30-views">--</div>
                    <div style="font-size:12px;">' . $LANG_analytics_admin['metric_views'] . '</div>
                </div>
            </div>

            <div id="chart-container" style="padding:20px;border:1px solid #eef2f5;background:#fff;border-radius:8px;min-height:350px;">
                <canvas id="ga4Chart"></canvas>
                <p id="loading-msg" style="text-align:center;margin-top:150px;">' . $LANG_analytics_admin['data_wait'] . '</p>
            </div>
        </div>

        <div id="auth-section" style="margin-top:25px;text-align:center;">
            <button id="authorize-btn" type="button" style="padding:12px 24px;cursor:pointer;">' . $LANG_analytics_admin['auth_button'] . '</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js" defer></script>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <script>
    (function () {
        "use strict";

        var CLIENT_ID = ' . $jsClientId . ';
        var PROPERTY_ID = ' . $jsPropertyId . ';
        var HOSTNAME = ' . $jsHostname . ';
        var FILTER_HOSTNAME = ' . $jsFilterEnabled . ';
        var STRINGS = ' . analytics_jsonEncode($jsStrings) . ';
        var SCOPES = "https://www.googleapis.com/auth/analytics.readonly";
        var tokenClient = null;
        var gaChart = null;
        var cacheKeyBase = "analytics_1_1_3_" + PROPERTY_ID + "_" + (FILTER_HOSTNAME ? HOSTNAME : "all");
        var cacheDataKey = cacheKeyBase + "_data";
        var cacheTimeKey = cacheKeyBase + "_time";

        function metricValue(report, index) {
            if (!report || !report.rows || !report.rows.length || !report.rows[0].metricValues || !report.rows[0].metricValues[index]) {
                return 0;
            }
            return parseInt(report.rows[0].metricValues[index].value, 10) || 0;
        }

        function dimensionFilter() {
            if (!FILTER_HOSTNAME) {
                return null;
            }
            return {
                filter: {
                    fieldName: "hostName",
                    stringFilter: {
                        matchType: "EXACT",
                        value: HOSTNAME,
                        caseSensitive: false
                    }
                }
            };
        }

        function reportBody(startDate, endDate, withDateDimension) {
            var body = {
                dateRanges: [{ startDate: startDate, endDate: endDate }],
                metrics: [{ name: "activeUsers" }, { name: "screenPageViews" }]
            };

            if (withDateDimension) {
                body.dimensions = [{ name: "date" }];
                body.orderBys = [{ dimension: { dimensionName: "date" } }];
            }

            var filter = dimensionFilter();
            if (filter) {
                // Request hostName as a dimension as well as filtering on it.
                // This follows the Data API requirement that filtered dimensions
                // are part of the requested dimensions.
                if (!body.dimensions) {
                    body.dimensions = [];
                }
                body.dimensions.push({ name: "hostName" });
                body.dimensionFilter = filter;
            }

            return body;
        }

        function friendlyApiError(status, message) {
            if (status === 401) {
                return STRINGS.authError;
            }
            if (status === 403) {
                return STRINGS.permissionError;
            }
            if (status === 429) {
                return STRINGS.quotaError;
            }
            return (message || STRINGS.requestFailed) + " (HTTP " + status + ")";
        }

        function runReport(token, body) {
            var url = "https://analyticsdata.googleapis.com/v1beta/properties/" + encodeURIComponent(PROPERTY_ID) + ":runReport";

            return fetch(url, {
                method: "POST",
                headers: {
                    "Authorization": "Bearer " + token,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(body)
            }).then(function (response) {
                return response.json().catch(function () { return {}; }).then(function (data) {
                    if (!response.ok || data.error) {
                        var apiMessage = data && data.error && data.error.message ? data.error.message : "";
                        throw new Error(friendlyApiError(response.status, apiMessage));
                    }
                    return data;
                });
            });
        }

        function buildProcessedData(chartReport, yesterdayReport, last7Report, last30Report) {
            var processed = {
                labels: [],
                users: [],
                views: [],
                kpi: {
                    yesterday: { users: metricValue(yesterdayReport, 0), views: metricValue(yesterdayReport, 1) },
                    last7: { users: metricValue(last7Report, 0), views: metricValue(last7Report, 1) },
                    last30: { users: metricValue(last30Report, 0), views: metricValue(last30Report, 1) }
                }
            };

            if (chartReport && chartReport.rows) {
                chartReport.rows.sort(function (a, b) {
                    return a.dimensionValues[0].value.localeCompare(b.dimensionValues[0].value);
                });

                chartReport.rows.forEach(function (row) {
                    var dateStr = row.dimensionValues[0].value;
                    processed.labels.push(dateStr.substring(6, 8) + "/" + dateStr.substring(4, 6));
                    processed.users.push(parseInt(row.metricValues[0].value, 10) || 0);
                    processed.views.push(parseInt(row.metricValues[1].value, 10) || 0);
                });
            }

            return processed;
        }

        function renderDashboard(chartData) {
            document.getElementById("dashboard-section").style.display = "block";
            document.getElementById("loading-msg").style.display = "none";
            document.getElementById("kpi-y-users").textContent = chartData.kpi.yesterday.users;
            document.getElementById("kpi-y-views").textContent = chartData.kpi.yesterday.views;
            document.getElementById("kpi-7-users").textContent = chartData.kpi.last7.users;
            document.getElementById("kpi-7-views").textContent = chartData.kpi.last7.views;
            document.getElementById("kpi-30-users").textContent = chartData.kpi.last30.users;
            document.getElementById("kpi-30-views").textContent = chartData.kpi.last30.views;

            var ctx = document.getElementById("ga4Chart").getContext("2d");
            if (gaChart) {
                gaChart.destroy();
            }

            gaChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: chartData.labels,
                    datasets: [
                        { label: STRINGS.users, data: chartData.users, borderWidth: 2, fill: false, tension: 0.3 },
                        { label: STRINGS.views, data: chartData.views, borderWidth: 2, fill: false, tension: 0.3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: "index", intersect: false },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        function showError(error) {
            var loading = document.getElementById("loading-msg");
            loading.style.display = "block";
            loading.textContent = STRINGS.error + " " + (error && error.message ? error.message : error);
            document.getElementById("auth-section").style.display = "block";
        }

        function fetchAnalyticsData(token) {
            document.getElementById("dashboard-section").style.display = "block";
            document.getElementById("loading-msg").textContent = STRINGS.loading;
            document.getElementById("loading-msg").style.display = "block";
            document.getElementById("cache-info").style.display = "none";

            Promise.all([
                runReport(token, reportBody("30daysAgo", "yesterday", true)),
                runReport(token, reportBody("yesterday", "yesterday", false)),
                runReport(token, reportBody("7daysAgo", "yesterday", false)),
                runReport(token, reportBody("30daysAgo", "yesterday", false))
            ]).then(function (reports) {
                var processed = buildProcessedData(reports[0], reports[1], reports[2], reports[3]);
                localStorage.setItem(cacheDataKey, JSON.stringify(processed));
                localStorage.setItem(cacheTimeKey, String(Date.now()));
                renderDashboard(processed);
                document.getElementById("auth-section").style.display = "block";
                document.getElementById("authorize-btn").textContent = STRINGS.refresh;
            }).catch(showError);
        }

        function loadCache() {
            var cachedData = localStorage.getItem(cacheDataKey);
            var cacheTime = localStorage.getItem(cacheTimeKey);

            if (!cachedData || !cacheTime) {
                return;
            }

            try {
                var parsedData = JSON.parse(cachedData);
                renderDashboard(parsedData);
                document.getElementById("cache-info").style.display = "block";
                document.getElementById("cache-date-label").textContent = STRINGS.cachedDate.replace("%s", new Date(parseInt(cacheTime, 10)).toLocaleString());
                document.getElementById("authorize-btn").textContent = STRINGS.refresh;
            } catch (e) {
                localStorage.removeItem(cacheDataKey);
                localStorage.removeItem(cacheTimeKey);
            }
        }

        function initDashboard() {
            loadCache();

            tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: CLIENT_ID,
                scope: SCOPES,
                callback: function (tokenResponse) {
                    if (tokenResponse && tokenResponse.access_token) {
                        fetchAnalyticsData(tokenResponse.access_token);
                    }
                }
            });

            document.getElementById("authorize-btn").addEventListener("click", function () {
                tokenClient.requestAccessToken();
            });
        }

        window.addEventListener("load", function () {
            var attempts = 0;
            var dependencyCheck = window.setInterval(function () {
                attempts += 1;
                if (typeof google !== "undefined" && google.accounts && google.accounts.oauth2 && typeof Chart !== "undefined") {
                    window.clearInterval(dependencyCheck);
                    initDashboard();
                    return;
                }

                if (attempts >= 150) {
                    window.clearInterval(dependencyCheck);
                    showError(new Error(STRINGS.dependencyError));
                }
            }, 100);
        });
    }());
    </script>';
}

$content .= $LANG_analytics_admin['manual_html'];

echo COM_createHTMLDocument($content, array('pagetitle' => $LANG_analytics_admin['dashboard_title']));

?>
