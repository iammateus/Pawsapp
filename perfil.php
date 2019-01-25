<?php
	
	session_start();//Inicia a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
		require("_composicao_site/connection.php");//Conecta com o banco
		require("_composicao_site/script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario =  array_merge(pegaInformacoesUsuario());//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario['codigo'];
		$foto_usuario_logado = $informacoes_do_usuario['foto'];
		
		$codigo_usuario_perfil = isset($_GET["codigo"])?$_GET["codigo"]:-1;
		
		if(!($codigo_usuario_perfil == $codigo_usuario_logado)){
			$cmd_count_users = $pdo->prepare ("SELECT COUNT(*) as quantidade FROM tb_usuarios WHERE codigo = :codigo");
			$cmd_count_users->bindParam(":codigo",$codigo_usuario_perfil);
			$cmd_count_users->execute();
			$cmd_count_users = $cmd_count_users->fetch();
			$usuario = $cmd_count_users["quantidade"] > 0?true:false;
			if(!$usuario){
				header("Location: feed.php");//dedireciona para a página de login 
			}else{
				$cmd_informacao_user = $pdo->prepare ("SELECT * FROM tb_usuarios WHERE codigo = :codigo");
				$cmd_informacao_user->bindParam(":codigo",$codigo_usuario_perfil);
				$cmd_informacao_user->execute();
				$informacao_user = $cmd_informacao_user->fetch();
				
				$nome_usuario_perfil = ucfirst(strtolower($informacao_user['nome']));
				$vetor_sobrenome_perfil = explode(" ",$informacao_user['sobrenome']);
				$sobrenome_usuario_perfil = $vetor_sobrenome_perfil[0];
				$foto_usuario_perfil = $informacao_user["foto"];
			}
		}else{
			$nome_usuario_perfil = ucfirst(strtolower($informacoes_do_usuario['nome']));
			$vetor_sobrenome_perfil = explode(" ",$informacoes_do_usuario['sobrenome']);
			$sobrenome_usuario_perfil = $vetor_sobrenome_perfil[0];
			$foto_usuario_perfil = $informacoes_do_usuario["foto"];
		}
	}else{//Se acontecer algo errado
		session_destroy();//A sessão é destruida
		header("Location: index.php");//dedireciona para a página de login 
		exit;//Encerra o funcionamento da página atual
	}
	
	if(isset($_POST["sair"])){//Se o usuário clicou em sair
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
    <title>Paws App | <?php echo $nome_usuario_perfil." ".$sobrenome_usuario_perfil; ?></title>
    <link rel="shortcut icon" href="_imgs/logo5.png" type="image/x-icon" />
    <link href="_css/perfil.css" rel="stylesheet" type="text/css" />
    <link href="_css/editar-foto.css" rel="stylesheet" type="text/css" />
    <link href="_css/gifplayer.css" rel="stylesheet" type="text/css" />
    <link href="_css/estilo-baloes.css" rel="stylesheet" type="text/css" />
    <link href="_css/modal-confirmacao.css" rel="stylesheet" type="text/css" />
    <link href="_css/postagem.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="_script/jquery.js"></script>
    <script type="text/javascript" src="_script/script-perfil.js"></script>
    <script type="text/javascript" src="_script/script-funcoes-post.js"></script>
    <script type="text/javascript" src="_script/jquery.gifplayer.js"></script>
    <script type="text/javascript" src="_script/jquery.cropit.js"></script>
    <script type="text/javascript" src="_script/mascara-input.js"></script>
    <script type="text/javascript">
        $.noConflict();
    </script>
</head>
<body>
	<div id="interface"><!--Div de interface (mãe de todas as outras)-->
    	<header id="cabecario-interface">
        	<br/>
        	<img id="imagem-logo" src="_imgs/logo_white.png" />
  			<div id="container-foto-nome-usuario">
        		<img id="imagem-user-perfil-grande" src="<?php echo $foto_usuario_perfil; ?>" />
                <h1 id="nome-usuario-perfil"><?php echo $nome_usuario_perfil." ".$sobrenome_usuario_perfil; ?></h1>
            	<div id="container_botoes">
                	<?php
						if($codigo_usuario_logado != $codigo_usuario_perfil){
							
							$verifica_seguindo = $pdo->prepare("SELECT COUNT(*) quantidade FROM tb_seguidor_seguido WHERE codSeguidor = $codigo_usuario_logado and codSeguido = :codigoPerfil");
							$verifica_seguindo->bindValue(":codigoPerfil",$codigo_usuario_perfil);
							$verifica_seguindo->execute();
							$verifica_seguindo = $verifica_seguindo->fetch();
							$verifica_seguindo = $verifica_seguindo["quantidade"];
							
							echo "<button onClick='seguirNaoSeguir(this,$codigo_usuario_perfil)' class='botoes_perfil'";
							if($verifica_seguindo){
								echo "title='Não Seguir'>-";
							}else{
								echo "title='Seguir Usuário'>+";
							}
							echo "</button>";
							echo"
							<button class='botoes_perfil' title='Denunciar Usuário'>&#9873;</button>
							";
						}else{
							echo"
							<button class='botoes_perfil' title='Editar'>✏</button>
							<button class='botoes_perfil' title='Seguidores'>👤</button>
							";
						}
					?>
                </div>
            </div>
        </header>
        <div id="container-inforcoes-perfil">
        </div>
        <div id="container-bg">
        	<a href="feed.php"><img id="voltar" src="_imgs/voltar-feed.png"/></a>
            <div id="container-posts">
            <br>
            <script>
                jQuery(document).ready(function(){
						jQuery('body').scrollTop(0);
						var larguraDaTela = jQuery(window).width();
						if(larguraDaTela < 900){
							buscaPostagens(<?php echo $codigo_usuario_perfil; ?>,1)
						}else{//Se o site estiver aberto como desktop:
							buscaPostagens(<?php echo $codigo_usuario_perfil; ?>,2)
						}
						jQuery(window).scroll(function() {//Quando o usuário da scroll na tela:
							if(Math.ceil(jQuery(window).scrollTop()) == Math.floor(jQuery(document).height() - jQuery(window).height())){//Se o scroll chegar no final da tela:
								if(larguraDaTela < 960){//Se o site estiver aberto como mobile e o usuário estiver com o feed aberto:
									buscaPostagens(<?php echo $codigo_usuario_perfil; ?>,1)
								}else{
									buscaPostagens(<?php echo $codigo_usuario_perfil; ?>,2)
								}
							}
						});
                });
            </script>
            </div>
		<div>
        <div id="pre-footer">
            <img src="_imgs/rodape.png" />
        </div>
        <footer id="rodape">
            <p>Copyright &copy; 2017 - by Void Main Company<p>
        </footer>
    </div><!--Div de interface (mãe de todas as outras)-->
</body>
</html> 


