<?php
$email = 'visaminsk.by@yandex.ru'; // адрес куда отправлять письмо, можно несколько через запятую
$subject = 'Новое сообщение с сайта '.$_SERVER['HTTP_HOST']; // тема письма
$message = ''; // первая строчка письма Данные формы:
$smtp = 1; // отправлять ли через личный почтовый ящик, 1 - отправлять, 0 - через хостинг
$__smtp = array(
    "host" => 'smtp.yandex.ru', // сервер отправки писем (SMTP)
    "auth" => true, // нужна ли авторизация (как правило нужна)
    "secure" => 'ssl', // тип защиты
    "port" => 465, // порт сервера
    "charset" => 'utf-8', // кодировка
    "from" => 'Visaminsk', // имя отправителя
    "addreply" => 'visaminsk.by@yandex.ru', // адрес куда отвечать (обязательно)
    "username" => 'visaminsk.by@yandex.ru', // логин почтового сервера
    "password" => '80297509842', // пароль к почте
);
$fields = "";
foreach($_POST as $key => $value){
  if($value === 'on'){ $value = 'Да'; }
  if($key === 'sendto'){ $email = $value; } else {
    if($value !== ''){ $fields .= str_replace('_',' ',$key).': <b>'.$value.'</b> <br />'; }
  }
}

smtpmail($email, $subject, $message.'<br>'.$fields);

function smtpmail($to, $subject, $content)
{
global $success, $__smtp, $smtp, $redirect;
require_once('./class-phpmailer.php');
$mail = new PHPMailer(true);
if($smtp) {$mail->IsSMTP();}
try {
  $mail->Host       = $__smtp['host'];
  $mail->SMTPDebug  = 0;
  $mail->SMTPAuth   = $__smtp['auth'];
  $mail->SMTPSecure = $__smtp['secure'];
  $mail->Port       = $__smtp['port'];
  $mail->CharSet    = $__smtp['charset'];
  $mail->Username   = $__smtp['username'];
  $mail->Password   = $__smtp['password'];
  $mail->AddReplyTo($__smtp['addreply'], $__smtp['from']);
  $to_array = explode(',', $to);
  foreach ($to_array as $to){
   $mail->AddAddress($to);
  }
  $mail->SetFrom($__smtp['addreply'], $__smtp['from']);
  $mail->Subject = htmlspecialchars($subject);
  $mail->MsgHTML($content);
<!-- /*
  if ($_FILES['file']['name'][0] !== '') {
    $file_ary = reArrayFiles($_FILES['file']);

    foreach ($file_ary as $file) {
        $mail->AddAttachment($file['tmp_name'],$file['name']);
    }
  }
*/ -->
  
  $mail->Send(); echo('success');
} catch (phpmailerException $e) {
  echo $e->errorMessage();
} catch (Exception $e) {
  echo $e->getMessage();
}
}

<!-- /*
function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

?>
*/ -->