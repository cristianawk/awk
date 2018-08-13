(function($){
$(document).ready(function() {

    var $page_template = $('#page_template')
        ,$metabox = $('#business_press_page_meta_box_cntnr');

    $page_template.change(function() {
        if ($(this).val() == 'template-landing-page.php') {
            $metabox.hide();
        } else {
            $metabox.show();
        }
    }).change();

});
})(jQuery);