jQuery(document).ready(function($) {
    var selectedStyle = '';
    var selectedMaterial = '';

    // When a roof style is selected.
    $('.rdt-select-style').on('click', function() {
        var $card = $(this).closest('.rdt-card');
        selectedStyle = $card.data('style');
        var styleImage = $card.data('style-img'); // get the image URL
        $('#rdt-selected-style').text(selectedStyle);
        // Set the image in the summary if available
        if(styleImage) {
            $('#rdt-selected-style-img').html('<img src="'+ styleImage +'" alt="'+ selectedStyle +'" />');
        }
        $('.rdt-styles .rdt-card').removeClass('rdt-selected');
        $card.addClass('rdt-selected');
    });    

    // When a roof material is selected.
    $('.rdt-select-material').on('click', function() {
        var $card = $(this).closest('.rdt-card');
        selectedMaterial = $card.data('material');
        var materialImage = $card.data('material-img'); // get the image URL
        $('#rdt-selected-material').text(selectedMaterial);
        // Set the image in the summary if available
        if(materialImage) {
            $('#rdt-selected-material-img').html('<img src="'+ materialImage +'" alt="'+ selectedMaterial +'" />');
        }
        $('.rdt-materials .rdt-card').removeClass('rdt-selected');
        $card.addClass('rdt-selected');
    });    

    // Redirect on "Submit Design" click.
    $('#rdt-submit-design').on('click', function() {
        if ( selectedStyle === '' || selectedMaterial === '' ) {
            alert('Please select both a roof style and a roof material.');
            return;
        }
        // Determine the proper separator for the query string.
        // If the base URL already contains a "?" (i.e. existing query parameters),
        // we use "&" to append additional parameters; otherwise, we start with "?".
        var baseUrl = rdt_vars.designFormUrl ? rdt_vars.designFormUrl : (window.location.origin + window.location.pathname);
        // Construct the redirect URL by appending the hidden field parameters.
        // We encode the keys and values to ensure they are safe for URLs.
        // - rdt_vars.hiddenStyleKey: the query parameter key for the roof style.
        // - selectedStyle: the value chosen by the user for the roof style.
        // - rdt_vars.hiddenMaterialKey: the query parameter key for the roof material.
        // - selectedMaterial: the value chosen by the user for the roof material.
        var separator = baseUrl.indexOf('?') !== -1 ? '&' : '?';
        var redirectURL = baseUrl + separator +
            encodeURIComponent(rdt_vars.hiddenStyleKey) + '=' + encodeURIComponent(selectedStyle) + '&' +
            encodeURIComponent(rdt_vars.hiddenMaterialKey) + '=' + encodeURIComponent(selectedMaterial);
        // Redirect the browser to the constructed URL, which should pass the query parameters.
        window.location.href = redirectURL;
    });
});