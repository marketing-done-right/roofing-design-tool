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
        // Redirect to the current page with URL parameters.
        // (You can change this logic if you want to redirect to a dedicated contact page.)
        var redirectURL = window.location.origin + window.location.pathname + '?style=' + encodeURIComponent(selectedStyle) + '&material=' + encodeURIComponent(selectedMaterial);
        window.location.href = redirectURL;
    });
});
