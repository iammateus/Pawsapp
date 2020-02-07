<?php
	session_start();//Inicia a sessão
	if(isset($_SESSION["email"]) || isset($_SESSION["senha"]) || isset($_SESSION["permissao"])){//Se o usuário estiver logado
		
		if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
			header("Location: feed.php");//Redireciona para o Feed
			exit;//Encerra o funcionamento da página
		}else{//Se acontecer algo errado
			session_destroy();//A sessão é destruida
			header("Location: index.php");//recarrega a página
			exit;//Encerra o funcionamento da página
			
		}
		
	}else{//Se o usuario não estiver logado ele tem acesso permitido
		require("_composicao_site/connection.php");//Conecta com o banco

	}
?>

<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <!--<link rel="icon" href="_imgs/icones-menu/icone-do-site.ico" type="image/x-icon" />-->
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="keywords" content="Rede social adoção, adotar animais, encontrar animais">
	<meta name="robots" content="index, follow">
	<meta name="author" content="Void Main">
	<title>Paws App | Ínicio</title>
    <link rel="shortcut icon" href="_imgs/logo5.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="_css/tcc-style-comum.css"/>
    <link rel="stylesheet" type="text/css" href="_css/tcc-style-index.css"/>
    <link rel="stylesheet" type="text/css" href="_css/estilo-baloes.css"/>
    <link rel="stylesheet" type="text/css" href="_css/gifplayer.css"/>
    <link href="_css/formularios.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="_script/jquery.js"></script>
    <script type="text/javascript" src="_script/script-comum.js"></script>
    <script type="text/javascript" src="_script/script-index.js"></script>
    <script type="text/javascript" src="_script/jquery.gifplayer.js"></script>
</head>

<body>
<div id="interface">
	<!--Login-->
    <div id="fundo-logar">
    	<div id="container-logar">
        	<div id="fechar-logar"></div>
            <h2>Entrar</h2>
            <form id="form-logar" method="post" onSubmit="return entrar(this)">
            	<div class="divs-input">
                    <input id="email" type="email" placeholder="Email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" />
                    <div class="erro-input"> <!--Icone de erro, que engloba a descrição do aviso-->
                    	<span class="tooltiptext">Informe um email válido</span> <!--Descrição do aviso-->
                    </div>
                </div>
                <div class="divs-input">
                    <input id="senha" type="password" placeholder="Senha" minlength="8" name="senha" />
                    <div class="erro-input"> <!--Icone de erro, que engloba a descrição do aviso-->
                    	<span class="tooltiptext">Senha muito curta</span> <!--Descrição do aviso-->
                    </div>
                </div>
                <p id="avisos-logar"></p>
                <input id="botao-logar" type="submit" value="Entrar" />
                <span id="ou">ou</span>
                <a href="conta.php" id="botao-cadastrar">Cadastre-se</a>
                <a href="#" onClick="esqueceuSenha()" id="esqueceu-a-senha">Esqueceu sua senha?</a>
            </form>
        </div>
    </div>
	<nav id="menu-mobile">
    	<ul>
        	<li>Home</li>
            <li>Ongs</li>
            <li>Petshop</li>
            <li>Contato</li>
        </ul>
	</nav>
	<!--Configurações do cabeçario desktop(computador)-->
	<header id="cabecario-desktop">
    	 	<a href="#"><img id="logo" src="_imgs/logoteste.png" /></a>
            <nav id="menu">
                <button class="botoes-menu"><a href="#">Home</a></button>
                <button class="botoes-menu botoes-esquerda"><a href="#">Ongs</a></button>
        	</nav>
            <nav id="menu2"> 
                <button class="logar">Entrar</button>
                <button class="botoes-menu"><a href="#">Petshop</a></button>
                <button class="botoes-menu botoes-esquerda"><a href="#">Contato</a></button>
    		</nav>
            <img id="imagem-cachorro" src="_imgs/essabostadecapaperfeita.png" />
    </header>
    
    <!--Configurações do cabeçario mobile(celular-tablet)-->
    <header id="cabecario-mobile">
    	<img id="abrir-fechar-menu" src="_imgs/abrir-menu.png" />
        <button class="logar">Entrar</button>
    	<a href="#"><img id="logo-mobile" src="_imgs/logo-mobile-com-letas.png"/></a>
    </header>
    
    <div id="visualizacao">
    	<img id="subir-tela" class="desaparece" src="_imgs/arrow-big-circle-up-512.png"/>
    	<header id="cabecario-visualizacao-desktop">
        	<h1>Confira: </h1>
        	<ul class="linha">
            	<li class="coluna-posts-simples">
                	<a href="#"><div style="background-image:url(_imgs/saude.jpg)" class="posts-simples"></div></a>
                </li>
                <li class="coluna-posts-simples">
                	<a href="#"><div style="background-image:url(_imgs/comida.jpg)" class="posts-simples"></div></a>
                </li>
                <li class="coluna-posts-simples" id="terceiro-post-simples">
                	<a href="#"><div style="background-image:url(_imgs/lar.jpg)" class="posts-simples"></div></a>
                </li>
                <li class="coluna-posts-simples" >
                	<div class="posts-simples" id="post-cadastre-se">
                		<h1>Novo por aqui?</h1>
                        <p>Aqui você encontrará a melhor comunidade de pets da internet!</p>
                    	<button><a href="conta.php">Inscreva-se</a></button>
                	</div>
                </li>
            </ul>
        </header>
    	<div id="cabecaio-visualizacao">
        	<h1>Principais Publicações</h1>
            <nav id="opcoes-visualizacao">
				<form id="form-visualizacoes" method="post">
                	<input checked class="botoes-de-opcoes" type="checkbox" name="casual" id="casual" /><label for="casual">Casual</label><br />
                	<input checked class="botoes-de-opcoes" type="checkbox" name="doacao" id="doacao" /><label for="doacao">Animais para Doação</label><br />
                    <input checked class="botoes-de-opcoes" type="checkbox" name="perdidos" id="perdidos" /><label for="perdidos">Animais Perdidos</label><br />
                    <input checked class="botoes-de-opcoes" type="checkbox" name="encontrados" id="encontrados" /><label for="encontrados">Animais Encontraos</label><br />
                </form>
            </nav>
            <img id="ver-mais-ver-menos" src="_imgs/icone_ver_mais.png" />
        </div>
        <div id="visualizacao-posts">
        
         </div>
    
    </div>
    <div id="pre-footer">
   		<img src="_imgs/rodape.png" />
    </div>
    <footer id="rodape">
    	<p>Copyright &copy; 2017 - by Void Main Company<p>
    </footer>


</div>



</body>
</html>
