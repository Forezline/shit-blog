<?php
function sendResetEmail($to, $reset_link): void
{
    $subject = "Скидання паролю для вашого акаунта";
    $message = "Щоб скинути пароль, натисніть на наступне посилання: $reset_link";
    $headers = "From: no-reply@yourdomain.com";

    mail($to, $subject, $message, $headers);
}
