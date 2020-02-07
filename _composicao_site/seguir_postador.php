<?php

	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoUsuarioAlvo"])){//Se o usuário estiver realmente logado
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_usuario_alvo = $_POST["codigoUsuarioAlvo"];
		
		$tipo = $_POST['tipo'];
		
		
		$verifica_seguir = $pdo->prepare("SELECT COUNT(*) existencia FROM tb_seguidor_seguido WHERE codSeguidor = :codigoLogado and codSeguido = :codigoSeguido");
		$verifica_seguir->bindParam(":codigoLogado",$codigo_usuario_logado);
		$verifica_seguir->bindParam(":codigoSeguido",$codigo_usuario_alvo);
		$verifica_seguir->execute();
		$verifica_seguir = $verifica_seguir->fetch();
		$verifica_seguir = $verifica_seguir["existencia"];
		
		if($verifica_seguir < 1 && $tipo == 1){
			$grava_seguir = $pdo->prepare("INSERT INTO tb_seguidor_seguido (codigo, codSeguidor, codSeguido) VALUES (NULL, :codigoLogado, :codigoSeguido);");
			$grava_seguir->bindParam(":codigoLogado",$codigo_usuario_logado);
			$grava_seguir->bindParam(":codigoSeguido",$codigo_usuario_alvo);
			if($grava_seguir->execute()){
				echo "Seguindo";
			}
		}else{
			$exclui_seguir = $pdo->prepare("DELETE FROM `tb_seguidor_seguido` WHERE codSeguidor = :codigoLogado and codSeguido = :codigoSeguido;");
			$exclui_seguir->bindParam(":codigoLogado",$codigo_usuario_logado);
			$exclui_seguir->bindParam(":codigoSeguido",$codigo_usuario_alvo);
			if($exclui_seguir->execute()){
				echo "Não seguindo";
			}
		}
		
	}else{//Se acontecer algo errado
		session_destroy();//A sessão é destruida
		exit;//Encerra o funcionamento da página atual
	}
?>