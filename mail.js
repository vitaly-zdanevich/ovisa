var selector = 'form';
$(selector).each(function(indx){if($(this).attr('action') === undefined){$(this).attr('action', '/').attr('method','post');}});
$('.w-form [data-name]').each(function(indx){$(this).attr('name', $(this).attr('data-name'));});
$(function() {
    $(selector+'[action = "/"]').submit(function(e) {

        hide = 0; // 1 - прятать форму после отправки (0 - не прятать)
        delay = 3000; // задержка исчезновения сообщения в миллисекундах (0 - не скрывать)
        success_msg = "Ваше сообщение отправлено!"; // сообщение об успешной отправке
        error_msg = "Ошибка отправки! Попробуйте позже."; // сообщение об ошибке
        wait_msg = 'Идет отправка...'; // сообщение об отправке (оставить пустым чтоб не показывать)
        redirect = 'thanks.php'; // страница, на котороую перейти после отправки (оставить пустым чтоб не переходить)
        action = '/mail.php'; // скрипт отправки почты

        cur_id = '#'+$(this).attr('id');
        if($(cur_id).attr('data-hide') !== undefined){ hide = parseInt($(cur_id).attr('data-hide')); }
        if($(cur_id).attr('data-delay') !== undefined){ delay = parseInt($(cur_id).attr('data-delay')); }
        cur_success = $(cur_id).siblings('.w-form-done').text(); if(cur_success !== 'Thank you! Your submission has been received!'){ success_msg = cur_success; }
        cur_error = $(cur_id).siblings('.w-form-fail').text(); if(cur_error !== 'Oops! Something went wrong while submitting the form'){ error_msg = cur_error; }
        cur_wait = $(cur_id).find('[data-wait]').attr('data-wait'); if(cur_wait !== 'Please wait...'){ wait_msg = cur_wait; }
        cur_redirect = $(cur_id).attr('data-redirect'); if(cur_redirect !== undefined){ redirect = cur_redirect; }
        cur_action = $(cur_id).attr('action'); if(cur_action !== '/'){ action = cur_action; }
        submit_div = $(cur_id).find('[type = submit]');
        submit_txt = submit_div.attr('value');
        if(wait_msg !== ''){ submit_div.attr('value', wait_msg); }
        if($(cur_id).attr('data-send') !== undefined){ $('<input type="hidden" name="sendto" value="'+$(cur_id).attr('data-send')+'">').prependTo(cur_id); }
        $('<input type="hidden" name="Форма" value="'+$(cur_id).attr('data-name')+'">').prependTo(cur_id);
        $('<input type="hidden" name="Страница" value="'+document.location.href+'">').prependTo(cur_id);
        e.preventDefault();
        var formData = new FormData($(cur_id)[0]);
        $.ajax({
          url: action,
          type: 'POST',
          processData: false,
          contentType: false,
          data: formData
        })
        .done(function( result ) {
        if(result == 'success'){ replay_class = '.w-form-done'; replay_msg = success_msg;
          if(redirect !== '') { document.location.href = redirect; return(true); }
          } else { replay_class = '.w-form-fail'; replay_msg = error_msg; }
          replay_div = $(cur_id).siblings(replay_class);
          replay_div.find('p').text(replay_msg);
          replay_div.show();
          if(hide) {$(cur_id).hide();}
          submit_div.attr('value', submit_txt);
          if(delay !== 0) { replay_div.delay(delay).fadeOut(); }
          if(result == 'success'){$(cur_id).trigger("reset");}
      });
    });
});
