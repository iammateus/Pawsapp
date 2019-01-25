<?php
	
	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes
	
	date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
	$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
	
	$casual = isset($_POST["casual"])?$_POST["casual"]:0;
	$doacao = isset($_POST["doacao"])?$_POST["doacao"]:0;
	$perdidos = isset($_POST["perdidos"])?$_POST["perdidos"]:0;
	$encontrados = isset($_POST["encontrados"])?$_POST["encontrados"]:0;
	
	$usa_and = $casual || $doacao || $perdidos || $encontrados?"AND":"";

	if($casual == true){
		$busca_casual = "P.tipoPost = 'casual'";
	}else{
		$busca_casual = "";
	}
	
	if($casual && $doacao){
		$busca_doacao = "or P.tipoPost = 'doacao'";
	}else if($doacao){
		$busca_doacao = "P.tipoPost = 'doacao'";
	}else{
		$busca_doacao = "";
	}
	
	if(($casual || $doacao) && $perdidos){
		$busca_perdidos = "or P.tipoPost = 'perdido'";
	}else if($perdidos){
		$busca_perdidos = "P.tipoPost = 'perdido'";
	}else{
		$busca_perdidos = "";
	}
	
	if(($casual || $doacao || $perdidos) && $encontrados){
		$busca_encontrados = "or P.tipoPost = 'encontrado'";
	}else if($encontrados){
		$busca_encontrados = "P.tipoPost = 'encontrado'";
	}else{
		$busca_encontrados = "";
	}
	
	//echo "$usa_where $busca_casual $busca_doacao $busca_perdidos $busca_encontrados";
	
	
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
		U.sobrenome,
		U.foto
	FROM tb_posts P
	INNER JOIN tb_usuarios U
	ON U.codigo = P.codUsuario
	WHERE
	P.ativo = 'true'
	$usa_and
	$busca_casual
	$busca_doacao
	$busca_perdidos
	$busca_encontrados
	ORDER BY P.dataPostagem DESC
	LIMIT 0, 20;");
	
	$busca_posts->execute();
	
	while($linha = $busca_posts->fetch(PDO::FETCH_ASSOC)){
		
		$codigo_post = $linha["codigoPost"];//Pega código do post
    	$tipo_post = $linha["tipoPost"];//Pega o tipo do post
		$titulo_post = $linha["nomeAnimal"];//Pega o tipo do post
		$titulo_post = wordwrap($titulo_post, 15, "\n", true);
    	$descricao_post = $linha["descricaoPost"];//Descrição do post
		$descricao_post = wordwrap($descricao_post, 15, "\n", true);
    	$media_post = $linha["imgPost"];//Pega a media do post
    	$codigo_usuario = $linha["codUsuario"];//Pega código do usuário
		
    	$nome_usuario = ucfirst(strtolower($linha["nomeUsuario"]));//pega nome do usuário
    	$vetor_sobrenome = explode(" ",$linha["sobrenome"]);
		$nome_usuario.=" ".ucfirst(strtolower($vetor_sobrenome[0]));
		
		$foto_usuario = $linha['foto'];//Pega a foto do usuário e guarda em uma variável
		$extencao_media = pegaExtensaoArquivo($media_post);//Pega a extenção da media 
		
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
			echo "Erro!";
		}
		
		echo "<div class='post $classe_cor_post'>
            	<div class='container-img'>";
					if($extencao_media == ".jpg" || $extencao_media == ".png"){//Se a extenção for de imagem:
						echo "<img class='imagens-posts' src='$media_post' />";
			        }elseif($extencao_media == ".mp4"){//Se a extenção for de vídeo:
				    	echo"<video class='video-posts' controls>
							     <source src='$media_post' type='video/mp4' />
							     Desculpe, mas não foi possível carregar o áudio.
							 </video>";
			        }elseif($extencao_media == ".gif"){//Se a extenção for de gif:
				        $media_post = str_replace("gif","png",$media_post);
					    echo "<img class='imagens-posts gifplayer' src='$media_post' />";
			        }
		 echo "</div>
                <div class='detalhes-posts $classe_cor_post'>
                	<img class='dono-da-postagem' src='$foto_usuario'/><span>$nome_usuario</span><br />
                	<div class='container-detalhes'>
                    	<h3>$titulo_post</h3>
                        <p>$descricao_post</p>
                    </div>
                </div>
            </div>";
			
	}	
?>