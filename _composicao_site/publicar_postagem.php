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
	
	 	if(verificaPalavroes($titulo)){//Verifica se o título tem palavrãs
			$input_de_erro = "titulo";
			$resposta = "Não permitido linguagem imprópria.";
		}else if(verificaPalavroes($localidade)){//Verifica se a localidade tem palavrãs
			$input_de_erro = "localidade";
			$resposta = "Não permitido linguagem imprópria.";
		}else if(verificaPalavroes($descricao)){//Verifica se a descrição tem palavrãs
			$input_de_erro = "descricao";
			$resposta = "Descrição contém linguagem imprópria remova e tente novamente.";
		}else{//Se os campos estiverem OK
			
			$resposta.="Título: $titulo, Tipo de postagem: $tipoPost, Descrição: $descricao, Mídia: $media, Espécie: $especie \n
			 Porte: $porte, Raça: $raca, Cor: $cor, Sexo: $sexo, Telefone: $telefone e Localidade $localidade";
		
			$cmd_publica_postagem = $pdo-> prepare("
			INSERT INTO tb_posts 
				VALUES
			(NULL,:codUsuario,:tipoPost,:imgPost,:dataPostagem,:descricaoPost,:nomeAnimal,
			:especieAnimal,:porteAnimal,:sexoAnimal,:cor,:raca,:contato,:localidade,'true');");
			$cmd_publica_postagem->bindParam(":codUsuario",$codigo_usuario_logado);
			$cmd_publica_postagem->bindParam(":tipoPost",$tipoPost);
			$cmd_publica_postagem->bindParam(":imgPost",$media);
			$cmd_publica_postagem->bindParam(":dataPostagem",$data_atual);
			$cmd_publica_postagem->bindParam(":descricaoPost",$descricao);
			$cmd_publica_postagem->bindParam(":nomeAnimal",$titulo);
			$cmd_publica_postagem->bindParam(":especieAnimal",$especie);
			$cmd_publica_postagem->bindParam(":porteAnimal",$porte);
			$cmd_publica_postagem->bindParam(":sexoAnimal",$sexo);
			$cmd_publica_postagem->bindParam(":cor",$cor);
			$cmd_publica_postagem->bindParam(":raca",$raca);
			$cmd_publica_postagem->bindParam(":contato",$telefone);
			$cmd_publica_postagem->bindParam(":localidade",$localidade);
			if($cmd_publica_postagem->execute()){
				$confirmacao = true;
				$resposta.= "Postado";
			}else{
				$confirmacao = 1;
				$resposta.= "Não Postado";
			}
		}
		
		echo json_encode(array("confirmacao" => $confirmacao,"mensagem_erro" => $mensagem_erro,"input_de_erro" => $input_de_erro,"codigo_postagem_publicada" => $codigo_postagem_publicada,"resposta" => $resposta));
		
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "ERRO | Usuário não esta logado corretamente.";
		exit;//Encerra o funcionamento da página atual
	}

?>