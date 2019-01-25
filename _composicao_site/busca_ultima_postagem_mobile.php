<?php
	session_start();//Começa a sessão
	
	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes
	
	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
	
	date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
	$data_atual = date('Y-m-d H:i:s');//Pega a hora atual

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
		WHERE
		codUsuario = $codigo_usuario_logado
		ORDER BY P.dataPostagem DESC
		LIMIT 0, 1;");
	
	$busca_posts->execute();
	$_SESSION["comecoBusca"]+=(int)$busca_posts->rowCount();
	
	//Configuração do select costumizado no banco acima----------------------------------------------------------	
	
	if($linha = $busca_posts->fetch(PDO::FETCH_ASSOC)){
		
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


			
		$classe_foto_usuario = "foto-usuario-logado";
			
		//Configuração da aprensetação do post
			
		echo "<div class='posts'>
				  <div class='menu-postagem $classe_cor_post'>
				  	  <div class='ponteiro-postagem'></div>
					  <h2>Menu</h2>
					  <ul>
					  	<a href='post.php?codigo=$codigo_post&editar=true'><li>Editar Postagem</li></a>
					  	<li id='excluir-postagem' onClick=confirmacao(this,'excluir',$codigo_post)>Excluir Postagem</li>
						<li onClick=confirmacao(this,'ocultar',0)>Ocultar Postagem</li>";
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
			 	  <div class='container-opcoes-post'>
				  <div onclick='favoritarDesfavoritar(this,$codigo_post)' class='botao-post favoritar'></div>
				  <a href='post.php?codigo=$codigo_post'><span>0 comentários<span></a>
				  </div>
			  </div>";
		
		}	
?>