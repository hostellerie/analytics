# Geeklog Analytics Plugin (GA4)

This plugin integrates Google Analytics 4 into your Geeklog website. It provides both the frontend tracking snippet and a full visual dashboard in the administration area.

## Features
- **GA4 Frontend Tracking**: Automatically injects the modern `gtag.js` tracking code using your Measurement ID (`G-XXXXX`).
- **Data API Dashboard**: A complete dashboard in the Geeklog admin area showing traffic evolution over the last 30 days.
- **Client-Side Caching**: Uses local browser caching to reduce OAuth authorization prompts and API requests.
- **KPI Summary**: Quick overview of Yesterday, Last 7 Days, and Last 30 Days traffic.

## Requirements
- Geeklog 2.2.2 or higher
- PHP 7.4 or higher
- A Google Cloud Project with the **Google Analytics Data API** enabled.

## Installation
1. Upload the plugin archive and install it directly from your Geeklog Administration panel under **Plugins** (`admin/plugins.php`).
2. Configure the plugin in the Geeklog Configuration:
   - **GA4 Measurement ID**: E.g., `G-XXXXXX` (Used for tracking)
   - **GA4 Property ID**: Numeric ID (Used for the admin dashboard)
   - **Google OAuth Client ID**: Client ID created in Google Cloud Console.

## Dashboard Setup
To view the admin dashboard charts:
1. Ensure your Google OAuth Client ID is properly configured.
2. Open the Analytics plugin administration page.
3. Click "Authorize Google Analytics Access" and log in with an account that has at least "Viewer" access to the GA4 Property.

## Author
Ben (hostellerie.org AT gmail DOT com)
