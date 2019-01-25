<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codComentario"])){//Se o usuário estiver realmente logado e o codigo do comentario informado
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_comentario = $_POST["codComentario"];
		
		//verifica se o usuário realmente é dono do post
		$verifica_comentario = $pdo->prepare("SELECT COUNT(*) existencia FROM tb_comentarios WHERE codigo = :codComentario  and codUsuario =:codigoLogado");
		$verifica_comentario->bindParam(":codigoLogado",$codigo_usuario_logado);
		$verifica_comentario->bindParam(":codComentario",$codigo_comentario);
		$verifica_comentario->execute();
		$verifica_comentario = $verifica_comentario->fetch();
		$verifica_comentario = $verifica_comentario["existencia"];
				
		if($verifica_comentario > 0){//Se a busca retornar 1 é porque o usuário é o dono e assim ele consegue excluir permanentemente a postagem
			$deleta_post = $pdo->prepare("DELETE FROM tb_comentarios WHERE codigo = :codComentario  and codUsuario = :codigoLogado");
			$deleta_post->bindParam(":codigoLogado",$codigo_usuario_logado);
			$deleta_post->bindParam(":codComentario",$codigo_comentario);
			if($deleta_post->execute()){//Se o usuário conseguir excluir
				echo "Excluido";
			}else{
				echo "Post não excluido";
			}
		}
		
	}else{//Se o usuário não estiver logado ou o codigo do comentário
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>