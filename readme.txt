=== Roofing Design Tool ===
Contributors: hanscode, marketingdoneright
Requires at least: 6.7.0
Tested up to: 6.7.1
Requires PHP: 8.0
Stable tag: 6.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress Plugin for Roofing Contractors.

== Description ==
The Roofing Design Tool plugin allows users to design their roofs by selecting a roofing style and roofing material. User selections are passed via URL parameters to a submission form (compatible with Fluent Forms Pro or any form plugin), where the chosen options are injected into hidden fields.

== Installation ==
## Upload the Plugin:
- Place the entire roofing-design-tool folder into your /wp-content/plugins/ directory.

## Activate the Plugin:
- Go to the WordPress Dashboard → Plugins and activate Roofing Design Tool.

## Set Up Custom Post Types:
- You will see new menu items for Roofing Styles and Roofing Materials.
- Add your entries (ensure you add featured images for the best appearance).

## Configure Plugin Settings:
- Navigate to Settings → Roof Design Tool.
- Enter your form shortcode (e.g., [fluentform id=\"1\"]).
- Select the page that contains the [roof_design_form] shortcode.
- Specify the hidden field names for the roof style and roofing material values (these must match the hidden fields in your form).

## Add Shortcodes to Your Pages:
- Create a page and insert [roof_design_tool] to display the interactive design tool.
- Create another page and insert [roof_design_form] to display the design summary and submission form.