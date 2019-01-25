<?php

	session_start();//Inicia a sessão

	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
		require("_composicao_site/connection.php");//Conecta com o banco
		require("_composicao_site/script-funcoes.php");//Inclui script de funcoes

		$informacoes_do_usuario =  array_merge(pegaInformacoesUsuario());//Pegando informações do usuario e colocando-as em um vetor
		$foto_usuario = $informacoes_do_usuario['foto'];
		$nome_usuario = ucfirst(strtolower($informacoes_do_usuario['nome']));
		$email_usuario = $informacoes_do_usuario['email'];
		$codigo_usuario = $informacoes_do_usuario['codigo'];

		$_SESSION['email_logado'] = $email_usuario;
		$_SESSION['id_user'] = $codigo_usuario;

		$pegaUser = $pdo->prepare("SELECT * FROM `tb_usuarios` WHERE `email` = ?");
		$pegaUser->execute(array($_SESSION["email"]));
		$dadosUser = $pegaUser->fetch();

			date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário.
			$agora = date('Y-m-d H:i:s');
			$limite = date('Y-m-d H:i:s', strtotime('+2 min'));
			$update = $pdo->prepare("UPDATE `tb_usuarios` SET `horario` = ?, `limite` = ? WHERE `email` = ?");
			if($update->execute(array($agora, $limite, $email_usuario))){
				while($row = $pegaUser->fetchObject()){
					$_SESSION['email_logado'] = $email_usuario;
					$_SESSION['id_user'] = $codigo_usuario;
					header("Location:feed.php");
				}
			}

		//Configura toda a busca
		$_SESSION["comecoBusca"] = 0;//A busca começa no 0
		$tipoPost = !isset($_POST["tipoPost"]) || $_POST["tipoPost"] == ""?"naoInformado":$_POST["tipoPost"];//Pega o tipo de post buscado
		$especie = !isset($_POST["especie"]) || $_POST["especie"] == ""?"naoInformado":$_POST["especie"];//Pega a especie de animal buscada
		$porte = !isset($_POST["porte"]) || $_POST["porte"] == ""?"naoInformado":$_POST["porte"];//Pega o porte dos animais buscados
		$cor = !isset($_POST["cor"]) || $_POST["cor"] == ""?"naoInformado":$_POST["cor"];//Pega a cor dos animais buscados
		$sexo = !isset($_POST["sexo"]) || $_POST["sexo"] == ""?"naoInformado":$_POST["sexo"];//Pega o sexo de animais buscados

		$_SESSION["comentarios-feitos"] = 0;//Configura a variável de comentários anteriores na página para 0
	}else{//Se acontecer algo errado
		session_destroy();//A sessão é destruida
		header("Location: index.php");//dedireciona para a página de login
		exit;//Encerra o funcionamento da página atual
	}

	if(isset($_GET["sair"])){//Se o usuário clicou em sair
		session_destroy();//detroi a sessão
		header("Location: index.php");//redireciona para a pagina de login
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
    <title>Paws App | Feed</title>
    <link rel="shortcut icon" href="_imgs/logo5.png" type="image/x-icon" />
    <link href="_css/feed.css" rel="stylesheet" type="text/css" />
    <link href="_css/conteudo-popover.css" rel="stylesheet" type="text/css" />
    <link href="_css/editar-foto.css" rel="stylesheet" type="text/css" />
    <link href="_css/usuarios-container.css" rel="stylesheet" type="text/css" />
    <link href="_css/gifplayer.css" rel="stylesheet" type="text/css" />
    <link href="_css/estilo-baloes.css" rel="stylesheet" type="text/css" />
    <link href="_css/modal-confirmacao.css" rel="stylesheet" type="text/css" />
    <link href="_css/postagem.css" rel="stylesheet" type="text/css" />
    <link href="_css/formularios.css" rel="stylesheet" type="text/css" />
    <link href="_css/pesquisa.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="_script/jquery.js"></script>
    <script type="text/javascript" src="_script/script-feed.js"></script>
    <script type="text/javascript" src="_script/script-funcoes-post.js"></script>
    <script type="text/javascript" src="_script/jquery.gifplayer.js"></script>
    <script type="text/javascript" src="_script/jquery.cropit.js"></script>
    <script type="text/javascript" src="_script/ImageTools.js"></script>
    <script type="text/javascript" src="_script/mascara-input.js"></script>
    <script type="text/javascript" src="_script/script-formularios-editar-postar.js"></script>
		<script type="text/javascript" src="_script/script_chat.js"></script>
    <script type="text/javascript">
        $.noConflict();
    </script>
</head>
<body>
	<div id="interface"><!--Div de interface (mãe de todas as outras)-->
    	<div id="camada-fundo-site">
        	<div id="container-trocar-foto"><!-- Modal trocar foto -->
            	<div onclick="fecharModal()" class="fechar-container"></div>
                <form id="form-foto-desktop" onSubmit="return atualizaFotoPerfil(this)">
                	<div class="image-editor">
                    	<input id="input-foto" type="file" class="cropit-image-input" />
                        <input type="hidden" name="image-data" class="hidden-image-data" />
                        <input id="botao-selecionar-foto" type="button" value="Escolher Fotos" onclick="document.getElementById('input-foto').click();" />
                        <div class="cropit-preview">
                            <div id="container-botoes-girar">
                                <div class="rotate-ccw"></div>
                                <div class="rotate-cw"></div>
                            </div>
                        </div>
                        <div id="container-controle-tamanho">
							<div class="image-size-label">
                            	Tamanho:
                            </div>
                            <input type="range" class="cropit-image-zoom-input">
                        </div>
                        <div id="container-botoes-trocar">
                            <button class="botaos-trocar-foto" type="submit">Definir Foto</button>
                            <div onclick="fecharModal()" class="botaos-trocar-foto" type="none">Cancelar</div>
                        </div>
                         <input type="hidden" name="image-data" class="hidden-image-data" />
					</div>
                </form>
            </div><!-- Modal trocar foto -->

            <div id="container-filtrar"><!-- Modal filtrar -->
                <div onclick="fecharModal()" id="botao-fechar-filtrar"></div>
                <h1 class="titulo-modal">Filtrar</h1>
                <form method="post" id="form-filtrar">
                    <label>Tipo de Post:</label>
                    <select name="tipoPost" id="select-tipo-post" class="selects-filtrar">
                        <option value="" disabled selected>Escolha tipo de post...</option>
                        <option value="casual">Posts Casuais</option>
                        <option value="doacao">Animais Para Doação</option>
                        <option value="perdido">Animais Perdidos</option>
                        <option value="encontrado">Animais Encontrados</option>
                        <option value="naoInformado">Todos</option>
                    </select>
                    <div id="container_detalhes_filtrar">
                        <label>Especie:</label>
                        <select name="especie" id="select-especie-post" class="selects-filtrar">
                            <option value="" disabled selected>Escolha a especie...</option>
                            <option value="cachorro">Cachorro</option>
                            <option value="gato">Gato</option>
                            <option value="passaro">Pássaro</option>
                            <option value="outros">Outros</option>
                        </select>
                        <div style="display:none;" id="container-select-porte">
                            <label>Porte:</label>
                            <select name="porte" id="select-porte-post" class="selects-filtrar">
                                <option id="opcao-porte-default" value="naoInformado" disabled selected>Escolha o porte...</option>
                                <option value="grande">Grande</option>
                                <option value="medio">Médio</option>
                                <option value="pequeno">Pequeno</option>
                                <option value="naoInformado">Todos</option>
                            </select>
                        </div>
                        <label>Cor:</label>
                        <select name="cor" id="select-cor-post" class="selects-filtrar">
                            <option value="" disabled selected>Escolha o porte...</option>
                            <option value="preto">Preto</option>
                            <option value="branco">Branco</option>
                            <option value="listrado">Listrado</option>
                            <option value="naoInformado">Todos</option>
                        </select>
                        <label>Sexo:</label>
                        <select name="sexo" id="select-sexo-post" class="selects-filtrar">
                            <option value="" selected>Escolha uma opção:</option>
                            <option value="Fêmea">Fêmea</option>
                            <option value="Macho">Macho</option>
                            <option value="naoInformado">Ambos</option>
                        </select>
                    </div>
                    <div class="container-botoes-modal">
                        <input class="botoes-modal" id="botao-filtrar" type="submit" value="Filtrar" />
                        <div class="botoes-modal" onclick="fecharModal()">Cancelar</div>
                    </div>
                </form>
            </div><!-- Modal filtrar -->

            <div id="container-postar">
            	<div onclick="fecharModalPostar()" class="fechar-container"></div>
                <h1 class="titulo-modal">Postar</h1>
                <div id="container-postar-de-dentro">
                    <form id="form-postar" enctype="multipart/form-data" method="post" onSubmit="return postar(this)">
                        <div class="container-campos-postar">
                            <label class="label-postar" for="campo-titulo">Título/Nome Animal:</label>
                            <div class="divs-input">
                                <input class="input-postar" type="text" name="campoTitulo" id="campo-titulo" placeholder="Ex: Felix" />
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
                                    <option value="Ambos">Ambos</option>
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
                            <textarea name="campoDescricao" id="descricao-postagem" placeholder="Ex: Eu amo esse gato!!!"></textarea>
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
                        	<input type="submit"  class="botoes-modal" id="botao-postar" value="Postar" />
                        	<div class="botoes-modal" onclick="fecharModalPostar()">Cancelar</div>
                    	</div>
                    </form>

                </div>
            </div>
            <div id="container-confimacoes">
           		<div onclick="fecharModal()" id="botao-fechar-filtrar"></div>
                <h1 id="titulo-confirmacao" class="titulo-modal">Confirmação</h1>
                <p id="conteudo-confirmacao"></p>
                <form id="form-motivo-denuncia" method="post">
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

        </div>

        <header id="cabecario-site"><!--Cabeçario do site (mobile e desktop)-->
            <div id="barra"><!--Primeira barra do cabeçario (mobile e desktop)-->
                <a href="post.php"><img id="logo" src="_imgs/logo5.png" /></a>
                <div id="container-barra-pesquisa" class="elemento-pesquisa-desktop">
                    <form>
                        <input onKeyUp="pesquisar(this.value)" autocomplete="off" id="barra-de-pesquisa" type="text" placeholder="Pesquisar" class="elemento-pesquisa-desktop" />
                        <div id="icone-de-pesquisa" class="elemento-pesquisa-desktop"></div>
                    </form>
                    <div id="container-resultados-pesquisa-desktop" class="elemento-pesquisa-desktop">
                   		<div class="nenhum-pesquisa elemento-pesquisa-desktop">Pesquise algo!</div>
                        <img class="icone-grande-pesquisa-mobile elemento-pesquisa-desktop" src="_imgs/buscaper.png" />
                    </div>
                </div>
                <div onClick="confirmacao(this,'sair',0)" id="botao-sair">Sair</div>
                <!--<form method="post">
                    <input type="submit" name="sair" id="botao-sair" value="Sair" />
                </form>-->
                <ul id="lista-menu-desktop">
                	<li onClick="abrirContainerAplicacao(3)" id="botao-menu-desktop"></li>
                    <li onClick="abrirContainerAplicacao(1)" id="botao-chat-desktop"></li>
                    <li onClick="abrirContainerAplicacao(2)" id="botao-notificacao-desktop"></li>
                    <div id="container-menu-desktop-popover" class="popover-chat">
            			<div id="ponteiro-popover" class="ponteiro-notificacoes"></div>
                        <div id="conteudo-popover"></div>
                    </div>
                </ul>
            </div><!--Primeira barra do cabeçario (mobile e desktop)-->

            <div id="barra2"><!--Segunda barra do cabeçario (apenas mobile)-->
                <ul>
                    <li class="container-bt-mobile" id="primeiro-container-bt-mobile">
                        <img onClick="trocarDeAba(1)" id="bt-feed-mobile" src="_imgs/home.png" class="icones-menu-mobile" />
                    </li>
                    <li class="container-bt-mobile">
                        <img onClick="trocarDeAba(2)" id="bt-chat-mobile" src="_imgs/c.png" class="icones-menu-mobile" />
                    </li class="container-bt-mobile">
                    <li class="container-bt-mobile">
                        <img onClick="trocarDeAba(3)" id="bt-notificacao-mobile" src="_imgs/n.png" class="icones-menu-mobile" />
                    </li>
                    <li class="container-bt-mobile">
                        <img onClick="trocarDeAba(4)" id="bt-menu-mobile" src="_imgs/menu.png" class="icones-menu-mobile" />
                    </li>
                </ul>
            </div><!--Segunda barra do cabeçario (apenas mobile)-->
        </header><!--Cabeçario do site (mobile e desktop)-->

        <div id="visualizacao-mobile"><!--Container de todo o site mobile-->
        	<img class="gif-carregando" src="_imgs/200_s.gif" />
            <div class="visualizacoes" id="feed-mobile">
                <script>
                    jQuery(document).ready(function(){//Quando o documento carregar
						var valueScrollPluss = 0;
                        var larguraDaTela = jQuery(window).width();//Pega a largura de toda a tela
                        if(larguraDaTela < 960){//Se o site estiver aberto como mobile:
                            visualizarPostsMobile('<?php echo $tipoPost; ?>','<?php echo $especie; ?>','<?php echo $porte; ?>','<?php echo $cor; ?>','<?php echo $sexo; ?>');//Chama a função que carrega os posts mobile
							valueScrollPluss = 80;
						}else{
							//Se o site estiver aberto como desktop:
                            visualizarPostsDesktop('<?php echo $tipoPost; ?>','<?php echo $especie; ?>','<?php echo $porte; ?>','<?php echo $cor; ?>','<?php echo $sexo; ?>');
                        }
						jQuery(window).scroll(function() {//Quando o usuário da scroll na tela:
                            //console.log("Altura scroll: " + jQuery(window).scrollTop() + " Altura para acionar: " + (jQuery(document).height() - jQuery(window).height()))
							if((Math.ceil(jQuery(window).scrollTop()) + valueScrollPluss) >= Math.floor(jQuery(document).height() - jQuery(window).height())){//Se o scroll chegar no final da tela:
                                if(larguraDaTela < 960 && jQuery("#feed-mobile").is(":visible")){//Se o site estiver aberto como mobile e o usuário estiver com o feed aberto:
                                    visualizarPostsMobile('<?php echo $tipoPost; ?>','<?php echo $especie; ?>','<?php echo $porte; ?>','<?php echo $cor; ?>','<?php echo $sexo; ?>');//Chama a função que carrega os posts mobile
                                }else if(larguraDaTela >= 960 && jQuery("#container-postagem-feed").is(":visible")){
                                	visualizarPostsDesktop('<?php echo $tipoPost; ?>','<?php echo $especie; ?>','<?php echo $porte; ?>','<?php echo $cor; ?>','<?php echo $sexo; ?>');
                                }

							}
                        });
                    });
                </script>
                <header id="container-postar-enfeite-mobile">
                    <a href="perfil.php?codigo=<?php echo $codigo_usuario; ?>"><img class="fotos-perfil-feed-mobile foto-usuario-logado" src="<?php echo $foto_usuario; ?>" ></a>
                    <textarea onClick="abrirPostar()" id="campo-enfeite" placeholder="Gostaria de postar algo?"></textarea>
                </header>
                <div id="container-posts-mobile">
                </div>

            </div><!--Container do feed site mobile-->
            <div class="visualizacoes" id="pesquisa-mobile">
                <div class="nenhum-pesquisa">Pesquise algo!</div>
                <img class="icone-grande-pesquisa-mobile" src="_imgs/buscaper.png" />
            </div>

            <div class="visualizacoes" id="chat-mobile">
                Buscando...
            </div>
            <div class="visualizacoes" id="notificacoes-mobile">
                <div id="notificacoes-mobile-conteudo">
                Buscando...
                </div>
            </div>
            <div class="visualizacoes" id="menu-mobile">

            </div>
            <div class="visualizacoes" id="favoritos-mobile">
            	<header id="cabecario-visualizacao-mobile-favoritos">
                    <div id="voltar-feed-noticias-mobile"></div>
                    <h1>Meus Favoritos</h1>
            	</header>
            </div>
            <div class="visualizacoes" id="seguindo-mobile">
            	Buscando...
            </div>
            <div class="visualizacoes" id="bloqueados-mobile">
            	Buscando...
            </div>
        </div><!--Container de todo o site mobile-->

        <div id="visualizacao-desktop"><!--Container de todo o site desktop-->

            <menu id="menu-visualizacao-desktop">
            	<header>
                	<div id="container-foto-desktop">
                    	<img class="foto-usuario-logado" id="foto-menu-visualizacao-usuario" src="<?php echo $foto_usuario; ?>" />
            			<div id="fundo-foto-desktop">
                        	<span onclick=" abrirModalTrocarFoto()" id="editar-foto">Editar</span>
                        </div>
                    </div>
                    <a href="perfil.php?codigo=<?php echo $codigo_usuario; ?>"><span id="nome-usuario-menu-visualizacao"><?php echo $nome_usuario; ?></span></a>
                </header>
                <h2>O que procura?</h2>
                <ul id="lista-menu-visualizacao-desktop">
                	<li>
                    	<form method="post">
                            <input type="hidden" name="tipoPost" value="casual"/>
                            <input class="botoes-menu-visualizacao-desktop" type="submit" value="Casuais" />
            			</form>
                    </li>
                    <li>
                    	<form method="post">
                            <input type="hidden" name="tipoPost" value="doacao"/>
                            <input class="botoes-menu-visualizacao-desktop" type="submit" value="Doação" />
            			</form>
                    <li>
                    	<form method="post">
                            <input type="hidden" name="tipoPost" value="perdido"/>
                            <input class="botoes-menu-visualizacao-desktop" type="submit" value="Perdidos" />
                        </form>
                    </li>
                    <li>
                        <form method="post">
                            <input type="hidden" name="tipoPost" value="encontrado"/>
                            <input class="botoes-menu-visualizacao-desktop" type="submit" value="Encontrados" />
                        </form>
                    </li>
                    <li>
                    	<button class="botoes-menu-visualizacao-desktop" onClick="abrirBuscaPersonalizada()">Personalizada</button>
                    </li>
                </ul>
            </menu>

            <div id="container-visualizacoes-desktop">
                <div class="visualizacoes-desktop" id="container-postagem-feed">
                    <header id="cabecario-visualizacao-desktop">
                        <a href="perfil.php?codigo=<?php echo $codigo_usuario; ?>"><img id="foto-usuario-logado-desktop" class="foto-usuario-logado" src="<?php echo $foto_usuario; ?>" ></a>
                        <textarea onClick="abrirPostar()" id="campo-enfeite-desktop" placeholder="Gostaria de postar algo?"></textarea>
                    </header>
                    <div id="container-postagem-desktop"></div>
                </div>

                <div class="visualizacoes-desktop" id="container-postagem-favoritos">
                	<header id="cabecario-visualizacao-desktop-favoritos">
                        <div id="voltar-feed-noticias"></div>
                        <h1>Meus Favoritos</h1>
                    </header>
                </div>
            </div>
						<aside id="container_usuarios_chat">
									<?php
												$linha = "";
												$busca_seguidores_seguidos = $pdo->prepare("
													SELECT
															SS.codigo as 'codigo ligação',
															U.codigo,
															U.foto,
															CONCAT(U.nome,' ',U.sobrenome) as nome
													FROM tb_seguidor_seguido SS
													INNER JOIN tb_usuarios U
													ON U.codigo = SS.codSeguidor
													WHERE SS.codSeguido = $codigo_usuario
													and SS.codSeguidor IN
													(SELECT
													codSeguido
													FROM tb_seguidor_seguido SS
													WHERE codSeguidor = $codigo_usuario)");
												$busca_seguidores_seguidos->execute();
												while ($linha = $busca_seguidores_seguidos->fetch(PDO::FETCH_ASSOC)) {
														$codigo_seguidor = $linha["codigo"];
														$nome_seguidor = $linha["nome"];
														$foto_seguidor = $linha["foto"];
														echo "<div class='usuarios' id='$codigo_seguidor'";?> onClick="abreJanela(<?php echo $codigo_seguidor; ?>,'<?php echo $nome_seguidor; ?>')">
																	<?php echo"
																			<img class='fotos_usuarios' src='$foto_seguidor'/>
																			<span class='nome_usuarios'>$nome_seguidor</span>
																	</div>";
												}
												if($busca_seguidores_seguidos->rowCount() == 0){
													echo "<p class='no_friends'>Sem seguidores mútuos. Usuarios que se seguem mutuamente conseguem se comunicarem com o PawsApp Chat.</p>";
												}
									?>
						</aside>
						<aside id="container_conversas">

						</aside>
			</div><!--Container de todo o site desktop-->
	</div><!--Div de interface (mãe de todas as outras)-->
</body>
</html>
