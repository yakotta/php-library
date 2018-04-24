<?php
namespace Yakotta;

class Email {
    // Sends emails from forms
    // Here is how to send email in HTML: https://stackoverflow.com/questions/11238953/send-html-in-email-via-php
    static public function send($to, $from, $subject, $message)
    {
        $from = "{$from["name"]} <{$from["email"]}>";
        
        $headers = implode("\r\n", [
            "To: {$to["name"]} <{$to["email"]}>",
            "From: $from",
            "Reply-To: $from",
            "MIME-Version: 1.0",
            "Content-Type: text/html; charset=UTF-8",
        ]);
            
        return mail($to["email"], $subject, $message, $headers);
    }
}