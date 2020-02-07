<?php

	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoComentarioAlvo"]) && isset($_POST["motivoDenuncia"])){//Se o usuário estiver realmente logado e o código do comentário estiver informado:
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_comentario = $_POST["codigoComentarioAlvo"];
		$motivo = $_POST["motivoDenuncia"];
		
		//Verifica se o usuário já denunciou antes
		$busca_denuncia_comentario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codigoDenunciado = :codComentario AND codDenunciador = :codUsuario AND tipoDenunciado = 'comentario'");
		$busca_denuncia_comentario->bindParam(":codUsuario",$codigo_usuario_logado);
		$busca_denuncia_comentario->bindParam(":codComentario",$codigo_comentario);
		$busca_denuncia_comentario->execute();
		$busca_denuncia_comentario = $busca_denuncia_comentario->fetch();
		$denuncias_realizadas = $busca_denuncia_comentario["quantidade"];
		if($denuncias_realizadas < 1){//Se a busca retornar 0 é porque ele não denunciou então o mesmo pode denunciar
			$codigo_dono=$pdo->prepare("SELECT codUsuario FROM tb_comentarios WHERE codigo = :codigo");
			$codigo_dono->bindValue(":codigo",$codigo_comentario);
			$codigo_dono->execute();
			$codigo_dono = $codigo_dono->fetch();
			$codigo_dono = $codigo_dono["codUsuario"];
			
			$grava_denuncia = $pdo->prepare("INSERT INTO tb_denuncias VALUES (NULL,:codUsuario,:donoComentario,'comentario',:motivo,:codComentario)");
			$grava_denuncia->bindParam(":codUsuario",$codigo_usuario_logado);
			$grava_denuncia->bindParam(":donoComentario",$codigo_dono);
			$grava_denuncia->bindParam(":codComentario",$codigo_comentario);
			$grava_denuncia->bindParam(":motivo",$motivo);
			if($grava_denuncia->execute()){
				echo "Denunciado";
			}else{
				echo "Não denunciado";
			}
		}else{//Se ele já denunciou antes:
			echo "Usuário já denunciou comentario anteriormente";
		}
		
		
	}else{//Se não estiver logado ou o código da postagem não tiver sido informado:
		//print_r($_POST["motivoDenuncia"]);
		print_r($_POST["motivoDenuncia"]);
		echo "Informações de comentári0 inválidas ou motivo não informado";
		exit;//Encerra o funcionamento da página atual
	}
?>