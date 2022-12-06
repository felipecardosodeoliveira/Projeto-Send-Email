<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class Mensagem {

    private $para = null;

    private $assunto = null;

    private $mensagem = null;

    public function __get($atributo) {

        return $this->$atributo;

    }
    public function __set($atributo, $valor) {

        $this->$atributo = $valor;

    }

    public function mensagem_valida() {

        if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {

            return false;

        } else {

            return true;
            
        }

    }

}

$m = new Mensagem();

$m->__set('para', $_POST['para']);
$m->__set('assunto', $_POST['assunto']);
$m->__set('mensagem', $_POST['mensagem']);


if (!$m->mensagem_valida()) {
    echo "Erro";
}


//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'segredo';                     //SMTP username
    $mail->Password   = 'segredo';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('segredo', 'Felipe');
    $mail->addAddress($m->__get('para'));     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $m->__get('assunto');
    $mail->Body    = $m->__get('mensagem');
    $mail->AltBody = 'É necessário um client que suporte html';

    $mail->send();
    echo 'E-mail enviado com sucesso!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}