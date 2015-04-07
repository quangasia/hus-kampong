$(document).ready(function () {
    $(document).on('submit', 'form#commentForm', function (e) {
        e.preventDefault();
        $url = $(this).attr('action');
        $container = $('#commentContainer');
        $prototype = $container.data('prototype');

        $.ajax({
            type: 'POST',
            url: $url,
            data: $(this).serialize(),
            success: function (response) {
                $res = $.parseJSON( response );
                if ($res.is_error === false) {
                    $data = $prototype.replace(/{fullname}/g, $res.fullname).replace(/{comment}/g, $res.comment).replace(/{commentid}/g, $res.commentid);
                    $container.append($data);
                } else {
                    alert('Có lỗi, vui lòng viết lại nhận xét!');
                }
            },
            error: function () {

            }
        });
    });
    
    $("#rate").jRate({
        rating: 0,
        startColor: 'yellow',
        endColor: 'yellow',
        width: 25,
        height: 25,
        precision: 0,
        onChange: function(rating) {
            $('.ratePoint').val(rating);
            console.log("OnChange: Rating: "+rating);
        },
        onSet: function(rating) {
            console.log("OnSet: Rating: "+rating);
        }
    });
});



