<?php
	session_start();
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver logado
		
		exit;//Encerra o funcionamento da página
	
	}else{//Se o usuário não estiver logado:
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Conecta com o banco
		
		if(!isset($_POST["email"]) || !isset($_POST["senha"])){//Se algum dado do formulário por algum motivo não chegar
			
			echo "Informe os dados";//Mensagem de solicitar dados
		
		}else{//Se os dados chegarem
			
			//Pegando os horários de verificação
			date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
			$horario_atual = date('Y-m-d H:i:s');//Pega a hora atual
			$horario_antigo = date('Y-m-d H:i:s',strtotime("-10 minutes",strtotime($horario_atual)));//Pega o horário de 1 hora atrás

			//Pega as informações inseridas no formulário de login
			$email = $_POST["email"];
			$senha = md5($_POST["senha"]);
			
			//Busca possível bloqueio			
			$busca_bloqueio = $pdo->prepare("SELECT COUNT(*) quantidadeBloqueio FROM tb_bloqueio WHERE dataFinal > :dataatual and email = :email;");
			$busca_bloqueio->bindValue(":dataatual",$horario_atual);
			$busca_bloqueio->bindValue(":email",$email);
			$busca_bloqueio->execute();
			$vetor_quantidade = $busca_bloqueio->fetch();
			$quantidade_de_bloqueio = $vetor_quantidade["quantidadeBloqueio"];
			$tem_bloqueio = $quantidade_de_bloqueio > 0?true:false;
			
			if(!$tem_bloqueio){//Se o usuário não tiver bloqueio
				
				//verifica se a senha e o email estão corretos
				$busca_email = $pdo->prepare("SELECT COUNT(*) quantidade FROM tb_usuarios WHERE email = :email and senha = :senha;");
				$busca_email->bindValue(":email",$email);
				$busca_email->bindValue(":senha",$senha);
				$busca_email->execute();
				$quantidade = $busca_email->fetch();
				$email_valido = $quantidade['quantidade'];//retorna se achou algum email
				
				if($email_valido > 0){//Se a senha estiver senha (avhar alguma conta com a senha e o email informados)
					
					//verifica a permissao (comum/administradora)
					$busca_permissao = $pdo->prepare("SELECT permissao FROM tb_usuarios WHERE email = :email and senha = :senha;");
					$busca_permissao->bindValue(":email",$email);
					$busca_permissao->bindValue(":senha",$senha);
					$busca_permissao->execute();
					$quantidade = $busca_permissao->fetch();
					$permissao = $quantidade['permissao'];//Variável contendo a permissao
					
					//Loga o usuário
					$_SESSION["email"] = $email;
					$_SESSION["senha"] = $senha;
					$_SESSION["permissao"] = $permissao;
					
					if($permissao == 1){
						echo "1"; //Logado com sucesso (usuário comum) 
					}else{
						echo "2"; //Logado com sucesso (usuário administrador)
					}
					
				}else{//Se a senha ou o email informados estiverem errados
					
					//Grava a tentativa
					$inserir_tentativa = $pdo->prepare("INSERT INTO tb_tentativas (codigo, email, horario) VALUES (NULL, :email, :horaatual);"); 
					$inserir_tentativa->bindValue(":email",$email);
					$inserir_tentativa->bindValue(":horaatual",$horario_atual);
					$inserir_tentativa->execute();
					
					//Busca tentativas 
					$busca_tentativas = $pdo->prepare("SELECT COUNT(*) tentativas FROM tb_tentativas WHERE horario BETWEEN :horarioant and :horarioatual and email = :email;");
					$busca_tentativas->bindValue(":horarioant",$horario_antigo);
					$busca_tentativas->bindValue(":horarioatual",$horario_atual);
					$busca_tentativas->bindValue(":email",$email);
					$busca_tentativas->execute();
					//Coloca o resultado da busca em uma variável
					$vetor_quantidade = $busca_tentativas->fetch();
					$quantidade_de_tentativas = $vetor_quantidade['tentativas'];//quantidade de tentativas
					
					if($quantidade_de_tentativas > 9){//Se a pessoa tentou logar sem sucesso mais de 9 vezes em 5 minutos 
						
						$horario_mais_um_minuto = date('Y-m-d H:i:s',strtotime("5 minutes",strtotime($horario_atual)));//Pega o horário de 1 hora atrás
						//echo "Horario mais 5 minutos: $horario_mais_um_minuto";
						
						//Deleta os bloqueios anteriores
						$deleta_bloqueios = $pdo->prepare("DELETE FROM tb_bloqueio WHERE email = :email;");
						$deleta_bloqueios->bindValue(":email",$email);
						$deleta_bloqueios->execute();
				
						//Grava bloqueio de 5 minuto
						$inserir_bloqueio_de_acesso = $pdo->prepare("INSERT INTO pawsapp.tb_bloqueio (codigo, email, dataFinal) VALUES (NULL, :email, :horariobloqueio);");
						$inserir_bloqueio_de_acesso->bindValue(":email",$email);
						$inserir_bloqueio_de_acesso->bindValue(":horariobloqueio",$horario_mais_um_minuto);
						$inserir_bloqueio_de_acesso->execute();
						
					}
					
					echo "Email ou senha incorretos.";
					
				
				}
			
			}else{
				
				//Deleta as tentativas anteriores
				$deleta_tentativas = $pdo->prepare("DELETE FROM tb_tentativas WHERE email = :email;");
				$deleta_tentativas->bindValue(":email",$email);
				$deleta_tentativas->execute();
			
				//Select o final do bloqueio
				$final_do_bloqueio = $pdo->prepare("SELECT dataFinal final FROM tb_bloqueio WHERE email = :email;");
				$final_do_bloqueio->bindValue(":email",$email);
				$final_do_bloqueio->execute();
				$vetor_final_do_bloqueio = $final_do_bloqueio->fetch();
				$final_do_bloqueio = $vetor_final_do_bloqueio["final"];
				$tempo_espera = strtotime($final_do_bloqueio) - strtotime($horario_atual);//Calcula os segundos a esperar
				
				if($tempo_espera > 59){
					$segundos = strtotime($final_do_bloqueio) - strtotime($horario_atual);
					$tempo_espera = intval($segundos/60)." minutos ";
				}else{
					$tempo_espera.=" segundos";
				}
				echo "Excesso de tentativas $tempo_espera á esperar.";
			}
		}
		
			
	}
	
	

?>