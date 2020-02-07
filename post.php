<?php
	session_start();//Inicia a sessão
	if(isset($_GET["sair"])){//Se o usuário clicou em sair
		session_destroy();//detroi a sessão
		header("Location: index.php");//redireciona para a pagina de login
		exit;//Encerra o funcionamento da página atual
	}
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
		
		if(!isset($_GET['codigo']) || $_GET['codigo'] == "" || $_GET['codigo'] == NULL){
			header("Location: feed.php");//redireciona para a pagina de login
			exit;//Encerra o funcionamento da página atual
		}else{
			require("_composicao_site/script-funcoes.php");//Inclui script de funcoes
			$informacoes_do_usuario =  array_merge(pegaInformacoesUsuario());//Pegando informações do usuario e colocando-as em um vetor
			$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
			require("_composicao_site/connection.php");//Conecta com o banco
			$codigo_postagem = $_GET['codigo'];//pega o código da postagem
			
			$verifica_existencia_post = $pdo->prepare("SELECT COUNT(*) as 'Quantidade' FROM tb_posts WHERE codigo = :codigoPostagem");
			$verifica_existencia_post->bindParam(":codigoPostagem",$codigo_postagem);
			$verifica_existencia_post->execute();
			$post_existe = $verifica_existencia_post->fetch();
			$post_existe = $post_existe["Quantidade"];
			
			if($post_existe < 1){
				header("Location: feed.php");//dedireciona para o feed
				exit;	
			}else{
				$busca_dono_post = $pdo->prepare("SELECT codUsuario FROM tb_posts WHERE codigo = :codigoPostagem");
				$busca_dono_post->bindParam(":codigoPostagem",$codigo_postagem);
				$busca_dono_post->execute();
				$busca_dono_post = $busca_dono_post->fetch();
				$codigo_usuario = $busca_dono_post["codUsuario"];
								
				$busca_bloqueio= $pdo->query("SELECT COUNT(*) quantidade FROM `tb_bloqueio_usuario` WHERE (codUsuario = $codigo_usuario_logado AND codBloqueado = $codigo_usuario) or (codUsuario = $codigo_usuario AND codBloqueado = $codigo_usuario_logado)");
				$busca_bloqueio->execute();
				$busca_bloqueio = $busca_bloqueio->fetch();
				$bloqueios = $busca_bloqueio["quantidade"];
				
				if($bloqueios > 0){
					header("Location: feed.php");//dedireciona para o feed
					exit;
				}else{
					$foto_usuario_logado = $informacoes_do_usuario['foto'];//Pega a foto do usuário e guarda em uma variável
					$_SESSION["comentarios-feitos"] = 0;
					date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
					$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
				}
			}
		}
				
	}else{//Se acontecer algo errado
		session_destroy();//A sessão é destruida
		header("Location: index.php");//dedireciona para a página de login 
		exit;//Encerra o funcionamento da página atual
	}
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="Rede social adoção, adotar animais, encontrar animais">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Void Main">
    <title>Paws App | Postagem</title>
    <link rel="shortcut icon" href="_imgs/logo5.png" type="image/x-icon" />
    <link href="_css/css-post.css" rel="stylesheet" type="text/css" />
    <link href="_css/postagem.css" rel="stylesheet" type="text/css" />
    <link href="_css/gifplayer.css" rel="stylesheet" type="text/css" />
    <link href="_css/modal-confirmacao.css" rel="stylesheet" type="text/css" />
    <link href="_css/formularios.css" rel="stylesheet" type="text/css" />
    <link href="_css/estilo-baloes.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="_script/jquery.js"></script>
    <script type="text/javascript" src="_script/script-post.js"></script>
    <script type="text/javascript" src="_script/script-funcoes-post.js"></script>
    <script type="text/javascript" src="_script/jquery.gifplayer.js"></script>
    <script type="text/javascript" src="_script/script-formularios-editar-postar.js"></script>
    <script type="text/javascript" src="_script/ImageTools.js"></script>
    <script type="text/javascript" src="_script/mascara-input.js"></script>
    <script type="text/javascript">
        $.noConflict();
    </script>
