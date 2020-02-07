<?php

	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["motivo"]) && isset($_POST["codPostagem"])){//Se o usuário estiver realmente logado e o código da postagem estiver informado:
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_postagem = $_POST["codPostagem"];
		$motivo = $_POST["motivo"];
		
		//Verifica se o usuário já denunciou antes
		$busca_denuncia_usuario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codigoDenunciado = :codPostagem AND codDenunciador = :codusuario AND tipoDenunciado = 'postagem'");
		$busca_denuncia_usuario->bindParam(":codusuario",$codigo_usuario_logado);
		$busca_denuncia_usuario->bindParam(":codPostagem",$codigo_postagem);
		$busca_denuncia_usuario->execute();
		$busca_denuncia_usuario = $busca_denuncia_usuario->fetch();
		$denuncias_realizadas = $busca_denuncia_usuario["quantidade"];
		
		if($denuncias_realizadas < 1){//Se a busca retornar 0 é porque ele não denunciou então o mesmo pode denunciar
			$codigo_dono_postagem = $pdo ->prepare("SELECT codUsuario FROM tb_posts WHERE codigo = :codigo");
			$codigo_dono_postagem->bindValue(":codigo",$codigo_postagem);
			$codigo_dono_postagem->execute();
			$codigo_dono_postagem=$codigo_dono_postagem->fetch();
			$codigo_dono_postagem=$codigo_dono_postagem["codUsuario"];
			$grava_denuncia = $pdo->prepare("INSERT INTO tb_denuncias VALUES (NULL,:codusuario,:codDonoPostagem,'postagem',:motivo,:codPostagem)");
			$grava_denuncia->bindParam(":codusuario",$codigo_usuario_logado);
			$grava_denuncia->bindParam(":codDonoPostagem",$codigo_dono_postagem);
			$grava_denuncia->bindParam(":codPostagem",$codigo_postagem);
			$grava_denuncia->bindParam(":motivo",$motivo);
			if($grava_denuncia->execute()){
				echo "Denunciado";
			}else{
				echo "Não denunciado";
			}
		}else{//Se ele já denunciou antes:
			echo "Usuário já denunciou postagem anteriormente";
		}
		
	}else{//Se não estiver logado ou o código da postagem não tiver sido informado:
		echo "Informações do usuário inválidas ou código da postagem não informado";
		exit;//Encerra o funcionamento da página atual
	}
?>