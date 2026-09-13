jQuery(document).ready(function($) {

    if ($("#jform_fb_description").length) { // On a Form with FB Description Textarea add the characters left
        var maxlengthFb = tagz.max_chars_description_facebook;
        var currentLengthFb = $('#jform_fb_description').val().length;
        var charLeftFb = maxlengthFb - currentLengthFb;

        $("#jform_fb_description").after("<span id='fb_character_count' class='character_count green'>Characters left: " + charLeftFb + "</span>");
        if (charLeftFb < 0) {
            $("#fb_character_count").removeClass("green");
            $("#fb_character_count").addClass("red");
        }

        $("#jform_fb_description").on("input", function() {
            currentLengthFb = $('#jform_fb_description').val().length;
            charLeftFb = maxlengthFb - currentLengthFb;

            $("#fb_character_count").html("<span>Characters left: " + charLeftFb + "</span>");
            if (charLeftFb < 0) {
                $("#fb_character_count").removeClass("green");
                $("#fb_character_count").addClass("red");
            } else {
                $("#fb_character_count").removeClass("red");
                $("#fb_character_count").addClass("green");
            }
        });
    }

    if ($("#jform_twitter_description").length) { // On a Form with Twitter Description Textarea add the characters left
        var maxlengthTwitter = tagz.max_chars_description_twitter;
        var currentLengthTwitter = $('#jform_twitter_description').val().length;
        var charLeftTwitter = maxlengthTwitter - currentLengthTwitter;

        $("#jform_twitter_description").after("<span id='twitter_character_count' class='character_count green'>Characters left: " + charLeftTwitter + "</span>");
        if (charLeftTwitter < 0) {
            $("#twitter_character_count").removeClass("green");
            $("#twitter_character_count").addClass("red");
        }

        $("#jform_twitter_description").on("input", function() {
            currentLengthTwitter = $('#jform_twitter_description').val().length;
            charLeftTwitter = maxlengthTwitter - currentLengthTwitter;

            $("#twitter_character_count").html("<span>Characters left: " + charLeftTwitter + "</span>");
            if (charLeftTwitter < 0) {
                $("#twitter_character_count").removeClass("green");
                $("#twitter_character_count").addClass("red");
            } else {
                $("#twitter_character_count").removeClass("red");
                $("#twitter_character_count").addClass("green");
            }
        });
    }

    // Enable fancy checkboxes
    $(".labelauty").labelauty();
    $(".labelauty_small").labelauty({
        extra: true,
        label: false
    });
    $(".labelauty_icon").labelauty({
        label: false
    });

    $('.button-select').on('click', function(e) {
        // break media field , cbs j3.x
        // $('[id^=imageModal]').remove();
    });

    $('body').loadingIndicator();

    // If Tag is locked disable the Get TAG From Item button
    if ($('#jform_lock_tag0').is(':checked')) {
        $('#adminForm :input').prop('readonly', true);
        $('#get_tag_info').prop('disabled', true);
        $('#jform_lock_tag').prop('readonly', false);
    }

    $('#jform_lock_tag').on('click', function(e) {
        if ($('#jform_lock_tag0').is(':checked')) { // Locked
            $('#adminForm :input').prop('readonly', true);
            $('#get_tag_info').prop('disabled', true);
            $('#jform_lock_tag').prop('readonly', false);
        } else {
            $('#adminForm :input').prop('readonly', false);
            $('#get_tag_info').prop('disabled', false);
        }
    });

    // Show info that the user has changed inputs and needs to save to update preview: text inputs
    $("#adminForm.tagz_edit :input").keyup(function() {
        var input_id = '#tagz_changed_notice_' + $(this).parents().eq(3).attr('id');
        $(input_id).css('display', 'block');
        $('.tagz_preview_container_' + $(this).parents().eq(3).attr('id')).addClass("tagz_blurred_overlay");
    });

    // Show info that the user has changed inputs and needs to save to update preview: image
    $("#adminForm.tagz_edit [id$=_image]").change(function() {
        var input_id = '#tagz_changed_notice_' + $(this).parents().eq(5).attr('id');
        $(input_id).css('display', 'block');
        $('.tagz_preview_container_' + $(this).parents().eq(5).attr('id')).addClass("tagz_blurred_overlay");
    });

    // Remove some weird error in form.php that did not affect functionality
    $('.span12').contents().filter(function() {
        return this.nodeType === 3;
    }).remove().end().filter('b').remove().end().filter('br').remove();

    // Force the admin to go to the last tab
    $('.rh_tab-links a').trigger("click");

});