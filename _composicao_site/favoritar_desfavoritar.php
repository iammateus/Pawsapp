<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codPostagem"])){
		require("connection.php");
		require("script-funcoes.php");
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_post = $_POST["codPostagem"];
		$buscafavorito = $pdo -> prepare("SELECT COUNT(*) AS quantidade FROM tb_favoritos WHERE codUsuario = $codigo_usuario_logado AND codPost = :codPostagem ;"); 
		$buscafavorito -> bindParam(":codPostagem", $codigo_post);
		$buscafavorito -> execute();
		$buscafavorito = $buscafavorito-> fetch();
		$buscafavorito = $buscafavorito['quantidade'];

		if($buscafavorito < 1) {
			$favoritar = $pdo -> prepare("INSERT INTO tb_favoritos VALUES (NULL, :codPost,:codUsuario);");	
			$favoritar-> bindParam(":codUsuario", $codigo_usuario_logado);
			$favoritar-> bindParam(":codPost", $codigo_post);
			if($favoritar-> execute()){
				
				echo "Adicionado aos favoritos";
				
				$cmd_busca_dono_postagem = $pdo->prepare("SELECT `codUsuario` FROM `tb_posts` WHERE codigo = :codPostagem");
				$cmd_busca_dono_postagem->bindParam(":codPostagem",$codigo_post);
				$cmd_busca_dono_postagem->execute();
				$cmd_busca_dono_postagem = $cmd_busca_dono_postagem->fetch();
				$codigo_dono_postagem = $cmd_busca_dono_postagem['codUsuario'];
				
				if($codigo_dono_postagem <> $codigo_usuario_logado){
					date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
					$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
					//Verifica quantos usuários comentaram no post
					$cmd_quantos_usuarios = $pdo->prepare("SELECT COUNT(DISTINCT codUsuario) quantidade FROM tb_favoritos WHERE codPost = :codPostagem and codUsuario <> :codigoDonoPostagem and codUsuario <> :codigoDonoFavorito");
					$cmd_quantos_usuarios->bindParam(":codPostagem",$codigo_post);
					$cmd_quantos_usuarios->bindParam(":codigoDonoPostagem",$codigo_dono_postagem);
					$cmd_quantos_usuarios->bindParam(":codigoDonoFavorito",$codigo_usuario_logado);
					$cmd_quantos_usuarios->execute();
					$cmd_quantos_usuarios = $cmd_quantos_usuarios->fetch();
					$numero_usuarios = $cmd_quantos_usuarios['quantidade'];
					
					//Pega nome do usuaário logado
					$nome_usuario_logado =  ucfirst(strtolower($informacoes_do_usuario["nome"]));
					
					if($numero_usuarios == 0 && $codigo_dono_postagem != $codigo_usuario_logado){
						$descricao = "$nome_usuario_logado favoritou sua publicação.";
						$cmd_grava_notificacao = $pdo->prepare("INSERT INTO tb_notificacoes VALUES (NULL,:donoPostagem,:codigoLogado,:codPostagem,'favorito',:descricao,0,:dataAtual)");
						$cmd_grava_notificacao->bindParam(":donoPostagem",$codigo_dono_postagem);
						$cmd_grava_notificacao->bindParam(":codigoLogado",$codigo_usuario_logado);
						$cmd_grava_notificacao->bindParam(":codPostagem",$codigo_post);
						$cmd_grava_notificacao->bindParam(":descricao",$descricao);
						$cmd_grava_notificacao->bindParam(":dataAtual",$data_atual);
						$cmd_grava_notificacao->execute();
					}elseif($numero_usuarios > 0){
						$cmd_deleta_notificacao = $pdo -> prepare ("DELETE FROM tb_notificacoes WHERE codPost = :codPostagem and tipo = 'favorito'");
						$cmd_deleta_notificacao->bindParam(":codPostagem",$codigo_post);
						$cmd_deleta_notificacao->execute();
						$descricao = "$nome_usuario_logado e mais $numero_usuarios favoritaram sua publicação.";
						$cmd_grava_notificacao = $pdo->prepare("INSERT INTO tb_notificacoes VALUES (NULL,:donoPostagem,:codigoLogado,:codPostagem,'favorito',:descricao,0,:dataAtual)");
						$cmd_grava_notificacao->bindParam(":donoPostagem",$codigo_dono_postagem);
						$cmd_grava_notificacao->bindParam(":codigoLogado",$codigo_usuario_logado);
						$cmd_grava_notificacao->bindParam(":codPostagem",$codigo_post);
						$cmd_grava_notificacao->bindParam(":descricao",$descricao);
						$cmd_grava_notificacao->bindParam(":dataAtual",$data_atual);
						$cmd_grava_notificacao->execute();
					}				
				}
				
				
			}else{
				echo "Falha ao adicionar aos favoritos";
			}
		} else {
			$desfavoritar = $pdo -> prepare("DELETE FROM `tb_favoritos` WHERE codUsuario = $codigo_usuario_logado AND codPost = :codPost;");
			$desfavoritar-> bindParam(":codPost", $codigo_post);
			if ($desfavoritar-> execute()) {
				echo "Removido dos favoritos";	
			} else {
				echo "Falha ao remover";
			}
		}
	}



?>