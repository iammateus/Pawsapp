<?php
	session_start();//Começa a sessão

	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes

	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
	$foto_usuario_logado = $informacoes_do_usuario["foto"];

  $usuario2 = !isset($_POST["usuario2"]) || $_POST["usuario2"] == "0"?0:$_POST["usuario2"];
	$mensagem = !isset($_POST["mensagem"]) || $_POST["mensagem"] == "00"?0:$_POST["mensagem"];

  //Busca se usuário foi bloqueado
	$busca_quantidade_mensagens = $pdo->prepare("SELECT COUNT(*) quantidade FROM tb_bloqueio_usuario WHERE ( codUsuario = :logado and codBloqueado = :segundo ) or ( codUsuario = :segundoII and codBloqueado = :logadoII )");
  $busca_quantidade_mensagens->bindValue(":logado",$codigo_usuario_logado);
  $busca_quantidade_mensagens->bindValue(":segundo",$usuario2);
  $busca_quantidade_mensagens->bindValue(":segundoII",$usuario2);
  $busca_quantidade_mensagens->bindValue(":logadoII",$codigo_usuario_logado);
	$busca_quantidade_mensagens->execute();
	$busca_quantidade_mensagens = $busca_quantidade_mensagens->fetch();
	$busca_quantidade_mensagens = $busca_quantidade_mensagens["quantidade"];

	if($busca_quantidade_mensagens > 0){//Se está no limite 0 e não há favoritos é porque a pessoa não tem favoritos

		echo "Você não pode mandar mensagem para esse usuário.";

	}else{
		$horario = time();
    $enviar_mensagem = $pdo->prepare("INSERT INTO tb_mensagens VALUES (NULL,:logado,:segundo,:mensagem,:horario,0)");
    $enviar_mensagem->bindValue(":logado",$codigo_usuario_logado);
    $enviar_mensagem->bindValue(":segundo",$usuario2);
		$enviar_mensagem->bindValue(":mensagem",$mensagem);
		$enviar_mensagem->bindValue(":horario",$horario);
    $enviar_mensagem->execute();

		echo "Mensagem enviada";
  }
?>
