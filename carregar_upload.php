<?php

session_start();//Inicia a sessão
	
if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
	
	require("_composicao_site/connection.php");//Conecta com o banco
	require("_composicao_site/script-funcoes.php");//Inclui script de funcoes
	
	$informacoes_do_usuario =  array_merge(pegaInformacoesUsuario());//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario['codigo'];
	$novoNomeFile = "_media_posts/".md5(time().generateRandomString(100).$codigo_usuario_logado);
	
	$fileName = $_FILES["file1"]["name"];//Nome do arquivo
	$fileTmpLoc = $_FILES["file1"]["tmp_name"];//Arquivo na pesta PHP temporaria
	$fileType = $_FILES["file1"]["type"];//O tipo de arquivo
	$fileSize = $_FILES["file1"]["size"];//O tamanho do arquivo em bytes
	$fileErrorMsg = $_FILES["file1"]["error"];//0 para falso 1 para verdadeiro
	
	$nome_ultimo_arquivo = $_POST["ultimoArquivo"];//Pega o nome do último arquivo
	
	if($nome_ultimo_arquivo <> ""){//Se a pessoa tinha upado algo antes é excluido
		unlink($nome_ultimo_arquivo);
		if(pegaExtensaoArquivo($nome_ultimo_arquivo) == ".gif"){
			unlink(str_replace("gif","png",$nome_ultimo_arquivo));
		}
	}
	
	if($fileType == "image/gif"){
		if(move_uploaded_file($fileTmpLoc, $novoNomeFile.".gif")){
			
			$image = imagecreatefromgif($novoNomeFile.".gif");
			
			imagepng($image, $novoNomeFile.".png");
			

		}else{
			//echo "move_uploaded_file function failed";
		}
		$novoNomeFile.=".gif";
	}elseif($fileType == "video/mp4"){
		if(move_uploaded_file($fileTmpLoc, $novoNomeFile.".mp4")){
			//echo "$fileName upload is complete";
		}else{
			//echo "move_uploaded_file function failed";
		}
		$novoNomeFile.=".mp4";		
	}else{
		if(move_uploaded_file($fileTmpLoc, $novoNomeFile.".jpg")){
			//echo "$fileName upload is complete";
		}else{
			//echo "move_uploaded_file function failed";
		}		
		$novoNomeFile.=".jpg";
	}

	echo $novoNomeFile;
		
}else{//Se acontecer algo errado
	session_destroy();//A sessão é destruida
	header("Location: index.php");//dedireciona para a página de login 
	exit;//Encerra o funcionamento da página atual
}







?>