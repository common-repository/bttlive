/**
 * Created by bernt on 22.09.14.
 */
jQuery(document).ready(function($){


    var portrait_uploader;
    var action_uploader;
    var mannschafts_uploader;

    $('#portrait_bild_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (portrait_uploader) {
            portrait_uploader.open();
            return;
        }

        //Extend the wp.media object
        portrait_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Wähle Portrait',
            button: {
                text: 'Wähle Portrait'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        portrait_uploader.on('select', function() {
            attachment = portrait_uploader.state().get('selection').first().toJSON();
            $('#portrait_bild').val(attachment.url);
            $('#spieler_img1').attr('src',attachment.url).show();
        });

        //Open the uploader dialog
        portrait_uploader.open();

    });
    $('#mannschafts_bild_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (mannschafts_uploader) {
            mannschafts_uploader.open();
            return;
        }

        //Extend the wp.media object
        mannschafts_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Wähle Mannschaftsbild',
            button: {
                text: 'Wähle Mannschaftsbild'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        mannschafts_uploader.on('select', function() {
            attachment = mannschafts_uploader.state().get('selection').first().toJSON();
            $('#mannschafts_bild_id').val(attachment.url);
            $('#mannschafts_img_id').attr('src',attachment.url).show();
        });

        //Open the uploader dialog
        mannschafts_uploader.open();

    });
    $('#action_bild_button').click(function(e) {

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (action_uploader) {
            action_uploader.open();
            return;
        }

        //Extend the wp.media object
        action_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Wähle Actionbild',
            button: {
                text: 'Wähle Actionbild'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        action_uploader.on('select', function() {
            attachment = action_uploader.state().get('selection').first().toJSON();
            $('#action_bild').val(attachment.url);
            $('#spieler_img2').attr('src',attachment.url).show();
        });

        //Open the uploader dialog
        action_uploader.open();

    });
    $('#portrait_bild').on('change keyup paste', function () {
        var imgpath = $(this).attr('value');
        $('#spieler_img1').attr('src', imgpath).show();
    });

    $('#action_bild').change(function(e) {
        var imgpath = $(this).attr('value');
        $('#spieler_img2').attr('src', imgpath).show();
    });
    $('#mannschafts_bild_id').on('change keyup paste', function () {
        var imgpath = $(this).attr('value');
        $('#mannschafts_img_id').attr('src', imgpath).show();
    });
});
