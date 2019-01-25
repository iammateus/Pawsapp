<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoUsuarioAlvo"])){//Se o usuário estiver realmente logado
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_usuario_alvo = $_POST["codigoUsuarioAlvo"];
		
		$grava_bloqueio = $pdo->prepare("INSERT INTO tb_bloqueio_usuario VALUES (NULL, :codigoLogado, :codigoAlvo);");
		$grava_bloqueio->bindParam(":codigoLogado",$codigo_usuario_logado);
		$grava_bloqueio->bindParam(":codigoAlvo",$codigo_usuario_alvo);
		if($grava_bloqueio->execute()){
			echo "Bloqueado";
		}else{
			echo "Erro ao executar";
		}
		
	}
?>