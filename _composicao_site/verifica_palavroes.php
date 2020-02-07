<?php
	
	session_start();//Começa a sessão para verificar as variáveis de login:
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Verifica se o usuários está logado:
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		//variáveis de resposta ao AJAX
		$confirmacao = true;
		$mensagem_erro = "";
		$input_de_erro = "";
		$resposta = "";
		
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
		}else{
			$confirmacao = false;
		}
		
		echo json_encode(array("confirmacao" => $confirmacao,"input_de_erro" => $input_de_erro,"resposta" => $resposta));
		
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "ERRO | Usuário não esta logado corretamente.";
		exit;//Encerra o funcionamento da página atual
	}

?>