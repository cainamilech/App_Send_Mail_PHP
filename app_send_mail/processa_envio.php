<?php

    //ADICIONAR SENHA DO EMAIL LINHA 67.
	
	//importação dos arquivos da biblioteca externa que tem a função de enviar o email
	require "./biblioteca/PHPMailer/Exception.php";
	require "./biblioteca/PHPMailer/OAuth.php";
	require "./biblioteca/PHPMailer/PHPMailer.php";
	require "./biblioteca/PHPMailer/POP3.php";
	require "./biblioteca/PHPMailer/SMTP.php";

	//selecionar os namespaces utilizados dentro dos arquivos para extrair suas respectivas classes 
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

  
  class Mensagem {
  	private $para = null;
  	private $assunto = null;
  	private $Mensagem = null;
    public $status = array('codigo_status' => null, 'descricao_status' => '');

  	public function __get($atributo) {
  		return $this->$atributo;
  	}

  	public function __set($atributo, $valor) {
  		$this->$atributo = $valor;
  	}

  	public function mensagemValida(){
  		//verificar se os campos dos atributos estao preenchidos
  		if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
  			return false;
  		}

  		return true;
  	}

  }
  //criando o objeto
  $mensagem = new Mensagem();
  //setar o valor dos campos, via post
  $mensagem->__set('para', $_POST['para']);
  $mensagem->__set('assunto', $_POST['assunto']);
  $mensagem->__set('mensagem', $_POST['mensagem']);

  //executar metodo, para tomar decisao apartir do retorno
  if (!$mensagem->mensagemValida()) {
  	echo 'Mensagem não é valida';
    header('Location: index.php'); //se tentar acessar essa url sem passar pelo index, será redirecionado.

  	//die(); //script morre, tudo sera descartado daqui pra frente (comentei depois que adicionei o header.)
  }

  //caso contrario, temos mensagem válida, entao continuamos com esse script, copiado da biblioteca
  $mail = new PHPMailer(true); //criação do objeto baseado na classse php mailer

try {
    //Server settings
    $mail->SMTPDebug = false; //SMTP::DEBUG_SERVER; (isso era o codigo da lib, que mostrava toda codificação depois que enviava o email, troquei pra false, pra nao mostrar mais nada)                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'caina_milech@outlook.com';                     //SMTP username
    $mail->Password   = 'ADICIONAR SENHA';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('caina_milech@outlook.com', 'Cainã Remetente');
    $mail->addAddress($mensagem->__get('para')); //recupera o email do formulario para destino

    //$mail->addAddress('ellen@example.com');               //se quiser adicionar outros destinarios
    //$mail->addReplyTo('info@example.com', 'Information'); para deixar um endereço de resposta padrao
    //$mail->addCC('cc@example.com'); se quiser mandar copia
    //$mail->addBCC('bcc@example.com'); se quiser mandar copia oculta

    //Attachments (anexos)
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto'); //recupera o assunto do formulario

    $mail->Body = $mensagem->__get('mensagem'); //recupera a mensagem do formulario

    $mail->AltBody = 'É necessario utilizar um client de email que suporte renderização de tags html'; //caso o client email nao tenha suporte a renderização de tags html

    $mail->send();

    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'Email enviado com sucesso!'; //dando certo, esses atributos sao gerados

} catch (Exception $e) {

    $mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = "Não foi possível enviar este e-mail. Detalhes do erro: {$mail->ErrorInfo}";

    //nao dando certo, esses atributos sao gerados.
}
?>


<html>
    <head>
        <meta charset="utf-8" />
        <title>App Mail Send</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>

    <body>

        <div class="container">
            <div class="py-3 text-center">
                <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
                <h2>Send Mail</h2>
                <p class="lead">Seu app de envio de e-mails particular!</p>
            </div>

            <div class="row">
                <div class="col-md-12">
                    
                    <? if($mensagem->status['codigo_status'] == 1){ ?>

                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <? } ?>

                    <? if($mensagem->status['codigo_status'] == 2){ ?>

                        <div class="container">
                            <h1 class="display-4 text-danger">Ops!</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <? } ?>

                </div>
            </div>
        </div>

    </body>
    </html>




  
