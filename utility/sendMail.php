<?php
/**
 * Created by PhpStorm.
 * User: zhalnin
 * Date: 14/08/14
 * Time: 17:45
 */

/**
 * Функция отправки сообщения
 * @param $from
 * @param $to
 * @param $subject
 * @param $body
 */
function sendMail($from, $to, $subject, $body){
    $headers = "MIME-Version: 1.0 \n";
    $headers .= "From: <".$from.">\n";
    $headers .= "Reply-To: <".$from."> \n";
    $headers .= "Content-Type: text/html; charset=UTF-8 \n";

    $subject = '=?utf-8?B?'.base64_encode($subject).'?=';

    mail($to, $subject, $body, $headers);
}
?>