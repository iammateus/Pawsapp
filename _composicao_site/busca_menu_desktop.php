<?php
	
	session_start();//Inicia a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		$informacoes_do_usuario =  array_merge(pegaInformacoesUsuario());//Pegando informações do usuario e colocando-as em um vetor
		$foto_usuario = !isset($informacoes_do_usuario['foto']) || $informacoes_do_usuario['foto'] == ""?"_imgs_users/empty.png":"_imgs_users/".$informacoes_do_usuario['foto'];
		
		$tipoPost = !isset($_POST["tipoPost"]) || $_POST["tipoPost"] == ""?"naoInformado":$_POST["tipoPost"];
		$especie = !isset($_POST["especie"]) || $_POST["especie"] == ""?"naoInformado":$_POST["especie"];
		$porte = !isset($_POST["porte"]) || $_POST["porte"] == ""?"naoInformado":$_POST["porte"];
		$cor = !isset($_POST["cor"]) || $_POST["cor"] == ""?"naoInformado":$_POST["cor"];
		$sexo = !isset($_POST["sexo"]) || $_POST["sexo"] == ""?"naoInformado":$_POST["sexo"];
	}else{//Se acontecer algo errado
		exit;//Encerra o funcionamento da página
	}
	
?>
<nav>
	<ul id="lista-menu-mobile-popover">
        <li onClick="buscaFavoritos(1)">
        	<img src="_imgs/desfavoritar.png" />
            <div class="container-descricao-icone-popover">
            	<span>Favoritos</span>
            </div>
        </li>
        <li onClick="buscaSeguindo(1)">
        	<img src="_imgs/seguindo.png" />
        	<div class="container-descricao-icone-popover">
            	<span>Seguindo</span>
            </div>
        </li>
        <li onClick="buscaBloqueados(1)">
        	<img src="_imgs/banido.png" />
        	<div class="container-descricao-icone-popover">
            	<span>Bloqueados</span>
            </div>
        </li>
        <a href="conta.php"><li>
        	<img src="_imgs/gereconta2.png" />
        	<div class="container-descricao-icone-popover">
            	<span>Gerenciar Conta</span>
            </div>
        </li>
        </a>
    </ul>
</nav>