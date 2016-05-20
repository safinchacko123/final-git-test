var site_url;
$(document).ready(function () {
    site_url = $('#site_url').val();

    $('.btn-clear').click(function () {
        $('#term').val('');
        $('#term2').val('');
        $('#term3').val('');
    })

    $(".clsTooltip").popover({
        html: true,
        trigger: 'hover',
        'placement': function (e) {
            return 'bottom';
        }
    });

    // Create Tooltip for default address
    var addContent = $('.clsBillAdd').html();
    $('.clsNavAdd').addClass('clsTooltip').attr('rel', 'tooltip').attr('title', 'Current address').attr('data-content', addContent);

    $(".clsTooltip").popover({
        html: true,
        trigger: 'hover',
        'placement': function (e) {
            return 'bottom';
        }
    });
    
    var cp = getUrlParameter('cp');
    if(cp){
        $('.tabbable').find('a').each(function(){
            if($(this).text() === 'Profile'){
                $(this).parent().removeClass('active');
                $('.tab-content').find('#tabInfo').removeClass('active');
            }
            if($(this).text() === 'Change password'){
                $(this).parent().addClass('active');
                $('.tab-content').find('#tabPass').addClass('active');
            }
        })
    }
    
//     $("#product-add").submit(function () {
//        if (!$('input[name^="images"]').length) {
//            var error_msg = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>Ã—</a><div id='error-msg'>Atleast one product image is needed.</div></div>";
//            $("#error-div").html(error_msg);
//            return false;
//        }
//
//    });
    });

function maketoast(priority, title, message)
{
    //$('#toastCode').html("$.toaster({ priority : '" + priority + "', title : '" + title + "', message : '" + message + "'});");
    $.toaster({priority: priority, title: title, message: message});
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};