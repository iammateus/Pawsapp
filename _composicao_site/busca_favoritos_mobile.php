<?php
	session_start();//Começa a sessão
	
	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes
	
	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
	//Pega as informações de limite do select
	$comeco = $_POST["comecoFavoritos"];
	$retorno_html = "";		
	
	date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
	$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
	
	//Busca quantos favoritos essa busca alcançará
	$busca_quantidade_favoritos = $pdo->query("SELECT COUNT(*) quantidade FROM (SELECT * FROM tb_favoritos WHERE codUsuario = $codigo_usuario_logado LIMIT $comeco,3) AS A");
	$busca_quantidade_favoritos->execute();
	$busca_quantidade_favoritos = $busca_quantidade_favoritos->fetch(); 
	$busca_quantidade_favoritos = $busca_quantidade_favoritos["quantidade"];
	
	if($busca_quantidade_favoritos == 0 and $comeco == 0){//Se está no limite 0 e não há favoritos é porque a pessoa não tem favoritos
		
		$status = "nenhum favorito";
	
	}elseif($busca_quantidade_favoritos == 0){
		
		$status = "fim de favoritos";
	
	}else{
		
		$status = "favoritos encontrados";
	
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
		WHERE F.codUsuario = $codigo_usuario_logado and P.ativo = 'true'
		ORDER BY P.dataPostagem DESC
		LIMIT $comeco, 3;");
	
		$busca_posts->execute();
		
		$comeco+=$busca_quantidade_favoritos;
		
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
			
			$busca_bloqueio= $pdo->query("SELECT COUNT(*) quantidade FROM `tb_bloqueio_usuario` WHERE (codUsuario = $codigo_usuario_logado AND codBloqueado = $codigo_usuario) or (codUsuario = $codigo_usuario AND codBloqueado = $codigo_usuario_logado)");
			$busca_bloqueio->execute();
			$busca_bloqueio = $busca_bloqueio->fetch();
			$bloqueios = $busca_bloqueio["quantidade"];
			
			$classe_foto_usuario = $codigo_usuario == $codigo_usuario_logado?"foto-usuario-logado":"";
			
			//Configuração da aprensetação do post
			if($bloqueios < 1){
				$retorno_html.= 
				"<div class='posts'>
					 <div class='menu-postagem $classe_cor_post'>
						 <div class='ponteiro-postagem'></div>
						 <h2>Menu</h2>
						 <ul>";
							 if($codigo_usuario == $codigo_usuario_logado){
								 $retorno_html.="
								 <a href='post.php?codigo=$codigo_post&editar=true'><li>Editar Postagem</li></a>
								 <li id='excluir-postagem' onClick=confirmacao(this,'excluir',$codigo_post)>Excluir Postagem</li>";
							 }else{
								 $retorno_html.="<li onClick=confirmacao(this,'denunciar',$codigo_post)>Denúnciar Postagem</li>";
								 $retorno_html.="<li onClick=confirmacao(this,'bloquear',$codigo_usuario)'>Bloquear Usuario</li>";
								 if(!$ja_seguiu){
									 $retorno_html.= "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Seguir Usuário</li>"; 
								 }else{
									 $retorno_html.= "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Não Seguir</li>";
								 }
							 }
							 $retorno_html.= "<li onClick=confirmacao(this,'ocultar',0)>Ocultar Postagem</li>";
		$retorno_html.=" </ul>
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
						        $retorno_html.= "<a href='post.php?codigo=$codigo_post'><img class='imagens-posts' src='$media_post' /></a>";
			               	 }elseif($extencao_media == ".mp4"){//Se a extenção for de vídeo:
				                $retorno_html.="<video class='video-posts' controls>
						  		          <source src='$media_post' type='video/mp4' />
								          Desculpe, mas não foi possível carregar o áudio.
							         </video>";
			                 }elseif($extencao_media == ".gif"){//Se a extenção for de gif:
				                $media_post = str_replace("gif","png",$media_post);
						        $retorno_html.= "<img class='imagens-posts gifplayer' src='$media_post' />";
			                }												
	$retorno_html.="</div>
					
					<div class='container-opcoes-post'>";
						
							$retorno_html.= "<div onclick='favoritarDesfavoritar(this,$codigo_post)' class='botao-post desfavoritar'></div>";
						$retorno_html.= "<a href='post.php?codigo=$codigo_post'><span>$quantidade_de_comentarios comentários<span></a>
					</div>
				</div>";//fechamento div post
			}//fim do if
		}//fechamento while
	}//fechamneto 
		
	echo json_encode(array("retornohtml" => $retorno_html,"proximocomeco" => $comeco,"status" => $status));
?>