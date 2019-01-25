<?php
	session_start();
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){
		
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		
		$esta_logado = true;
			
	}else{
		
		$esta_logado = false;
	
	}



	require("connection.php");
	
	if(!isset($_GET["email"])){
		echo "0";
	}else{
		$where_codigo = $esta_logado?"and codigo <> ".$codigo_usuario_logado:"";
		$email = $_GET["email"];
		$busca_disponibilidade = $pdo->prepare("SELECT COUNT(*) as disponibilidade FROM tb_usuarios WHERE email = :email $where_codigo");
		$busca_disponibilidade->bindParam(":email",$email);
		$busca_disponibilidade->execute();
		$busca_disponibilidade = $busca_disponibilidade->fetch();
		$retorno = $busca_disponibilidade["disponibilidade"] < 1?1:0;
		echo $retorno;
	}
?>