<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	
	$email = isset($_POST["email"]) && isset($_POST["email"]) != ""?$_POST["email"]:"";
	
	$cmd_busca_email = $pdo-> prepare("SELECT COUNT(*) AS 'quantidade' FROM tb_usuarios WHERE email = :email");
	$cmd_busca_email->bindValue(":email",$email);
	$cmd_busca_email->execute();
	$cmd_busca_email = $cmd_busca_email->fetch();
	$email_existente =  $cmd_busca_email['quantidade'];
	
	if($email_existente){
		$cmd_busca_email = $pdo-> prepare("UPDATE tb_usuarios SET senha = :senha WHERE email = :email");
		$senha = generateRandomString();
		$senha_banco = md5($senha);
		$cmd_busca_email->bindValue(":email",$email);
		$cmd_busca_email->bindValue(":senha",$senha_banco);
		
		if($cmd_busca_email->execute()){
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Host = 'smtp.gmail.com';
			$mail->Port = '465';
			$mail->isHTML();
			$mail->Username= 'pawsapp2017@gmail.com';
			$mail->Password = 'ESSAPORRADESENHA';
			$mail->SetFrom('pawsapp2017@gmail.com');
			$mail->Subject = 'Sua senha da PawsApp foi alterada com sucesso, Veja aqui.';
			$mail->Body = 'Essa é a sua nova senha: '.$senha.'. Está senha é provisória, recomendamos que troque a sua senha assim que entrar na PawsApp.';
			$mail->AddAddress($email);
			$mail->Send();
			echo "Sua senha foi alterada, verifique seu email.";
		}else{
			echo "Tente novamente.";
		}
	}else{
		echo "Email não existente.";
	}
?>