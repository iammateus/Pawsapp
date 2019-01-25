<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado e os dados necessários informados
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		
		$grava_notificacoes_lidas = $pdo->prepare("UPDATE tb_notificacoes SET foiLido = 1 WHERE codRecebedor = :codigoUsuario");
		$grava_notificacoes_lidas->bindParam(":codigoUsuario",$codigo_usuario_logado);
		
		if($grava_notificacoes_lidas->execute()){
			echo "marcado como lido";
		}else{
			echo "algo de errado";
		}
		
		
		
		
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>