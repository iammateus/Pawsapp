<?php

	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoUsuarioAlvo"])){//Se o usuário estiver realmente logado
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_usuario_alvo = $_POST["codigoUsuarioAlvo"];
		
		$exclui_seguir = $pdo->prepare("DELETE FROM `tb_bloqueio_usuario` WHERE codUsuario = :codigoLogado and codBloqueado = :codigoBloqueado;");
		$exclui_seguir->bindParam(":codigoLogado",$codigo_usuario_logado);
		$exclui_seguir->bindParam(":codigoBloqueado",$codigo_usuario_alvo);
		if($exclui_seguir->execute()){
			echo "Desbloqueado";
		}else{
			echo "Erro";
		}
	
	}else{//Se acontecer algo errado
		session_destroy();//A sessão é destruida
		exit;//Encerra o funcionamento da página atual
	}
?>