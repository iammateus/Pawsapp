<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoPostagem"])){//Se o usuário estiver realmente logado e o codigo da postagem informado
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_postagem = $_POST["codigoPostagem"];
		
		//verifica se o usuário realmente é dono do post
		$verifica_post = $pdo->prepare("SELECT COUNT(*) existencia FROM tb_posts WHERE codigo = :codPostagem  and codUsuario =:codigoLogado");
		$verifica_post->bindParam(":codigoLogado",$codigo_usuario_logado);
		$verifica_post->bindParam(":codPostagem",$codigo_postagem);
		$verifica_post->execute();
		$verifica_post = $verifica_post->fetch();
		$verifica_post = $verifica_post["existencia"];
				
		if($verifica_post > 0){//Se a busca retornar 1 é porque o usuário é o dono e assim ele consegue excluir permanentemente a postagem
			$deleta_post = $pdo->prepare("DELETE FROM tb_posts WHERE codigo = :codPostagem  and codUsuario = :codigoLogado");
			$deleta_post->bindParam(":codigoLogado",$codigo_usuario_logado);
			$deleta_post->bindParam(":codPostagem",$codigo_postagem);
			if($deleta_post->execute()){//Se o usuário conseguir excluir
				$deleta_notificacao = $pdo->prepare("DELETE FROM tb_notificacoes WHERE codPost = :codPostagem  and codRecebedor = :codigoLogado");
				$deleta_notificacao->bindParam(":codigoLogado",$codigo_usuario_logado);
				$deleta_notificacao->bindParam(":codPostagem",$codigo_postagem);
				$deleta_notificacao->execute();
				echo "Excluido";
			}else{
				echo "Post não excluido";
			}
		}
		
	}else{//Se o usuário não estiver logado ou o codigo da postagem não foi informado
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>