<?php
	
	session_start();//Inicia a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		$informacoes_do_usuario =  array_merge(pegaInformacoesUsuario());//Pegando informações do usuario e colocando-as em um vetor
		$foto_usuario = $informacoes_do_usuario['foto'];
	}else{//Se acontecer algo errado
		exit;//Encerra o funcionamento da página
	}
	
?>
<header id="cabecario-menu-mobile">
    <img id="foto-usuario-menu-mobile" class="foto-usuario-logado" src="<?php echo $foto_usuario; ?>" />
    <a href="perfil.php?codigo=<?php echo $informacoes_do_usuario["codigo"]; ?>"><span id="nome-do-usuario-menu-mobile"><?php echo $informacoes_do_usuario["nome"]; ?></span></a>
    <img onclick=" abrirModalTrocarFoto()" id="icone-editar-foto-mobile" src="_imgs/editar-foto-icone.png" />
</header>
<div id="container-menu-mobile">
    <span class="titulos-itens-menu-mobile">Visualização</span>
    <ul>
        <li>
            <form method="post">
                <input type="hidden" name="tipoPost" value="casual"/>
                <input id="botao-casual-menu-mobile" class="botoes-menu-mobile" type="submit" value="Postagens Casuais" /> 
            </form>
        </li>
        <li>
            <form method="post">
                <input type="hidden" name="tipoPost" value="doacao"/>
                <input id="botao-adocao-menu-mobile" class="botoes-menu-mobile" type="submit" value="Animais para Doação" /> 
            </form>
        </li>
        <li>
            <form method="post">
                <input type="hidden" name="tipoPost" value="perdido"/>
                <input id="botao-perdidos-menu-mobile" class="botoes-menu-mobile" type="submit" value="Animais Perdidos" /> 
            </form>
        </li>
        <li>
            <form method="post">
                <input type="hidden" name="tipoPost" value="encontrado"/>
                <input id="botao-encontrados-menu-mobile" class="botoes-menu-mobile" type="submit" value="Animais Encontrados" /> 
            </form>
        </li>
        <li>
        	<button id="botao-personalizado-mobile" onClick="abrirBuscaPersonalizada()" class="botoes-menu-mobile">Busca Personalizada</button>
        </li>
    </ul>
    <span class="titulos-itens-menu-mobile">Conta</span>
    <ul>
        <li>
        	<button id="botao-favoritos-mobile" onClick="trocarDeAba(6)" class="botoes-menu-mobile">Favoritos</button>
        </li>
        <li>
        	<button id="botao-bloqueados-mobile" onClick="trocarDeAba(8)" class="botoes-menu-mobile">Bloqueados</button>
        </li>
        <li>
        	<button id="botao-seguindo-mobile" onClick="trocarDeAba(7)" class="botoes-menu-mobile">Seguindo</button>
        </li>
        <li>
        	<a href="conta.php">
        		<button id="botao-gerenciar-conta-mobile" class="botoes-menu-mobile">Gerenciar Conta</button>
        	</a>
        </li>
        <li>
        	<button id="botao-sair-mobile" onClick="confirmacao(this,'sair',0)" class="botoes-menu-mobile">Sair</button>
        </li>
    </ul>
</div>
            
            



