<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoPostagem"]) && isset($_POST["conteudoComentario"])){//Se o usuário estiver realmente logado e os dados necessários informados
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_postagem = $_POST["codigoPostagem"];
		$comentario = $_POST["conteudoComentario"];
		$comentario = trim($comentario);
	 	if(verificaPalavroes($comentario)){
			echo "palavrao";
		}else{
			date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
			$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
			$cmd_envia_comentario = $pdo->prepare("INSERT INTO tb_comentarios VALUES (NULL,:codUsuario,:codPostagem,:conteudo,:dataAtual,'true');");
			$cmd_envia_comentario->bindParam(":codUsuario",$codigo_usuario_logado);
			$cmd_envia_comentario->bindParam(":codPostagem",$codigo_postagem);
			$cmd_envia_comentario->bindParam(":conteudo",$comentario);
			$cmd_envia_comentario->bindParam(":dataAtual",$data_atual);
			
			if($cmd_envia_comentario->execute()){
				echo "bem sucedido";//Envia retornode confirmação de registro
				$_SESSION["comentarios-feitos"] += 1;
				
				$cmd_busca_dono_postagem = $pdo->prepare("SELECT `codUsuario` FROM `tb_posts` WHERE codigo = :codPostagem");
				$cmd_busca_dono_postagem->bindParam(":codPostagem",$codigo_postagem);
				$cmd_busca_dono_postagem->execute();
				$cmd_busca_dono_postagem = $cmd_busca_dono_postagem->fetch();
				$codigo_dono_postagem = $cmd_busca_dono_postagem['codUsuario'];
				
				if($codigo_dono_postagem <> $codigo_usuario_logado){
					
					//Verifica quantos usuários comentaram no post
					$cmd_quantos_usuarios = $pdo->prepare("SELECT COUNT(DISTINCT codUsuario) quantidade FROM tb_comentarios WHERE codPostagem = :codPostagem and codUsuario <> :codigoDonoPostagem and codUsuario <> :codigoDonoComentario");
					$cmd_quantos_usuarios->bindParam(":codPostagem",$codigo_postagem);
					$cmd_quantos_usuarios->bindParam(":codigoDonoPostagem",$codigo_dono_postagem);
					$cmd_quantos_usuarios->bindParam(":codigoDonoComentario",$codigo_usuario_logado);
					$cmd_quantos_usuarios->execute();
					$cmd_quantos_usuarios = $cmd_quantos_usuarios->fetch();
					$numero_usuarios = $cmd_quantos_usuarios['quantidade'];
					
					//Pega nome do usuaário logado
					$nome_usuario_logado =  ucfirst(strtolower($informacoes_do_usuario["nome"]));
					
					if($numero_usuarios == 0 && $codigo_dono_postagem != $codigo_usuario_logado){
						$descricao = "$nome_usuario_logado comentou em sua publicação.";
						$cmd_grava_notificacao = $pdo->prepare("INSERT INTO tb_notificacoes VALUES (NULL,:donoPostagem,:codigoLogado,:codPostagem,'comentario',:descricao,0,:dataAtual)");
						$cmd_grava_notificacao->bindParam(":donoPostagem",$codigo_dono_postagem);
						$cmd_grava_notificacao->bindParam(":codigoLogado",$codigo_usuario_logado);
						$cmd_grava_notificacao->bindParam(":codPostagem",$codigo_postagem);
						$cmd_grava_notificacao->bindParam(":descricao",$descricao);
						$cmd_grava_notificacao->bindParam(":dataAtual",$data_atual);
						$cmd_grava_notificacao->execute();
					}elseif($numero_usuarios > 0){
						$cmd_deleta_notificacao = $pdo -> prepare ("DELETE FROM tb_notificacoes WHERE codPost = :codPostagem and tipo = 'comentario'");
						$cmd_deleta_notificacao->bindParam(":codPostagem",$codigo_postagem);
						$cmd_deleta_notificacao->execute();
						$descricao = "$nome_usuario_logado e mais $numero_usuarios comentaram em sua publicação.";
						$cmd_grava_notificacao = $pdo->prepare("INSERT INTO tb_notificacoes VALUES (NULL,:donoPostagem,:codigoLogado,:codPostagem,'comentario',:descricao,0,:dataAtual)");
						$cmd_grava_notificacao->bindParam(":donoPostagem",$codigo_dono_postagem);
						$cmd_grava_notificacao->bindParam(":codigoLogado",$codigo_usuario_logado);
						$cmd_grava_notificacao->bindParam(":codPostagem",$codigo_postagem);
						$cmd_grava_notificacao->bindParam(":descricao",$descricao);
						$cmd_grava_notificacao->bindParam(":dataAtual",$data_atual);
						$cmd_grava_notificacao->execute();
					}				
						
				}
			}else{
				echo "algo de errado / erro";
			}

		}
		
		
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>