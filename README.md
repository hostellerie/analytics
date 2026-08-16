# Geeklog Analytics Plugin (GA4)

The Analytics plugin integrates Google Analytics 4 (GA4) into Geeklog. It automatically adds the GA4 tracking code to the public site and provides a traffic dashboard directly in the Geeklog administration area.

Version **1.1.3** improves configuration handling, GA4 statistics accuracy and support for sites using the same GA4 property across several domains or subdomains.

## Features

- **GA4 frontend tracking**: automatically injects the `gtag.js` tracking code using your GA4 Measurement ID.
- **GA4 Data API dashboard**: displays traffic statistics directly in Geeklog administration.
- **Traffic KPIs**: shows Active Users and Page Views for Yesterday, Last 7 Days and Last 30 Days.
- **30-day traffic chart**: displays daily Active Users and Page Views.
- **Hostname filtering**: allows each domain or subdomain to display only its own GA4 statistics when several sites share the same GA4 property.
- **Automatic hostname detection**: by default, the plugin derives the hostname from Geeklog's `site_url`.
- **Client-side cache**: keeps the last dashboard result in the browser to reduce unnecessary API requests.
- **Configuration normalization**: trims and normalizes configured values when they are used.
- **Legacy upgrade support**: upgrades older Analytics plugin installations and removes the obsolete plugin database table when necessary.

## Requirements

- Geeklog **2.1.1 or higher**
- PHP **5.6 or higher**
- A modern browser with JavaScript enabled
- A Google Analytics 4 property
- A Google Cloud project with the **Google Analytics Data API** enabled
- A Google OAuth 2.0 Web Client ID to use the administration dashboard

### Recommended environment

- Geeklog 2.2.2 or higher
- PHP 8.1 or higher

The frontend GA4 tracking only requires the Measurement ID. The Property ID and OAuth Client ID are needed only for the administration dashboard.

## Installation

1. Download the Analytics plugin archive.
2. Open the Geeklog Administration area.
3. Go to **Plugins** (`admin/plugins.php`).
4. Upload and install the plugin archive.
5. Open **Configuration > Analytics**.
6. Enter the required Google Analytics settings.

When upgrading from an older version, use Geeklog's normal plugin update procedure. Version 1.1.3 keeps compatibility with the configuration introduced in Analytics 1.1.1 and 1.1.2.

## Configuration

The plugin configuration contains four values.

### GA4 Measurement ID

Example:

```text
G-XXXXXXXXXX
```

The Measurement ID is used by the public website tracking code.

You can find it in Google Analytics under:

**Admin > Data Streams > Web > Measurement ID**

Do not enter the numeric Property ID in this field.

### GA4 Property ID

Example:

```text
123456789
```

The Property ID is used by the Google Analytics Data API to retrieve dashboard statistics.

You can find it in Google Analytics under:

**Admin > Property Settings**

The plugin accepts a numeric Property ID and normalizes the value when it is used.

Do not enter a Measurement ID such as `G-XXXXXXXXXX` in this field.

### Google OAuth Client ID

Example:

```text
123456789012-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com
```

The OAuth Client ID allows the administration dashboard to request read-only access to Google Analytics data after the administrator authorizes access with a Google account.

The plugin requests the following scope:

```text
https://www.googleapis.com/auth/analytics.readonly
```

No Google Analytics password is stored by the plugin.

### Hostname filter

Version 1.1.3 adds hostname filtering to distinguish several websites that use the same GA4 property.

The setting supports three modes:

- **Empty value**: automatically use the hostname from Geeklog `$_CONF['site_url']`.
- **Explicit hostname**: use the entered hostname, for example `sub1.example.com`.
- **`*`**: disable hostname filtering and display statistics for the entire GA4 property.

Do not include `https://`, a path or a trailing slash when entering a hostname manually.

Example:

```text
sub1.example.com
```

## Using one GA4 property for several subdomains

A GA4 property can receive data from several domains or subdomains. Without a hostname filter, the Data API returns statistics for the entire property.

For example, if these two Geeklog sites use the same GA4 Property ID:

```text
sub1.example.com
sub2.example.com
```

an unfiltered dashboard can show the same totals on both sites.

Analytics 1.1.3 solves this by filtering GA4 Data API reports using the GA4 `hostName` dimension.

With the Hostname filter left empty:

```text
Geeklog site_url: https://sub1.example.com
Dashboard filter: sub1.example.com
```

and:

```text
Geeklog site_url: https://sub2.example.com
Dashboard filter: sub2.example.com
```

Each site can therefore use the same GA4 property while displaying statistics for its own hostname.

Use `*` only when you intentionally want the dashboard to aggregate all domains and subdomains in the GA4 property.

## Google Cloud setup for the dashboard

The frontend tracking code works without Google Cloud OAuth configuration. The following steps are required only if you want to view GA4 statistics inside Geeklog.

### 1. Create or select a Google Cloud project

Open Google Cloud Console and create a project, or select an existing project that you want to use for the Analytics plugin.

### 2. Enable the Google Analytics Data API

In the selected project, enable:

**Google Analytics Data API**

The dashboard cannot retrieve GA4 statistics until this API is enabled.

### 3. Configure the OAuth consent screen

Configure the OAuth consent screen for the Google Cloud project if this has not already been done.

The exact options available depend on your Google Cloud account and project configuration.

### 4. Create an OAuth 2.0 Client ID

Create an OAuth 2.0 credential for a **Web application**.

Add the Geeklog site origin to the allowed JavaScript origins when required by Google.

Example:

```text
https://www.example.com
```

For a subdomain installation, use the actual subdomain origin:

```text
https://sub1.example.com
```

