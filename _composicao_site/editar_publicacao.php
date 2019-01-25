<?php
	
	session_start();//Começa a sessão para verificar as variáveis de login:
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Verifica se o usuários está logado:
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		//variáveis de resposta ao AJAX
		$confirmacao = 0;
		$mensagem_erro = "";
		$input_de_erro = "";
		$codigo_postagem_publicada = "";
		$resposta = "";
		
		//Pega informações do usuário
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		
		//Configura o fuso horário e pega a data e a hora
		date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
		$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
		
		//Pega as variáveis para postagem
		$codigo = isset($_POST["codigo"])?trim($_POST["codigo"]):"";
		$titulo = isset($_POST["titulo"])?trim($_POST["titulo"]):"";
		$tipoPost = isset($_POST["tipoPost"])?trim($_POST["tipoPost"]):"";
		$descricao = isset($_POST["descricao"])?trim($_POST["descricao"]):"";
		$media = isset($_POST["media"])?trim($_POST["media"]):"";
		$especie = isset($_POST["especie"])?trim($_POST["especie"]):"";
		$porte = isset($_POST["porte"])?trim($_POST["porte"]):"";
		$raca = isset($_POST["raca"])?trim($_POST["raca"]):"";
		$cor = isset($_POST["cor"])?trim($_POST["cor"]):"";
		$sexo = isset($_POST["sexo"])?trim($_POST["sexo"]):"";
		$telefone = isset($_POST["telefone"])?trim($_POST["telefone"]):"";
		$localidade = isset($_POST["localidade"])?trim($_POST["localidade"]):"";
		
		$cmd_busca_postagem = $pdo->prepare("SELECT COUNT(*) quantidade FROM tb_posts WHERE codigo = :codigoPostagem and codUsuario = $codigo_usuario_logado;");
		$cmd_busca_postagem->bindParam(":codigoPostagem",$codigo);
		$cmd_busca_postagem->execute();
		$cmd_busca_postagem = $cmd_busca_postagem->fetch();
		$e_dono_da_postagem = $cmd_busca_postagem["quantidade"] > 0?true:false;
		
		if($e_dono_da_postagem){
			
			$cmd_busca_media_postagem = $pdo->prepare("SELECT imgPost FROM tb_posts WHERE codigo = :codigoPostagem;");
			$cmd_busca_media_postagem->bindParam(":codigoPostagem",$codigo);
			$cmd_busca_media_postagem->execute();
			$cmd_busca_media_postagem = $cmd_busca_media_postagem->fetch();
			$media_antiga = $cmd_busca_media_postagem["imgPost"];
		
			if($media_antiga != $media && $media_antiga != ""){
				unlink("../".$media_antiga);
				if(pegaExtensaoArquivo($media_antiga) == ".gif"){
					unlink(str_replace("gif","png","../".$media_antiga));
				}
			}
			
			$cmd_update = $pdo->prepare("
			UPDATE `tb_posts` 
			SET 
			`tipoPost`= :tipoPost,
			`imgPost`= :imgPost,
			`descricaoPost`= :descricaoPost,
			`nomeAnimal`= :nomeAnimal,
			`especieAnimal`= :especieAnimal,
			`porteAnimal`= :porteAnimal,
			`sexoAnimal`= :sexoAnimal,
			`cor`= :cor,
			`raca`= :raca,
			`contato`= :contato,
			`localidade`= :localidade
			 WHERE codigo = :codigoPost;");
			$cmd_update->bindParam(":codigoPost",$codigo);
			$cmd_update->bindParam(":tipoPost",$tipoPost);
			$cmd_update->bindParam(":imgPost",$media);
			$cmd_update->bindParam(":descricaoPost",$descricao);
			$cmd_update->bindParam(":nomeAnimal",$titulo);
			$cmd_update->bindParam(":especieAnimal",$especie);
			$cmd_update->bindParam(":porteAnimal",$porte);
			$cmd_update->bindParam(":sexoAnimal",$sexo);
			$cmd_update->bindParam(":cor",$cor);
			$cmd_update->bindParam(":raca",$raca);
			$cmd_update->bindParam(":contato",$telefone);
			$cmd_update->bindParam(":localidade",$localidade);
			if($cmd_update->execute()){
				echo "Alterado";
			}else{
				echo "Erro ao alterado";
			}
					
		}else{
			echo "Não é dono da postagem";	
		
		}
	
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "ERRO | Usuário não esta logado corretamente.";
		exit;//Encerra o funcionamento da página atual
	}

?>