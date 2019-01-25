<?php
	session_start();//Inicia a sessão
	
	if(!isset($_SESSION["email"]) && !isset($_SESSION["senha"]) && !isset($_SESSION["permissao"])){//Verifica se o usuários está logado:
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		//variáveis de resposta ao AJAX
		$confirmacao = false;
		$mensagem_erro = "";
		$input_de_erro = "";
		$resposta = "";
		
		//Configura o fuso horário e pega a data e a hora
		date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
		$data_atual = date('Y-m-d');//Pega a hora atual
		$limite_tempo = date('Y-m-d H:i:s', strtotime('+1 min'));
		
		//Pega as variáveis para postagem
		$nome = isset($_POST["nome"])?trim($_POST["nome"]):"";
		$sobrenome = isset($_POST["sobrenome"])?trim($_POST["sobrenome"]):"";
		$dataNascimento = isset($_POST["dataNascimento"])?trim($_POST["dataNascimento"]):"";
		$dataNascimento = implode('-',array_reverse(explode('/',$dataNascimento)));
		$sexo = isset($_POST["sexo"])?trim($_POST["sexo"]):"";
		$cidade = isset($_POST["cidade"])?trim($_POST["cidade"]):"";
		$estado = isset($_POST["estado"])?trim($_POST["estado"]):"";
		$celular = isset($_POST["celular"])?trim($_POST["celular"]):"";
		$telefoneFixo = isset($_POST["telefoneFixo"])?trim($_POST["telefoneFixo"]):"";
		$email = isset($_POST["email"])?trim($_POST["email"]):"";
		$sexo = isset($_POST["sexo"])?trim($_POST["sexo"]):"";
		$senha = isset($_POST["senha"])?trim($_POST["senha"]):"";
		$senha = md5($senha);
		$foto = "_imgs_users/empty.png";
		
		
		if(verificaPalavroes($nome)){//Verifica se o título tem palavrãs
			$input_de_erro = "nome";
			$resposta = "Não permitido linguagem imprópria.";
		}else if(verificaPalavroes($sobrenome)){//Verifica se a localidade tem palavrãs
			$input_de_erro = "sobrenome";
			$resposta = "Não permitido linguagem imprópria.";
		}else if(verificaPalavroes($cidade)){//Verifica se a descrição tem palavrãs
			$input_de_erro = "cidade";
			$resposta = "Não permitido linguagem imprópria.";
		}else{//Se os campos estiverem OK
		
			$busca_disponibilidade = $pdo->prepare("SELECT COUNT(*) as disponibilidade FROM tb_usuarios WHERE email = :email");
			$busca_disponibilidade->bindParam(":email",$email);
			$busca_disponibilidade->execute();
			$busca_disponibilidade = $busca_disponibilidade->fetch();
			$email_disponivel = $busca_disponibilidade["disponibilidade"] < 1?1:0;
			if($email_disponivel){
				
				$cmd_cadastrar = $pdo-> prepare("
				INSERT INTO tb_usuarios 
					VALUES 
				(NULL,1,1,1,:nome,:sobrenome,:dataNascimento,:sexo,:foto,:telefoneFixo,:celular,
				:email,:senha,:cidade,:uf,:dataInscrico,'true',:horario,:limite,'');");
				$cmd_cadastrar->bindParam(":nome",$nome);
				$cmd_cadastrar->bindParam(":sobrenome",$sobrenome);
				$cmd_cadastrar->bindParam(":dataNascimento",$dataNascimento);
				$cmd_cadastrar->bindParam(":sexo",$sexo);
				$cmd_cadastrar->bindParam(":foto",$foto);
				$cmd_cadastrar->bindParam(":telefoneFixo",$telefoneFixo);
				$cmd_cadastrar->bindParam(":celular",$celular);
				$cmd_cadastrar->bindParam(":email",$email);
				$cmd_cadastrar->bindParam(":senha",$senha);
				$cmd_cadastrar->bindParam(":cidade",$cidade);
				$cmd_cadastrar->bindParam(":uf",$estado);
				$cmd_cadastrar->bindParam(":dataInscrico",$data_atual);
				$cmd_cadastrar->bindParam(":horario",$data_atual);
				$cmd_cadastrar->bindParam(":limite",$limite_tempo);
				if($cmd_cadastrar->execute()){
					$resposta.= "Cadastrado.";
					$_SESSION["email"] = $email;
					$_SESSION["senha"] = $senha;
					$_SESSION["permissao"] = 1;
					$confirmacao = true;
				}
			
			}else{
				$input_de_erro = "email";
				$resposta.= "Email não disponivel.";
			}
		
				
		}
		
		echo json_encode(array("confirmacao" => $confirmacao,"mensagem_erro" => $mensagem_erro,"input_de_erro" => $input_de_erro,"resposta" => $resposta));
		
	}
?>