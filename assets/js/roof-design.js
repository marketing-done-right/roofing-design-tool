jQuery(document).ready(function($) {
    var selectedStyle = '';
    var selectedMaterial = '';

    // When a roof style is selected.
    $('.rdt-select-style').on('click', function() {
        selectedStyle = $(this).closest('.rdt-card').data('style');
        $('#rdt-selected-style').text(selectedStyle);
        $('.rdt-styles .rdt-card').removeClass('rdt-selected');
        $(this).closest('.rdt-card').addClass('rdt-selected');
    });

    // When a roof material is selected.
    $('.rdt-select-material').on('click', function() {
        selectedMaterial = $(this).closest('.rdt-card').data('material');
        $('#rdt-selected-material').text(selectedMaterial);
        $('.rdt-materials .rdt-card').removeClass('rdt-selected');
        $(this).closest('.rdt-card').addClass('rdt-selected');
    });

    // Redirect on "Submit Design" click.
    $('#rdt-submit-design').on('click', function() {
        if ( selectedStyle === '' || selectedMaterial === '' ) {
            alert('Please select both a roof style and a roof material.');
            return;
        }
        // Use the design form page URL passed from PHP (if set).
        var baseUrl = rdt_vars.designFormUrl ? rdt_vars.designFormUrl : (window.location.origin + window.location.pathname);
        // Append URL parameters using the keys from settings.
        var redirectURL = baseUrl + '?';
        redirectURL += encodeURIComponent(rdt_vars.hiddenStyleKey) + '=' + encodeURIComponent(selectedStyle) + '&';
        redirectURL += encodeURIComponent(rdt_vars.hiddenMaterialKey) + '=' + encodeURIComponent(selectedMaterial);
        window.location.href = redirectURL;
    });
});
