jQuery(document).ready(function() {  
    //jQuery('#slider').nivoSlider();  
//    jQuery('#slider').nivoSlider(); 
    jQuery('.remove-btn').each(function(){
        jQuery(this).on('click', function(){
            jQuery(this).parent().parent().remove();
        });
    });
    jQuery('#sortable').sortable();
    var custom_uploader;
    jQuery('.upload_image_button').click(function() {
            var btn = jQuery(this);
            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                    custom_uploader.open();
                    return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                            text: 'Choose Image'
                    },
                    multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                    attachment = custom_uploader.state().get('selection').first().toJSON();
                    btn.parent().parent().find('.upload_image_src').attr('src', attachment.url);
                    btn.parent().parent().find('.upload_image_id').val(attachment.id);
            });

            //Open the uploader dialog
            custom_uploader.open();
    });
});

function makeClone(){
    // clone the div
    var cloned = jQuery("tr.clone-div").first().clone(false);
    // change all id values to a new unique value by adding "_cloneX" to the end

    // where X is a number that increases each time you make a clone
    jQuery("*", cloned).add(cloned);
    cloned.find('img').attr('src', '');
    cloned.find('.remove-btn').show();
    jQuery('.clone-div').parent().append(cloned);
    var custom_uploader;
    jQuery('.upload_image_button').click(function() {
        var btn = jQuery(this);

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
                custom_uploader.open();
                return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                        text: 'Choose Image'
                },
                multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                btn.parent().parent().find('.upload_image_src').attr('src', attachment.url);
                btn.parent().parent().find('.upload_image_id').val(attachment.id);
        });

        //Open the uploader dialog
        custom_uploader.open();

    });
    jQuery('.remove-btn').each(function(){
        jQuery(this).on('click', function(){
            jQuery(this).parent().parent().remove();
        });
    });
}   
 