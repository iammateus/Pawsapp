<?php

	session_start();//Começa a sessão
	
	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes
	
	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
	$foto_usuario_logado = $informacoes_do_usuario["foto"];
	
	//Pega as informações de limite do select
	$comeco = $_POST["comeco"];
	$codigo_perfil = $_POST["codigoNego"];
	$tipoBusca = $_POST["tipoBusca"];
	
	date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
	$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
	$retorno_html = '';
	
	//Busca quantos favoritos essa busca alcançará
	$busca_quantidade_favoritos = $pdo->query("SELECT COUNT(*) quantidade FROM (SELECT * FROM tb_posts WHERE codUsuario = $codigo_perfil LIMIT $comeco,3) AS A");
	$busca_quantidade_favoritos->execute();
	$busca_quantidade_favoritos = $busca_quantidade_favoritos->fetch(); 
	$busca_quantidade_favoritos = (int)$busca_quantidade_favoritos["quantidade"];

	if($busca_quantidade_favoritos == 0 and $comeco == 0){//Se está no limite 0 e não há favoritos é porque a pessoa não tem favoritos
		
		$status = "nenhum post";
	
	}elseif($busca_quantidade_favoritos == 0){
		
		$status = "fim de post";
	
	}else{
		
		$status = "posts encontrados";
		
		//Query que busca os favoritos do usuário logado e execução:
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
		WHERE P.codUsuario = $codigo_perfil and P.ativo = 'true'
		ORDER BY P.dataPostagem DESC
		LIMIT $comeco, 3;");
		$busca_posts->execute();
		
		//Começo vai incrementar
		$comeco+=$busca_quantidade_favoritos;
		
		while($linha = $busca_posts->fetch(PDO::FETCH_ASSOC)){
			
			//Pega informações do post
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
			
			if($extencao_media == ".jpg" || $extencao_media == ".png"){//Se a extenção for de imagem:
				$tipo_media_post = "foto";//O tipo de media é foto
			}elseif($extencao_media == ".mp4"){//Se a extenção for de vídeo:
				$tipo_media_post = "video";//O tipo de media é video
			}else{//Se a extenção for de gif:
				$tipo_media_post = "gif";//O tipo de media é gif
			}
			
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
			}elseif($tipo_post == "encontrado"){
				$classe_cor_post = "encontrado";
				$tipo_post = "Encontrado";
			}else{
				$retorno_html.= "Erro!";
			}
			
			$classe_foto_usuario = $codigo_usuario == $codigo_usuario_logado?"foto-usuario-logado":"";
			
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
			
			if($bloqueios < 1){
				//Configuração da aprensetação do post
				$retorno_html.=
					"<div class='posts $classe_cor_post'>
					
						 <div class='menu-postagem $classe_cor_post'>
							 <div class='ponteiro-postagem'></div>
							 <h2>Menu</h2>
							 <ul>
								<a href='post.php?codigo=$codigo_post'><li>Página da postagem</li></a> 	
							";
							 if($codigo_usuario == $codigo_usuario_logado){
								 $retorno_html.="
								 <a href='post.php?codigo=$codigo_post&editar=true'><li>Editar Postagem</li></a>
								 <li id='excluir-postagem' onClick=confirmacao(this,'excluir',$codigo_post)>Excluir Postagem</li>";
							 }else{
								 $retorno_html.="<li onClick=confirmacao(this,'denunciar',$codigo_post)>Denúnciar Postagem</li>";
								 $retorno_html.="<li onClick=confirmacao(this,'bloquear',$codigo_usuario)>Bloquear Usuario</li>";
								 if(!$ja_seguiu){
									 $retorno_html.= "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Seguir Usuário</li>"; 
								 }else{
									 $retorno_html.= "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Não Seguir</li>";
								 }
							 }
							 $retorno_html.= "<li onClick=confirmacao(this,'ocultar',0)>Ocultar Postagem</li>";
							 $retorno_html.= "</ul>
						 </div>
						 
						 <img class='abrir-menu-postagem' src='_imgs/abrir-menu-postagens.png' onClick='abrirFecharMenupostagem(this)' /> 
						  
						 <div class='container-detalhes-post'>
							 <header class='container-detalhes-dono $classe_cor_post'>
								 <a href='perfil.php?codigo=$codigo_usuario'><img class='fotos-perfil-feed-postagens $classe_foto_usuario' src='$foto_usuario' />
								 <span class='nome-user'>$nome_usuario</span>
								 </a>
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
	   $retorno_html.="  </div>
						  
						 <div class='container-opcoes-post'>
							 <div onclick='favoritarDesfavoritar(this,$codigo_post)' class='botao-post desfavoritar'></div>
						 </div>
						  
						 <div class='container-comentarios $classe_cor_post'>";
							 
							 //Query para buscar favoritos e execução
							 $busca_comentarios=$pdo->prepare(
							 "SELECT 
								 C.codigo,
								 C.codUsuario,
								 C.codPostagem,
								 C.conteudo,
								 C.dataHora,
								 U.foto,
								 U.nome
							 FROM tb_comentarios C
							 INNER JOIN tb_usuarios U
							 ON U.codigo = C.codUsuario
							 WHERE codPostagem = :codPostagem 
							 ORDER BY C.dataHora
							 LIMIT 0, 5");
							 $busca_comentarios->bindParam(":codPostagem",$codigo_post);
							 $busca_comentarios->execute();
							   
							 while($linha2 = $busca_comentarios->fetch(PDO::FETCH_ASSOC)){
								 $codigo_comentario = $linha2["codigo"];
								 $codigo_dono_comentario = $linha2["codUsuario"];
								 $conteudo_comentario = $linha2["conteudo"];
								 $conteudo_comentario = wordwrap($conteudo_comentario, 15, "\n", true);
								 $foto_dono_comentario = $linha2['foto'];//Pega a foto do usuário e guarda em uma variável
								 $nome_dono_comentario = $linha2['nome'];
									
								 //Verifica se o usuário já denunciou antes
								 $busca_denuncia_comentario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codigoDenunciado = :codComentario AND codDenunciador = :codUsuario AND tipoDenunciado = 'comentario'");
								 $busca_denuncia_comentario->bindParam(":codUsuario",$codigo_usuario_logado);
								 $busca_denuncia_comentario->bindParam(":codComentario",$codigo_comentario);
								 $busca_denuncia_comentario->execute();
								 $busca_denuncia_comentario = $busca_denuncia_comentario->fetch();
								 $denuncias_realizadas = $busca_denuncia_comentario["quantidade"];
								 
								 $busca_bloqueio= $pdo->query("SELECT COUNT(*) quantidade FROM `tb_bloqueio_usuario` WHERE (codUsuario = $codigo_usuario_logado AND codBloqueado = $codigo_dono_comentario) or (codUsuario = $codigo_dono_comentario AND codBloqueado = $codigo_usuario_logado)");
								 $busca_bloqueio->execute();
								 $busca_bloqueio = $busca_bloqueio->fetch();
								 $bloqueios = $busca_bloqueio["quantidade"];
									
								 if($denuncias_realizadas < 1 && $bloqueios < 1){//Se a busca retornar 0 é porque ele não denunciou então o mesmo pode denunciar
										
									 $data_comentario = $linha2["dataHora"];
									 $tempo_de_cometario = buscaTempo($data_comentario);
										
									 $retorno_html.= "<div class='comentarios'>
														  <div class='container-detalhes-dono-mensagem'>
															  <a href='perfil.php?codigo=$codigo_dono_comentario'>
																  <img class='foto-dono-mensagem' src='$foto_dono_comentario' />
																  <span class='nome-comentador'>$nome_dono_comentario</span>
															  </a>
														  <span class='tempo-comentario'>$tempo_de_cometario</span>
														  </div>
												  
														  <div class='conteudo-comentario'>
															  <p>$conteudo_comentario</p>
														  </div>
													  
														  <div class='container-opcoes-comentarios'>
															  <ul>
																   <li onClick='ocultarComentario(this)'>Ocultar</li>";
																   if($codigo_dono_comentario == $codigo_usuario_logado){
																	   $retorno_html.= "<li onClick=confirmacao(this,'excluircomentario',$codigo_comentario)>Excluir</li>";
																   }else{
																	   $retorno_html.= "<li onClick=confirmacao(this,'denunciarcomentario',$codigo_comentario)>Denunciar</li>";
																   }
											 $retorno_html.="</ul>
														  </div>					
													  </div>";
								}//Fechamento IF
							
							}//Fechamento WHILe
							
							$busca_comentarios=$pdo->prepare("SELECT COUNT(*) quantidade FROM tb_comentarios WHERE codPostagem = :codPostagem");
							$busca_comentarios->bindParam(":codPostagem",$codigo_post);
							$busca_comentarios->execute();
							$busca_comentarios = $busca_comentarios->fetch();
							$busca_comentarios = $busca_comentarios["quantidade"];
		  $retorno_html.="</div>";//Fechamento container coentario
		  if($busca_comentarios > 5){
			  $retorno_html.="<button id='carregar-mais-comentarios' class='$classe_cor_post' onClick='carregarMaisComentarios(this,$codigo_post,2)'>Carregar mais comentários</button>";
		  }
		  $retorno_html.= "<div class='container-inputs-comentar'>
							  <img class='foto-usuario-comentar foto-usuario-logado' src='$foto_usuario_logado' />
							  <form method='post' onSubmit='return comentar(this)'>
								  <input name='codigoPostagem' type='hidden' value='$codigo_post>' />
								  <textarea onKeyUp='limpaComentar(this)' name='textarea' class='textarea-comentar' placeholder='Faça um comentario'></textarea>
								  <input class='botao-enviar-comentario' type='submit' value='Enviar' />
								  <img class='icone-enviar-comentar' src='_imgs/200_s.gif' />
								  <span class='mensagem-comentar'></span>
							  </form>
						  </div>";
			  
	$retorno_html.= "</div>";//Fechamneto div posts
			}//Fim do if de bloqueio
		}//fechamento While
	}//fechamneto condições de status
	
	echo json_encode(array("retornohtml" => $retorno_html,"proximocomeco" => $comeco,"status" => $status));
?>