</head>
<body>
	
    <div id="camada-fundo-site">
    	<div id="container-confimacoes">
        	<div onclick="fecharModal()" id="botao-fechar-filtrar"></div>
            <h1 id="titulo-confirmacao" class="titulo-modal">Confirmação</h1>
            <p id="conteudo-confirmacao"></p>
            <form id="form-motivo-denuncia">
            	<ul>
                	<li><input checked name="motivoDenuncia" id="irritante" type="radio" value="irritante"/><label for="irritante">É irritante ou desinteressante</label></li>
                    <li><input name="motivoDenuncia" id="ofensivo" type="radio" value="ofensivo"/><label for="ofensivo">Acredito que não deveria estar no PawsApp</label></li>
                    <li><input name="motivoDenuncia" id="spam" type="radio" value="spam"/><label for="spam">É spam</label></li>
				</ul>
            </form>
            <div id="opcoes-confirmacao" class="container-botoes-modal">
            	<div id="botao-confirma" class="botoes-modal" onclick="">Confirmar</div>
                <div class="botoes-modal" onclick="fecharModal()">Cancelar</div>
			</div>
        </div>
        <div id="container-postar">
            	<div onclick="fecharModalPostar()" class="fechar-container"></div>
                <h1 class="titulo-modal">Editar</h1>
                <div id="container-postar-de-dentro">
                    <form id="form-postar" enctype="multipart/form-data" method="post" onSubmit="return editar(this)">
                        <input type="hidden" value="<?php echo $codigo_postagem; ?>" name="codigo"/>
                        <div class="container-campos-postar">
                            <label class="label-postar" for="campo-titulo">Título/Nome Animal:</label>
                            <div class="divs-input">
                                <input class="input-postar" type="text" name="campoTitulo" id="campo-titulo" placeholder="Ex: Larissa" />
                                <div class="erro-input-esquerda"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <span class="tooltiptext">Campo obrigatório!</span> <!--Descrição do aviso-->
                                </div>
                            </div>
                        </div>
                        <div class="container-campos-postar">
                            <label class="label-postar" for="select-tipoPost">Tipo Postagem:</label>
                            <div class="container-selects-postar">
                                <select class="selects-postar" name="campoTipo" id="select-tipoPost">
                                    <option value="" selected>Escolha uma opção:</option>
                                    <option value="casual" >Casual</option>
                                    <option value="doacao">Doação</option>
                                    <option value="perdido">Perdido</option>
                                    <option value="encontrado">Encontrado</option>
                                </select>
                                <div class="aviso-erro aviso-input-selects"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <div class="tooltiptext">Atenção ao escolher o tipo de postagem.</div> <!--Descrição do aviso-->
                                </div>
                            </div>
                        </div>
                        <div class="container-campos-postar" id="informacoes-posts">
                            <label class="label-postar" for="campo-especie">Especie:</label>
                            <div class="container-selects-postar">
                                <select class="selects-postar" name="campoEspecie" id="campo-especie">
                                	<option value="" selected>Escolha uma opção:</option>
                                    <option value="cachorro">Cachorro</option>
                                    <option value="gato">Gato</option>
                                    <option value="passaro">Pássaro</option>
                                    <option value="outros">Outros</option>
                                </select>
                                <div class="aviso-erro aviso-input-selects"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <div class="tooltiptext">Atenção a especie do animal é importante para a visualização da sua postagem.</div> <!--Descrição do aviso-->
                                </div>
                            </div>
                            <label id="label-porte" class="label-postar" for="portePostar">Porte:</label>
                            <div id="seleciona-tamanho">
                            	<fieldset id="container-radios-tamanho">
                                	<div class="container-foto-input">
                                		<input id="radio-pequeno" name="campoPorte" class="radios-tamanho" type="radio" value="pequeno" checked />
                                        <label style="background-image:url(_imgs/small-size.png)" for="radio-pequeno"></label>
                                    </div>
                                    <div class="container-foto-input">
                                    	<input id="radio-medio" name="campoPorte" class="radios-tamanho" type="radio" value="medio" />
                                    	<label style="background-image:url(_imgs/middle-size.png)" for="radio-medio"></label>
                                    </div>
                                    <div class="container-foto-input">
                                    	<input id="radio-grande" name="campoPorte" class="radios-tamanho" type="radio" value="grande" />
                                		<label style="background-image:url(_imgs/big-size.png)" for="radio-grande"></label>
                                    </div>
                                </fieldset>
                            </div>
                            <label class="label-postar" for="campoRaca">Raça:</label>
                            <div class="container-selects-postar">
                                <select class="selects-postar" name="campoRaca" id="campoRaca">
                                	<option value="" selected>Escolha uma raça:</option>
                                    <optgroup label="Opções padrão: " class="grupo-racas-cachorro">
                                        <option value="naodefinido">Não Definido</option>
                                        <option value="outra">Outra</option>
                                    </optgroup>
                                    <optgroup label="Raças de cachorros" class="grupo-racas-cachorro">
                                        <option value="pastoralemao">Pastor Alemão</option>
                                        <option value="rottweiler">Rottweiler</option>
                                        <option value="doberman">Dobermann</option>
                                    </optgroup>
                                    <optgroup label="Raças de gatos" class="grupo-racas-gato">
                                    	<option value="siames">Siamês</option>
                                        <option value="persa">Persa</option>
                                    </optgroup>
                                    <optgroup label="Raças de pássaros" class="grupo-racas-passaro">
                                    	<option value="canarios">Canários</option>
                                    </optgroup>
                                </select>
                                <div class="aviso-erro aviso-input-selects"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <div class="tooltiptext">Campo raça é relevante mas não obrigatório.</div> <!--Descrição do aviso-->
                                </div>
                            </div>
                            <label class="label-postar" for="campo-cor">Cor:</label>
                            <div class="container-selects-postar">
                                <select class="selects-postar" name="campoCor" id="campo-cor">
                                	<option value="" selected>Escolha uma opção:</option>
                                    <option value="preto">Preto</option>
                                    <option value="branco">Branco</option>
                                    <option value="marrom">Marrom</option>
                                    <option value="listrado">Listrado</option>
                                    <option value="naoInformado">Não definida</option>
                                </select>
                                <div class="aviso-erro aviso-input-selects"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <div class="tooltiptext">Campo cor é obrigatório.</div> <!--Descrição do aviso-->
                                </div>
                            </div>
                            <label class="label-postar" for="campo-sexo">Sexo:</label>
                            <div class="container-selects-postar">
                                <select class="selects-postar" name="campoSexo" id="campo-sexo">
                                	<option value="" selected>Escolha uma opção:</option>
                                    <option value="Fêmea">Fêmea</option>
                                    <option value="Macho">Macho</option>
                                    <option value="Ambos">Macho(s) e Fêmea(s)</option>
                                </select>
                                <div class="aviso-erro aviso-input-selects"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <div class="tooltiptext">Atenção ao escolher o sexo do animal.</div> <!--Descrição do aviso-->
                                </div>
                            </div>
                            <label class="label-postar" for="campoTelefone">Telefone Contado:</label>
                            <div class="divs-input">
                                <input class="input-postar" type="text" name="campoTelefone" id="campoTelefone" placeholder="Ex: (11) 9999-9999" />					
                                <div class="erro-aviso-esquerda aviso-input-esquerda"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <span class="tooltiptext">Número de telefone opcional.</span> <!--Descrição do aviso-->
                                </div>
                            </div>
                            <label id="label-local" class="label-postar" for="campoLocal">Local:</label>
                            <div class="divs-input" id="container-local">
                                <input class="input-postar" type="text" name="campoLocal" id="campo-local" placeholder="Ex: Bairro: Jardim das Flores, Rua: Antonieta" />
                                <div class="erro-aviso-esquerda aviso-input-esquerda"> <!--Icone de erro, que engloba a descrição do aviso-->
                                    <span class="tooltiptext">Por segurança recomendamos não informar o número de sua moradia</span> <!--Descrição do aviso-->
                                </div>
                            </div>
                        </div>
                        <div class="container-campos-postar">
                            <label class="label-postar" for="descricao-postagem">Descrição:</label>
                            <textarea name="campoDescricao" id="descricao-postagem" placeholder="Ex: Eu amo esse cachorro!!!"></textarea>
                        </div>
                        <div class="container-mensagens">
                       		<span id="avisos-postar-descricao"></span>
                        </div>
                        <div id="container-upload-postar" class="container-campos-postar">
                            <label class="label-postar" for="campo-imagem">Foto/Vídeo</label>
                            <input onChange="uploadFile()" style="display:none;" type="file" name="imagem" id="campo-imagem" accept="image/x-png,image/gif,image/jpeg,video/mp4"/>
                            <div id="container-buscar-arquivo">
                                <div id="selecionar-imagem" onClick="document.getElementById('campo-imagem').click();">
                                    <div id="erro-media" class="erro-input-direita"> <!--Icone de erro, que engloba a descrição do aviso-->
                                        <span class="tooltiptext">Tipo de arquivo não suportado.</span> <!--Descrição do aviso-->
                                    </div>
                                </div>
                                <div id="container-progress">
                                    <div id="progress"></div>
                                    <span id="status">0%</span>
                                </div>
                            </div>
                            <div id="media-preview">
                            </div>
                            
                        </div>
                        <div class="container-mensagens">
                      		<div id="carregando-postar-container">
                            	<img id="carregando-postar" src="_imgs/200_s.gif" />
                                <p>Carregando...</p>
                            </div> 
                       	</div>
                    	<div class="container-botoes-modal">
                        	<div class="botoes-modal" onclick="confirmacao(this,'edicao',<?php echo $codigo_postagem; ?>)">Editar</div>
                        	<div class="botoes-modal" onclick="fecharModalPostar()">Cancelar</div>
                    	</div>
                    </form>
                    
                </div>
            </div>
    </div>
	
    <div id="interface"><!--Div de interface (mãe de todas as outras)-->
        <header id="cabecario-site"><!--Cabeçario do site (mobile e desktop)-->
    	    <a href="feed.php"><div id="botao-voltar"></div></a>
        	<img id="logo-cabecario" src="_imgs/logo5.png">                
            <!--<form method="post">
	            <input type="submit" name="sair" id="botao-sair" value="Sair" />	
            </form>-->
            <div onClick="confirmacao(this,'sair',0)" id="botao-sair">Sair</div>
       	</header><!--Cabeçario do site (mobile e desktop)-->
        <div id="container-vsualizacao">
        	<br/>
			<?php
				$busca_post = $pdo->prepare("
				SELECT 
					P.tipoPost,
					P.descricaoPost,
					P.imgPost,
					P.nomeAnimal,
					P.dataPostagem,
					P.especieAnimal,
					P.porteAnimal,
					P.sexoAnimal,
					P.cor,
					P.raca,
					P.contato,
					P.localidade,
					U.codigo codUsuario,
					U.nome nomeUsuario,
					U.foto
				FROM tb_posts P
				INNER JOIN tb_usuarios U
				ON U.codigo = P.codUsuario
				WHERE P.codigo = :codigoPost;");//SELECT da postagem
				
				$busca_post->bindParam(":codigoPost",$codigo_postagem);//Prepara a variável do select
				$busca_post->execute();//Executa
				$informacoesPost = $busca_post->fetch();//Coloca o resultado em uma variável
				
				$tipo_post = $informacoesPost["tipoPost"];//Pega o tipo do post
				$titulo_post = $informacoesPost["nomeAnimal"];//Pega o tipo do post
				$titulo_post = wordwrap($titulo_post, 15, "\n", true);
				$descricao_post = $informacoesPost["descricaoPost"];//Descrição do post
				$descricao_post = wordwrap($descricao_post, 15, "\n", true);
				$media_post = $informacoesPost["imgPost"];//Pega a media do post
				$codigo_usuario = $informacoesPost["codUsuario"];//Pega código do usuário
				$nome_usuario = $informacoesPost["nomeUsuario"];//pega nome do usuário
				$foto_usuario = $informacoesPost['foto'];//Pega a foto do usuário e guarda em uma variável
				$extencao_media = pegaExtensaoArquivo($media_post);//Pega a extenção da media 
				
				//Calculo de tempo de postagem
				$data_postagem = $informacoesPost["dataPostagem"];
				$tempo_de_postagem = buscaTempo($data_postagem);
				
				//Busca se o usuário visualizando denunciou a postagem atual
				$busca_denuncia_usuario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codElemento = :codPostagem AND codDenunciador = :codusuario AND tipoDenunciado = 'postagem'");
				$busca_denuncia_usuario->bindParam(":codusuario",$codigo_usuario_logado);
				$busca_denuncia_usuario->bindParam(":codPostagem",$codigo_postagem);
				$busca_denuncia_usuario->execute();
				$busca_denuncia_usuario = $busca_denuncia_usuario->fetch();
				$denuncias_realizadas = $busca_denuncia_usuario["quantidade"];
				
				//Grava notificação visualizada
				if($codigo_usuario == $codigo_usuario_logado){
					$grava_notificacoes_lidas = $pdo->prepare("UPDATE tb_notificacoes SET foiLido = 1 WHERE codRecebedor = :codigoUsuario and codPost = :codPostagem");
					$grava_notificacoes_lidas->bindParam(":codigoUsuario",$codigo_usuario_logado);
					$grava_notificacoes_lidas->bindParam(":codPostagem",$codigo_postagem);
					$grava_notificacoes_lidas->execute();
				}
					
				//Abaixo pega tipo do post para saber se seram nescessárias as outras informações
				if($tipo_post == "casual"){
					$classe_cor_post = "casual";
					$tipo_post = "Casual";
				}else{
					$especie_animal = ucfirst(strtolower($informacoesPost["especieAnimal"]));//Pega a especie do animal
					$porte_animal = $informacoesPost["porteAnimal"];//Pega a porte do animal
					$sexo_animal = $informacoesPost["sexoAnimal"];//Pega a sexo do animal
					$cor_animal = $informacoesPost["cor"];//Pega a cor do animal
					$raca_animal = $informacoesPost["raca"];//Pega a raca do animal
					$contato = $informacoesPost["contato"];//Pega a contato do animal
					$localidade = $informacoesPost["localidade"];//Pega a contato do animal
					
					if($tipo_post == "doacao"){
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
				}
				//Busca se o usuário já favoritou o post ou não
				$busca_favoritado = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_favoritos` WHERE codUsuario = :codigoUsuario and codPost = :codigoPost");
				$busca_favoritado->bindParam(":codigoUsuario",$codigo_usuario_logado);
				$busca_favoritado->bindParam(":codigoPost",$codigo_postagem);
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
				//Configuração da aprensetação do post
			?>	
			<div class="posts post-sozinho"><!--Container Post-->
            	<div class="menu-postagem <?php echo $classe_cor_post; ?>"><!--Menu Post-->
                	<div class="ponteiro-postagem"></div>
            		<h2>Menu</h2>
					<ul>
                   		<?php
					        if($codigo_usuario == $codigo_usuario_logado){
						        echo"<li onClick='chamaModalEditar(this,$codigo_postagem)'>Editar Postagem</li>";
								echo"<li id='excluir-postagem' onClick=confirmacao(this,'excluir',$codigo_postagem)>Excluir Postagem</li>";
						    	
							}else{
								echo"<li onClick=confirmacao(this,'bloquear',$codigo_usuario)>Bloquear Usuario</li>";
								if($denuncias_realizadas <= 0){
									echo"<li onClick=confirmacao(this,'denunciar',$codigo_postagem)>Denúnciar Postagem</li>";
								}else{
									echo"<li style='background-color:rgba(0,0,0,0.3)'>Postagem Denunciada</li>";
								}
								if(!$ja_seguiu){
						            echo "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Seguir Usuário</li>"; 
						        }else{
						            echo "<li onClick='seguirUsuarioDeseguir(this,$codigo_usuario,1)'>Não Seguir</li>";
						        }
						    }
						?>
					</ul>
				</div><!--Menu Post-->
				<img class="abrir-menu-postagem" src="_imgs/abrir-menu-postagens.png" onClick="abrirFecharMenupostagem(this)" /> 
                <div class="container-detalhes-post">
				    <header class="container-detalhes-dono <?php echo $classe_cor_post; ?>">
				    	<?php
							echo "<a href='perfil.php?codigo=$codigo_usuario'><img class='fotos-perfil-feed-postagens' src='$foto_usuario' />
								      <span class='nome-user'>$nome_usuario</span>
								  </a>";
				    		echo "<h1> $tipo_post</h1>";
                        ?>
                        <span class="tempo-postagem"><?php echo $tempo_de_postagem; ?></span>
				    </header>
				    <div class="container-titulo-descricao">
				        <h2><?php echo $titulo_post; ?></h2>
				    	<p><?php echo $descricao_post; ?></p>
				    </div>
                    <?php
					
						if($tipo_post <> "Casual"){
							echo "<div class='container-detalhes-precisos'>
									   <div class='container-ver-mais-menos'>
									       <label>Ver mais</label> 
										   <div onClick='verMaisMenos(this)' class='img-informacoes ver-mais'>
										   </div>
									   </div>
								       
									   <ul class='ul-informacoes-post'>
									        <li>Especie: $especie_animal</li>";
											if($raca_animal != ""){
												echo"<li>Raça: $raca_animal</li>";
											}
											echo"
											<li>Sexo: $sexo_animal</li>
											<li>Contato: $contato</li>";
											if($tipo_post == "perdido"){
												echo"
												<li>Última vez visto: $localidade</li>";
											}else{
												echo"
												<li>Localidade: $localidade</li>";
											}
							echo "     </ul>
							      </div>";
						}
					?>
				</div>
				<div class="container-foto-video-post">
				    <?php
						if($extencao_media == ".jpg" || $extencao_media == ".png"){//Se a extenção for de imagem:
							echo "<img class='imagens-posts' src='$media_post' />";
						}elseif($extencao_media == ".mp4"){//Se a extenção for de vídeo:
							echo "<video class='video-posts' controls>
				  				      <source src='$media_post' type='video/mp4' />
									  Desculpe, mas não foi possível carregar o áudio.
								  </video>";
						}elseif($extencao_media == ".gif"){//Se a extenção for de gif:
							$media_post = str_replace("gif","png",$media_post);
					  		echo"<img class='imagens-posts gifplayer' src='$media_post' />";
						}
					?>												
				</div>
				<div class="container-opcoes-post">
					<?php
						if($favoritado){
							echo "<div onclick='favoritarDesfavoritar(this,$codigo_postagem)' class='botao-post desfavoritar'></div>";
						}else{
							echo "<div onclick='favoritarDesfavoritar(this,$codigo_postagem)' class='botao-post favoritar'></div>";
						}						
					?>
				</div>
                <div class="container-comentarios <?php echo $classe_cor_post; ?>">
                	<?php
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
						$busca_comentarios->bindParam(":codPostagem",$codigo_postagem);
						$busca_comentarios->execute();
						
						while($linha2 = $busca_comentarios->fetch(PDO::FETCH_ASSOC)){
							$codigo_comentario = $linha2["codigo"];
							$codigo_dono_comentario = $linha2["codUsuario"];
							$conteudo_comentario = $linha2["conteudo"];
							$conteudo_comentario = wordwrap($conteudo_comentario, 15, "\n", true);
							$foto_dono_comentario = $linha2['foto'];//Pega a foto do usuário e guarda em uma variável
							$nome_dono_comentario = $linha2['nome'];
							
							//Verifica se o usuário já denunciou antes
							$busca_denuncia_comentario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codElemento = :codComentario AND codDenunciador = :codUsuario AND tipoDenunciado = 'comentario'");
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
								
								echo "<div class='comentarios'>
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
													   echo "<li onClick=confirmacao(this,'excluircomentario',$codigo_comentario)>Excluir</li>";
												   }else{
													   echo "<li onClick=confirmacao(this,'denunciarcomentario',$codigo_comentario)>Denunciar</li>";
												   }
										  echo"</ul>
										  </div>					
								  	</div>";
							}
						
						}
						
						$busca_comentarios=$pdo->prepare("SELECT COUNT(*) quantidade FROM tb_comentarios WHERE codPostagem = :codPostagem");
						$busca_comentarios->bindParam(":codPostagem",$codigo_postagem);
						$busca_comentarios->execute();
						$busca_comentarios = $busca_comentarios->fetch();
						$busca_comentarios = $busca_comentarios["quantidade"];
			echo "</div>";
						if($busca_comentarios > 5){
							echo"<button id='carregar-mais-comentarios' class='$classe_cor_post' onClick='carregarMaisComentarios(this,$codigo_postagem,2)'>Carregar mais comentários</button>";
						}
					?>
                
                    <div class='container-inputs-comentar'>
                    	<img class="foto-usuario-comentar" src="<?php echo $foto_usuario_logado; ?>" />
                    	<form method="post" onSubmit="return comentar(this)">
                        	<input name="codigoPostagem" type="hidden" value="<?php echo $codigo_postagem; ?>" />
                        	<textarea onKeyUp="limpaComentar(this)" name="textarea" class="textarea-comentar" placeholder="Faça um comentario"></textarea>
                            <input class="botao-enviar-comentario" type="submit" value="Enviar" />
                            <img class="icone-enviar-comentar" src="_imgs/200_s.gif" />
                            <span class="mensagem-comentar"></span>
                        </form>
                    </div>
                
			</div> <!--Container Post-->      
        </div>
   
        <div id="pre-footer">
	   		<img src="_imgs/rodape.png" />
        </div>
        </div>
        <footer id="rodape">
            <p>Copyright &copy; 2017 - by Void Main Company<p>
        </footer>
        
	</div><!--Div de interface (mãe de todas as outras)-->
</body>
</html> 
<?php
	if($codigo_usuario == $codigo_usuario_logado and isset($_GET["editar"])){
		echo"
			<script>
            	chamaModalEditar('editar',$codigo_postagem);
           	</script>";
	}
?>

