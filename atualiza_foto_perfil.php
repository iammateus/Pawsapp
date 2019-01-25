<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado e o codigo do comentario informado
		
		require("_composicao_site/connection.php");//Conecta com o banco
		require("_composicao_site/script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$encoded = $_POST['image-data'];//Imagem vem como um código
    	//explode at ',' - the last part should be the encoded image now
    	$exp = explode(',', $encoded);
    	//decode the image and finally save it
    	$data = base64_decode($exp[1]);

    	$nome_nao_usado = false;
		
		$nome_foto = "_imgs_users/".md5(time().generateRandomString(100).$codigo_usuario_logado).".jpg";

		if(file_put_contents($nome_foto, $data)){
			
			$busca_foto_antiga = $pdo -> prepare ("SELECT foto FROM tb_usuarios WHERE codigo = :codigoLogado");
			$busca_foto_antiga->bindValue("codigoLogado",$codigo_usuario_logado);
			$busca_foto_antiga->execute();
			$busca_foto_antiga = $busca_foto_antiga->fetch();
			$foto_antiga = $busca_foto_antiga["foto"];
			
			if($foto_antiga == "_imgs_users/empty.png"){
				$inserir_no_banco = $pdo->prepare("UPDATE tb_usuarios SET foto = :nomeFoto WHERE codigo = :codigoLogado");
				$inserir_no_banco->bindValue(":nomeFoto",$nome_foto);
				$inserir_no_banco->bindvalue("codigoLogado",$codigo_usuario_logado);
	
				if($inserir_no_banco->execute()){
					echo $nome_foto;
				}else{
					echo "erro";
				}
			}elseif(unlink($foto_antiga)){
				
				$inserir_no_banco = $pdo->prepare("UPDATE tb_usuarios SET foto = :nomeFoto WHERE codigo = :codigoLogado");
				$inserir_no_banco->bindValue(":nomeFoto",$nome_foto);
				$inserir_no_banco->bindvalue("codigoLogado",$codigo_usuario_logado);
	
				if($inserir_no_banco->execute()){
					echo $nome_foto;
				}else{
					echo "erro";
				}
				
			}else{
				echo "erro";
			}
		}
		
	}else{//Se o usuário não estiver logado ou o codigo do comentário
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>