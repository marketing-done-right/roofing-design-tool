jQuery(document).ready(function($) {
    let selectedStyleId = '';
    let selectedMaterialId = '';

    // When a roof style is selected.
    $('.rdt-select-style').on('click', function() {
        const $card = $(this).closest('.rdt-card');
        selectedStyleId = $card.data('style-id');
        const styleTitle = $card.data('style'); // get the title from the data attribute
        const styleImage = $card.data('style-img'); // get the image URL
        $('#rdt-selected-style').text(styleTitle);
        // Set the image in the summary if available
        if (styleImage) {
            $('#rdt-selected-style-img').html('<img src="'+ styleImage +'" alt="'+ styleTitle +'" />');
        }
        $('.rdt-styles .rdt-card').removeClass('rdt-selected');
        $card.addClass('rdt-selected');
    });

    // When a roof material is selected.
    $('.rdt-select-material').on('click', function() {
        const $card = $(this).closest('.rdt-card');
        selectedMaterialId = $card.data('material-id');
        const materialTitle = $card.data('material'); // get the title from the data attribute
        const materialImage = $card.data('material-img'); // get the image URL
        $('#rdt-selected-material').text(materialTitle);
        // Set the image in the summary if available
        if (materialImage) {
            $('#rdt-selected-material-img').html('<img src="'+ materialImage +'" alt="'+ materialTitle +'" />');
        }
        $('.rdt-materials .rdt-card').removeClass('rdt-selected');
        $card.addClass('rdt-selected');
    });

    // Redirect on "Submit Design" click.
    $('#rdt-submit-design').on('click', function() {
        if ( selectedStyleId === '' || selectedMaterialId === '' ) {
            alert('Please select both a roof style and a roof material.');
            return;
        }
        const baseUrl = rdt_vars.designFormUrl ? rdt_vars.designFormUrl : (window.location.origin + window.location.pathname);
        const separator = baseUrl.indexOf('?') !== -1 ? '&' : '?';
        const redirectURL = baseUrl + separator +
            'style_id=' + encodeURIComponent(selectedStyleId) + '&' +
            'material_id=' + encodeURIComponent(selectedMaterialId);
        window.location.href = redirectURL;
    });
});