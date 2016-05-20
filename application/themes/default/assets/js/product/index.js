
$(document).ready(function() {

    $('#productPhoto').click(function() {
        if ($('#productName').val() !== '') {
            var fdata = {
                productName: $('#productName').val()
            }
            $.ajax({
                url: '/ajax/getReleventImages',
                type: 'POST',
                data: fdata,
                dataType: 'JSON',
                async: true,
                error: function() {
                },
                success: function(resp) {
                    $('#product_photos').find('.clsRelevent').find('#gc_photos').html('');
                    $.each(resp, function(index,val){
                        var imgDiv = '<div class="product-image thumbnail" style="float:left">'+
                            '<img src="/uploads/images/thumbnails/'+val+'"/></div>';
                        $('#product_photos').find('.clsRelevent').find('#gc_photos').append(imgDiv);
                        
                    })
                    
                    $('#product_photos').find('.clsRelevent').find('.product-image').click(function(){
                        $(this).addClass('classBlue');
                    })
                }
            });
        }
    })
})