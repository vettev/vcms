$(document).ready(function()
{
    $('.form-creator #database').slideToggle(300);
    $('.change-form').click(function(e)
    {
        e.preventDefault();
        var target = $(this).data('target');
        var isOk = true;
        var inputs = $(this).parent().parent().find('input[type="text"], input[type="password"], input[type="email"]');
        var email = $(this).parent().parent().find('input[type="email"]');
        inputs.each(function(i)
        {
            if($(this).is('[type="email"]'))
            {
                var mail = $(this).val();
                if(!mail.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/i))
                {
                    $(this).parent().parent().find('.errors').html('Email is not valid.');
                    isOk = false;
                }
            }
            else if($(this).is('[name="user[username]"]'))
            {
                var username = $(this).val();
                if(!username.match(/^[a-zA-Z0-9_.]+$/i))
                {
                    $(this).parent().parent().find('.errors').html('Username is not valid.');
                    isOk = false;
                }
            }
            else if(!$(this).val() && $(this).is(':required'))
            {
                $(this).parent().parent().find('.errors').html('Fill all required fields.');
                isOk = false;
            }
        });
        inputs.change(function()
        {
            if($(this).val())
                isOk = true;
        });
        if(isOk)
        {
            $(this).parent().parent().slideToggle(300);
            $(target).slideToggle(300);
        }
    });
    $('.form-creator').submit(function()
    {
        $('.loader').fadeIn(500);
    });

	var isEditing = false; // flag that checks if comment is in edition 
	$('.ajax').click(function(e)
	{
    	e.preventDefault();
    	var url = $(this).attr('href');
    	var target = '#' + $(this).data('target');

	if($(this).hasClass('comment-edit'))
    {
        if(!isEditing)
        {
                $.ajax({
                url: url,
                beforeSend: function()
                {
                    isEditing = true;
                },
                error: function()
                {
                    isEditing = false;
                },
                success: function(data)
                {
                    $(target).find('.content').slideToggle(100).html(data).slideToggle(200);
                    $(target).find('.operations').slideToggle(200);
                    $('.ajax-form').submit(function(e)
                    {
                        var action = $(this).attr('action');
                        var form = $(this);
                        if($(this).hasClass('comment-edit-form'))
                        {
                            $.ajax({
                                url: action,
                                type: 'POST',
                                data: form.serialize(),
                                success: function(data)
                                {
                                    isEditing = false;
                                    var content = $(data).find('.content').html();
                                    $(target).find('.content').slideToggle(200).html( content ).slideToggle(200);
                                    $(target).find('.operations').slideToggle(300);
                                }
                            });
                        }
                        e.preventDefault();
                    });
                }
            })
            }
            else
                alert("Only 1 comment can be edit at the same time.");
    	}
        else if($(this).hasClass('comment-delete'))
        {
            $.ajax({
                url: url,
                success: function()
                {
                    $(target).slideToggle(500, function(){ $(this).detach(); } );
                }
            })
        }
	});
    $('.ajax-form').submit(function(e)
    {
        if($(this).hasClass('comment-add-form'))
        {
            var action = $(this).attr('action');
            var form = $(this);
            $.ajax({
                url: action,
                type: 'POST',
                data: form.serialize(),
                success: function(data)
                {
                    $('.comment-add-form').after( data );
                    $('.comment').first().hide().addClass('hiddenByScaleY').show().removeClass('hiddenByScaleY');
                    $('.comment-add-form textarea').val("");
                    $('.ajax').click(function(e)
                    {
                        e.preventDefault();
                        var url = $(this).attr('href');
                        var target = '#' + $(this).data('target');

                    if($(this).hasClass('comment-edit'))
                    {
                        if(!isEditing)
                        {
                                $.ajax({
                                url: url,
                                beforeSend: function()
                                {
                                    isEditing = true;
                                },
                                error: function()
                                {
                                    isEditing = false;
                                },
                                success: function(data)
                                {
                                    $(target).find('.content').slideToggle(100).html(data).slideToggle(200);
                                    $(target).find('.operations').slideToggle(200);
                                    $('.ajax-form').submit(function(e)
                                    {
                                        var action = $(this).attr('action');
                                        var form = $(this);
                                        if($(this).hasClass('comment-edit-form'))
                                        {
                                            $.ajax({
                                                url: action,
                                                type: 'POST',
                                                data: form.serialize(),
                                                success: function(data)
                                                {
                                                    isEditing = false;
                                                    var content = $(data).find('.content').html();
                                                    $(target).find('.content').slideToggle(200).html( content ).slideToggle(200);
                                                    $(target).find('.operations').slideToggle(300);
                                                }
                                            });
                                        }
                                        e.preventDefault();
                                    });
                                }
                            })
                            }
                            else
                                alert("Only 1 comment can be edit at the same time.");
                        }
                        else if($(this).hasClass('comment-delete'))
                        {
                            $.ajax({
                                url: url,
                                success: function()
                                {
                                    $(target).slideToggle(500, function(){ $(this).detach(); } );
                                }
                            })
                        }
                    });
                }
            });
        }
        e.preventDefault();
    });
});