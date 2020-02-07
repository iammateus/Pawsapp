<?php

	session_start();//Começa a sessão

	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes

	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
	$foto_usuario_logado = $informacoes_do_usuario["foto"];

  $usuario2 = !isset($_POST["usuario2"]) || $_POST["usuario2"] == ""?0:$_POST["usuario2"];

  //Busca quantos favoritos essa busca alcançará
	$busca_quantidade_mensagens = $pdo->prepare("SELECT COUNT(*) quantidade FROM tb_mensagens WHERE ( id_de = :logado and id_para = :segundo ) or ( id_de = :segundoII and id_para = :logadoII )");
  $busca_quantidade_mensagens->bindValue(":logado",$codigo_usuario_logado);
  $busca_quantidade_mensagens->bindValue(":segundo",$usuario2);
  $busca_quantidade_mensagens->bindValue(":segundoII",$usuario2);
  $busca_quantidade_mensagens->bindValue(":logadoII",$codigo_usuario_logado);
	$busca_quantidade_mensagens->execute();
	$busca_quantidade_mensagens = $busca_quantidade_mensagens->fetch();
	$busca_quantidade_mensagens = $busca_quantidade_mensagens["quantidade"];

	if($busca_quantidade_mensagens < 1){//Se está no limite 0 e não há favoritos é porque a pessoa não tem favoritos

		echo "Essa conversa não tem mensagens.";

	}else{
    $busca_mensagens = $pdo->prepare("SELECT * FROM tb_mensagens WHERE ( id_de = :logado and id_para = :segundo ) or ( id_de = :segundoII and id_para = :logadoII ) ORDER BY time");
    $busca_mensagens->bindValue(":logado",$codigo_usuario_logado);
    $busca_mensagens->bindValue(":segundo",$usuario2);
    $busca_mensagens->bindValue(":segundoII",$usuario2);
    $busca_mensagens->bindValue(":logadoII",$codigo_usuario_logado);
    $busca_mensagens->execute();
		while($linha = $busca_mensagens->fetch(PDO::FETCH_ASSOC)){
				$codigo = $linha["codigo"];
				$codigo_enviante = $linha["id_de"];
				$mensagem = $linha["mensagem"];
				$time = $linha["time"];
				$lido = $linha["lido"];

				$classe_mensagem = $codigo_enviante == $codigo_usuario_logado?"enviada":"recebida";

				echo "<div mensagem='$codigo' class='container_mensagem'>
									<p class='$classe_mensagem'>$mensagem</p>";
			  echo "</div>
							<span class='space'></span>";

    }

  }
?>
