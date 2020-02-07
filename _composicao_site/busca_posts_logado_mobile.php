<?php
	session_start();//Começa a sessão
	
	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes
	
	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
	//Pega as informações de limite do select
	$comeco = !isset($_SESSION["comecoBusca"])?"erro":$_SESSION["comecoBusca"];	
	
	date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
	$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
		
	//Pega as informações de busca do post
	$tipoPost = !isset($_POST["tipoPost"]) || $_POST["tipoPost"] == ""?"naoInformado":$_POST["tipoPost"];
	$especie = !isset($_POST["especie"]) || $_POST["especie"] == ""?"naoInformado":$_POST["especie"];
	$porte = !isset($_POST["porte"]) || $_POST["porte"] == ""?"naoInformado":$_POST["porte"];
	$cor = !isset($_POST["cor"]) || $_POST["cor"] == ""?"naoInformado":$_POST["cor"];
	$sexo = !isset($_POST["sexo"]) || $_POST["sexo"] == ""?"naoInformado":$_POST["sexo"];
	//echo "Tipo: $tipoPost <br /> Especie: $especie <br />Porte: $porte <br />Cor: $cor <br />Sexo: $sexo <br />";
	//echo "Começo: $comeco <br />";
	if($tipoPost == "favoritos"){
		
		$busca_posts = $pdo->prepare("
		SELECT
			F.codigo as 'cod',
			P.codigo codigoPost,
			P.tipoPost,
			P.descricaoPost,
			P.imgPost,
			P.nomeAnimal,
			P.dataPostagem,
			U.codigo codUsuario,
			U.nome nomeUsuario,
			U.foto
		FROM tb_favoritos F
		INNER JOIN tb_posts P
		ON P.codigo = F.codPost
		INNER JOIN tb_usuarios U
		ON U.codigo = P.codUsuario
		WHERE F.codUsuario = $codigo_usuario_logado
		ORDER BY P.dataPostagem DESC
		LIMIT $comeco, 3;");
		
	}else if($tipoPost == "usuario"){
		
		$cod_dono_perfil = $_POST["donoPerfil"]; 
		
		$busca_posts = $pdo->prepare("
		SELECT
			P.codigo codigoPost,
			P.tipoPost,
			P.descricaoPost,
			P.imgPost,
			P.nomeAnimal,
			P.dataPostagem,
			U.codigo codUsuario,
			U.nome nomeUsuario,
			U.foto
		FROM tb_posts P
		INNER JOIN tb_usuarios U
		ON U.codigo = P.codUsuario
		WHERE P.codUsuario = :codigoUsuario
		ORDER BY P.dataPostagem DESC
		LIMIT $comeco, 3");
		
		$busca_posts->bindParam(":codigoUsuario",$cod_dono_perfil);
	
	}else{
	
		//Configuração do select costumizado no banco abaixo----------------------------------------------------------
		$usa_and = $tipoPost <> "naoInformado" || $especie <> "naoInformado" || $porte <> "naoInformado" || $cor <> "naoInformado" || $sexo <> "naoInformado"?"AND":"";
			
		if(($tipoPost <> "naoInformado") and ($especie <> "naoInformado" || $porte <> "naoInformado" || $cor <> "naoInformado" || $sexo <> "naoInformado")){
			$especifica_post = "P.tipoPost = :tipoPost AND";
		}else if($tipoPost <> "naoInformado"){
			$especifica_post = "P.tipoPost = :tipoPost";
		}else{
			$especifica_post = "";
		}
		
		if(($especie <> "naoInformado") and ($porte <> "naoInformado" || $cor <> "naoInformado" || $sexo <> "naoInformado")){
			$especifica_especie = "P.especieAnimal = :especie AND";
		}else if($especie <> "naoInformado"){
			$especifica_especie = "P.especieAnimal = :especie";
		}else{
			$especifica_especie = "";
		}
		
		if(($porte <> "naoInformado") and ($cor <> "naoInformado" || $sexo <> "naoInformado")){
			$especifica_porte = "P.porteAnimal = :porte AND";
		}else if($porte <> "naoInformado"){
			$especifica_porte = "P.porteAnimal = :porte";
		}else{
			$especifica_porte = "";
		}
		
		if($cor <> "naoInformado" and $sexo <> "naoInformado"){
			$especifica_cor = "P.cor = :cor AND";
		}else if($cor <> "naoInformado"){
			$especifica_cor = "P.cor = :cor";
		}else{
			$especifica_cor = "";
		}
		
		$especifica_sexo = $sexo <> "naoInformado"?"P.sexoAnimal = :sexo":"";
		
		$busca_posts = $pdo->prepare("
		SELECT 
			P.codigo codigoPost,
			P.tipoPost,
			P.descricaoPost,
			P.imgPost,
			P.nomeAnimal,
			P.dataPostagem,
			U.codigo codUsuario,
			U.nome nomeUsuario,
			U.foto
		FROM tb_posts P
		INNER JOIN tb_usuarios U
		ON U.codigo = P.codUsuario
		WHERE P.ativo = 'true'
		$usa_and
		$especifica_post
		$especifica_especie
		$especifica_porte
		$especifica_cor
		$especifica_sexo
		ORDER BY P.dataPostagem DESC
		LIMIT $comeco, 3;");
		
		if($tipoPost <> "naoInformado"){
			$busca_posts->bindParam(":tipoPost",$tipoPost);
		}
		if($especie <> "naoInformado"){
			$busca_posts->bindParam(":especie",$especie);
		}
		if($porte <> "naoInformado"){
			$busca_posts->bindParam(":porte",$porte);
		}
		if($cor <> "naoInformado"){
			$busca_posts->bindParam(":cor",$cor);
		}
		if($sexo <> "naoInformado"){
			$busca_posts->bindParam(":sexo",$sexo);
		} 
		
	}
	$busca_posts->execute();
	$_SESSION["comecoBusca"]+=(int)$busca_posts->rowCount();
	
	//Configuração do select costumizado no banco acima----------------------------------------------------------	
	
	
	
	while($linha = $busca_posts->fetch(PDO::FETCH_ASSOC)){
		
		$codigo_post = $linha["codigoPost"];//Pega código do post
    	$tipo_post = $linha["tipoPost"];//Pega o tipo do post
		$titulo_post = $linha["nomeAnimal"];//Pega o tipo do post
		$titulo_post = wordwrap($titulo_post, 15, "\n", true);
    	$descricao_post = $linha["descricaoPost"];//Descrição do post
		$descricao_post = wordwrap($descricao_post, 15, "\n", true);
		$media_post = $linha["imgPost"];//Pega a media do post
    	$codigo_usuario = $linha["codUsuario"];//Pega código do usuário
    	$nome_usuario = $linha["nomeUsuario"];//pega nome do usuário
    	$foto_usuario = $linha['foto'];//Pega a foto do usuário e guarda em uma variável
		$extencao_media = pegaExtensaoArquivo($media_post);//Pega a extenção da media 
		
		//Busca se o usuário visualizando denunciou a postagem atual
		$busca_denuncia_usuario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codElemento = :codPostagem AND codDenunciador = :codusuario AND tipoDenunciado = 'postagem'");
		$busca_denuncia_usuario->bindParam(":codusuario",$codigo_usuario_logado);
		$busca_denuncia_usuario->bindParam(":codPostagem",$codigo_post);
		$busca_denuncia_usuario->execute();
		$busca_denuncia_usuario = $busca_denuncia_usuario->fetch();
		$denuncias_realizadas = $busca_denuncia_usuario["quantidade"];
		
		$busca_bloqueio= $pdo->query("SELECT COUNT(*) quantidade FROM `tb_bloqueio_usuario` WHERE (codUsuario = $codigo_usuario_logado AND codBloqueado = $codigo_usuario) or (codUsuario = $codigo_usuario AND codBloqueado = $codigo_usuario_logado)");
		$busca_bloqueio->execute();
		$busca_bloqueio = $busca_bloqueio->fetch();
		$bloqueios = $busca_bloqueio["quantidade"];
		
		if($denuncias_realizadas < 1 && $bloqueios < 1){//Se o usuário não denunciou a postagem ela será apresentada
			
			//Calculo de tempo de postagem
			$data_postagem = $linha["dataPostagem"];//Pega a data do post
			$tempo_de_postagem = buscaTempo($data_postagem);
			
			//Abaixo pega tipo do post para saber qual cor o post e o menu
			if($tipo_post == "casual"){
				$classe_cor_post = "casual";
				$tipo_post = "Casual";
			}elseif($tipo_post == "doacao"){
				$classe_cor_post = "doacao";
				$tipo_post = "Doação";
			}elseif($tipo_post == "perdido"){
				$classe_cor_post = "perdido";
				$tipo_post = "Perdido";
			}else{
				$classe_cor_post = "encontrado";
				$tipo_post = "Encontrado";
			}
			
			//Busca total de comentários
			$busca_total_comentarios = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_comentarios` WHERE codPostagem = :codigoPost");
			$busca_total_comentarios->bindParam(":codigoPost",$codigo_post);
			$busca_total_comentarios->execute();
			$quantidade_de_comentarios = $busca_total_comentarios->fetch();//Coloca as informações em um vetor
			$quantidade_de_comentarios = $quantidade_de_comentarios["quantidade"];
			
			//Busca se o usuário já favoritou o post ou não
			$busca_favoritado = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_favoritos` WHERE codUsuario = :codigoUsuario and codPost = :codigoPost");
			$busca_favoritado->bindParam(":codigoUsuario",$codigo_usuario_logado);
			$busca_favoritado->bindParam(":codigoPost",$codigo_post);
			$busca_favoritado->execute();
			$favoritado = $busca_favoritado->fetch();//Coloca as informações em um vetor
			$favoritado = $favoritado["quantidade"] > 0?true:false;
			
			//Busca se o usuário logado segue o postador
			$verifica_seguir = $pdo->prepare("SELECT COUNT(*) existencia FROM tb_seguidor_seguido WHERE codSeguidor = :codigoLogado and codSeguido = :codigoSeguido");
			$verifica_seguir->bindParam(":codigoLogado",$codigo_usuario_logado);
			$verifica_seguir->bindParam(":codigoSeguido",$codigo_usuario);
			$verifica_seguir->execute();
			$verifica_seguir = $verifica_seguir->fetch();
			$verifica_seguir = $verifica_seguir["existencia"];
			$ja_seguiu = $verifica_seguir > 0?true:false;
			
			$classe_foto_usuario = $codigo_usuario == $codigo_usuario_logado?"foto-usuario-logado":"";
			
			//Configuração da aprensetação do post
			
			echo "<div class='posts'>
					  <div class='menu-postagem $classe_cor_post'>
					  	  <div class='ponteiro-postagem'></div>
						  <h2>Menu</h2>
						  <ul>";
							  if($codigo_usuario == $codigo_usuario_logado){
								  echo"	<a href='post.php?codigo=$codigo_post&editar=true'><li>Editar Postagem</li></a>
								  		<li id='excluir-postagem' onClick=confirmacao(this,'excluir',$codigo_post)>Excluir Postagem</li>";
							  }else{
								   echo"<li onClick=confirmacao(this,'denunciar',$codigo_post)>Denúnciar Postagem</li>";
								   echo"<li onClick=confirmacao(this,'bloquear',$codigo_usuario)>Bloquear Usuario</li>";
								   if(!$ja_seguiu){
										echo "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Seguir Usuário</li>"; 
								   }else{
										echo "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Não Seguir</li>";
								   }
							  }
							  echo "<li onClick=confirmacao(this,'ocultar',0)>Ocultar Postagem</li>";
						  
					echo "</ul>
					  </div>
					  <img class='abrir-menu-postagem' src='_imgs/abrir-menu-postagens.png' onClick='abrirFecharMenupostagem(this)' /> 
					  
					  <div class='container-detalhes-post'>
						  <header class='container-detalhes-dono $classe_cor_post'>
							  <a href='perfil.php?codigo=$codigo_usuario'><img class='fotos-perfil-feed-postagens $classe_foto_usuario' src='$foto_usuario' />
							  <span class='nome-user'>$nome_usuario</span></a>
							  <h1>$tipo_post</h1>
							  <span class='tempo-postagem'>$tempo_de_postagem</span>
						  </header>
						  <div class='container-titulo-descricao'>
							  <h2>$titulo_post</h1>
							  <p>$descricao_post</p>
						  </div>
					  </div>
					  
					  <div class='container-foto-video-post'>";
						  if($extencao_media == ".jpg" || $extencao_media == ".png"){//Se a extenção for de imagem:
						        echo "<a href='post.php?codigo=$codigo_post'><img class='imagens-posts' src='$media_post' /></a>";
			           	  }elseif($extencao_media == ".mp4"){//Se a extenção for de vídeo:
				                echo"<video class='video-posts' controls>
						  		          <source src='$media_post' type='video/mp4' />
								          Desculpe, mas não foi possível carregar o áudio.
							         </video>";
			              }elseif($extencao_media == ".gif"){//Se a extenção for de gif:
				                $media_post = str_replace("gif","png",$media_post);
						        echo "<img class='imagens-posts gifplayer' src='$media_post' />";
			             }												
			 	 echo"</div>
				 	  <div class='container-opcoes-post'>";
						  if($favoritado){
							  echo "<div onclick='favoritarDesfavoritar(this,$codigo_post)' class='botao-post desfavoritar'></div>";
						  }else{
							  echo "<div onclick='favoritarDesfavoritar(this,$codigo_post)' class='botao-post favoritar'></div>";
						  }
					echo "<a href='post.php?codigo=$codigo_post'><span>$quantidade_de_comentarios comentários<span></a>
					  </div>
				  </div>";
			}
		}	
?>