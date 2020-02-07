<?php
	header("Content-Type: application/json;charset=utf-8;");
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codPostagem"])){//Se o usuário estiver realmente logado
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_postagem = $_POST["codPostagem"];
		
		//Busca informações da postagem
		$cmd_informacoes_post = $pdo-> prepare("SELECT * FROM tb_posts WHERE codigo = :codigoPostagem;");
		$cmd_informacoes_post->bindParam(":codigoPostagem",$codigo_postagem);
		$cmd_informacoes_post->execute();
		$informacoes_postagem = $cmd_informacoes_post->fetch();
		
		//Coloca as informações buscadas em variáveis
		$titulo = $informacoes_postagem["nomeAnimal"];
		$tipo = $informacoes_postagem["tipoPost"];
		$especie = $informacoes_postagem["especieAnimal"];
		$raca = $informacoes_postagem["raca"];
		$cor = $informacoes_postagem["cor"];
		$sexo = $informacoes_postagem["sexoAnimal"];
		$contato = $informacoes_postagem["contato"];
		$localidade = $informacoes_postagem["localidade"];
		$media = $informacoes_postagem["imgPost"];
		$descricao = $informacoes_postagem["descricaoPost"];
		$porte = $informacoes_postagem["porteAnimal"];
		
		echo json_encode(array("titulo" => $titulo,
							   "tipo" => $tipo,
							   "especie" => $especie,
							   "raca" => $raca,
							   "cor" => $cor,
							   "sexo" => $sexo,
							   "contato" => $contato,
							   "localidade" => $localidade,
							   "media" => $media,
							   "descricao" => $descricao,
							   "porte" => $porte));
		
	}
	
?>