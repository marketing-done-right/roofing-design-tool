# Roofing Design Tool

**A WordPress Plugin for Roofing Contractors**

The Roofing Design Tool plugin allows users to design their roofs by selecting a roofing style and roofing material. User selections are passed via URL parameters to a submission form (compatible with Fluent Forms Pro or any form plugin), where the chosen options are injected into hidden fields.

## Features

- **Custom Post Types:**  
  - **Roofing Styles:** Easily manage roofing style entries (with title, description, and featured image).  
  - **Roofing Materials:** Manage roofing material entries similarly.

- **Front-End Design Tool:**  
  - Use the `[roof_design_tool]` shortcode to display an interactive interface where users choose a roofing style and material.
  - Cards display images and titles for each roofing style and roofing material.

- **Configurable Design Form:**  
  - Use the `[roof_design_form]` shortcode to render a design summary and a contact/submission form.
  - An inline PHP script reads URL parameters and automatically populates hidden fields in the form.

- **Flexible Form Integration:**  
  - Configure the form shortcode (for Fluent Forms Pro or any other form plugin) via the plugin settings.
  - Specify the design form page and the hidden field names to match your form configuration.

- **WordPress Best Practices:**  
  - Secure coding practices including escaping, sanitization, use of nonces, and ABSPATH checks.
  - Enqueue scripts and styles correctly and use localizing for passing settings to JavaScript.

## Installation

1. **Upload the Plugin:**  
   Place the entire `roofing-design-tool` folder into your `/wp-content/plugins/` directory.

2. **Activate the Plugin:**  
   Go to the WordPress Dashboard → **Plugins** and activate **Roofing Design Tool**.

3. **Set Up Custom Post Types:**  
   You will see new menu items for **Roofing Styles** and **Roofing Materials**.  
   Add your entries (ensure you add featured images for the best appearance).

4. **Configure Plugin Settings:**  
   Navigate to **Settings → Roof Design Tool**.
   - Enter your form shortcode (e.g., `[fluentform id="1"]`).
   - Select the page that contains the `[roof_design_form]` shortcode.
   - Specify the hidden field names for the roof style and roofing material values (these must match the hidden fields in your form).

5. **Add Shortcodes to Your Pages:**  
   - Create a page and insert `[roof_design_tool]` to display the interactive design tool.
   - Create another page and insert `[roof_design_form]` to display the design summary and submission form.

## Usage

- **Front-End Design Page:**  
  When a user selects a roofing style and material, clicking the **Submit Design** button will redirect them to the configured Design Form Page with the selected options appended as URL parameters.

- **Form Page:**  
  The inline PHP script on the form page reads these URL parameters and populates the hidden fields so that your form (e.g., Fluent Forms Pro) receives the design data.

## File Structure

```
roofing-design-tool/
├── assets
│   ├── css
│   │   └── roof-design.css      # Styles for the front-end tool
│   └── js
│       └── roof-design.js       # JavaScript for handling selections and redirection
└── roofing-design-tool.php      # Main plugin file with custom post types, shortcodes, and settings page
```


## Development

- **Custom Post Types Registration:**  
  Located in the `rdt_register_post_types()` function in the main plugin file.

- **Shortcodes:**  
  - `[roof_design_tool]` is implemented in `rdt_display_design_tool()`.
  - `[roof_design_form]` is implemented in `rdt_display_design_form()`.

- **Plugin Settings:**  
  Configured via the settings page registered in the `rdt_add_settings_page()` function.

- **JavaScript & CSS:**  
  The assets in the `assets/js/` and `assets/css/` directories handle the front-end behavior and styling.

- **Inline Script for Form Field Population:**  
  In the `[roof_design_form]` output, an inline script uses `window.location.search` to populate hidden fields based on the URL parameters.

## Contributing

1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Create a new Pull Request.

## License

This project is licensed under the [GPL2 License](LICENSE).
