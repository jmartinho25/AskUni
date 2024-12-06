$(document).ready(function() {
    $('.follow-btn').click(function() {
        var questionId = $(this).data('question-id');
        var isFollowing = $(this).hasClass('btn-warning');

        $.ajax({
            url: isFollowing ? '/questions/' + questionId + '/unfollow' : '/questions/' + questionId + '/follow',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $(this).toggleClass('btn-warning btn-primary');
                    $(this).text(isFollowing ? 'Follow' : 'Unfollow');
                }
            }.bind(this),
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    });
});