Copy the generated Client ID into the Analytics plugin configuration.

### 5. Check Google Analytics permissions

The Google account used to authorize the dashboard must have access to the configured GA4 property.

In Google Analytics, check:

**Admin > Property Access Management**

The account should have at least sufficient read access to view the property data.

## Using the administration dashboard

Once the Measurement ID, Property ID and OAuth Client ID are configured:

1. Open the Analytics plugin administration page.
2. Click **Authorize Google Analytics Access**.
3. Select a Google account that has access to the configured GA4 property.
4. Grant the requested read-only Analytics permission.
5. The plugin retrieves and displays the GA4 dashboard data.

If browser-cached statistics are available, they are displayed immediately. You can use the refresh button to authorize again and retrieve fresh data.

## Dashboard statistics

Version 1.1.3 displays:

- **Yesterday**
- **Last 7 Days**
- **Last 30 Days**

For each period, the dashboard shows:

- Active Users
- Page Views

The 30-day chart shows the same metrics day by day.

### Accurate Active Users totals

Earlier dashboard code calculated 7-day and 30-day Active Users by adding daily Active Users values. This could count the same visitor more than once across several days.

Version 1.1.3 requests the KPI totals directly from GA4 for each date range. This provides the GA4 Active Users value for the complete period instead of adding daily unique-user counts.

### Completed days

The dashboard uses completed days for its summary periods. This avoids mixing a partially completed current day with full previous days.

The Yesterday KPI is requested explicitly rather than inferred from the position of rows returned by the API.

## Browser cache

The dashboard stores the latest retrieved statistics in the browser's `localStorage`.

Version 1.1.3 isolates cached dashboard data by GA4 Property ID and hostname filter. This prevents cached statistics from one property or hostname being displayed for another configuration.

The cache contains dashboard statistics only. OAuth access tokens are not stored in this dashboard cache.

## Configuration normalization

Version 1.1.3 normalizes configuration values when they are used.

This includes trimming accidental spaces before or after values such as:

```text
G-XXXXXXXXXX
123456789
OAuth Client ID
hostname
```

This avoids failures caused by copying configuration values with leading or trailing whitespace.

## Troubleshooting

### Tracking works but no dashboard is displayed

Check that both of these values are configured:

- GA4 Property ID
- Google OAuth Client ID

The Measurement ID alone enables frontend tracking but does not provide Data API access.

### User does not have sufficient permissions for this property

Check that:

- you authorized the plugin with the correct Google account;
- that account has access to the configured GA4 property;
- the configured Property ID is correct.

### Google Analytics Data API error

Check that the Google Analytics Data API is enabled in the same Google Cloud project as the configured OAuth Client ID.

### OAuth authorization fails

Check the OAuth Client ID configuration in Google Cloud, especially the allowed JavaScript origins for the Geeklog site.

Make sure the protocol and hostname match the site exactly, for example:

```text
https://sub1.example.com
```

### Two subdomains show the same statistics

Check the **Hostname filter** setting.

Leave it empty to automatically use each Geeklog site's `site_url`, or enter an explicit hostname.

Do not use `*` if you want separate statistics for each hostname.

### Dashboard shows no data after enabling hostname filtering

Check how the hostname appears in GA4. The configured hostname must correspond to the hostname recorded by Google Analytics.

Also verify that the configured GA4 property actually receives traffic from that site.

### Measurement ID contains spaces

Version 1.1.3 trims configuration values when they are used, but it is still recommended to store clean values in Geeklog Configuration.

### PHP warning about `$_DB_table_prefix`

Analytics 1.1.2 could produce an `Undefined variable $_DB_table_prefix` warning from `functions.inc` on Geeklog 2.2.2 because of an obsolete global table declaration.

Version 1.1.3 removes that unused declaration. The database prefix is only used inside the upgrade routine where it is required to detect an old Analytics plugin table.

## Security and privacy notes

- The frontend tracking code sends data to Google Analytics according to your GA4 configuration.
- The administration dashboard uses the Google Analytics read-only OAuth scope.
- The plugin does not require a Google account password.
- Administrators remain responsible for configuring Google Analytics in accordance with the privacy and cookie requirements applicable to their website and jurisdiction.

## Compatibility

Analytics 1.1.3 is designed to remain compatible with:

- Geeklog 2.1.1 and later
- PHP 5.6 and later

Recommended for current installations:

- Geeklog 2.2.2 or later
- PHP 8.1 or later

## Upgrading from Analytics 1.1.2

The update keeps the existing:

- Measurement ID
- Property ID
- OAuth Client ID

A new Hostname filter configuration option is added.

Leaving the Hostname filter empty enables automatic filtering using the hostname from Geeklog `site_url`.

If you want the same aggregated behavior as Analytics 1.1.2, set the Hostname filter to:

```text
*
```

## Version 1.1.3 highlights

- Added hostname-aware GA4 statistics.
- Added automatic hostname detection from Geeklog `site_url`.
- Added `*` mode for full-property statistics.
- Corrected multi-day Active Users KPIs.
- Corrected Yesterday calculation.
- Avoided partial current-day values in summary periods.
- Added configuration trimming and normalization.
- Improved configuration validation.
- Isolated dashboard browser cache by property and hostname.
- Improved JavaScript value encoding and dependency handling.
- Improved GA4 API error handling.
- Removed obsolete Analytics table declaration that could trigger a PHP warning on Geeklog 2.2.2.

See `CHANGELOG.md` for the version history.

## Author

Ben (hostellerie.org AT gmail DOT com)

Contributions from the Geeklog community and the original Analytics plugin authors are preserved in the source file headers.
