<?php

session_start();//Inicia a sessão

if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["ultimaMedia"])){//Se o usuário estiver realmente logado

	$nome_ultimo_arquivo = $_POST["ultimaMedia"];//Pega o nome do último arquivo
	require("_composicao_site/script-funcoes.php");//Inclui script de funcoes
	if($nome_ultimo_arquivo <> ""){//Se a pessoa tinha upado algo antes é excluido
		unlink($nome_ultimo_arquivo);
		if(pegaExtensaoArquivo($nome_ultimo_arquivo) == ".gif"){
			if(unlink(str_replace("gif","png",$nome_ultimo_arquivo))){
				echo "deletados";
			}
		}
	}

}else{
	exit;
}

?>