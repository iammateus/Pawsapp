<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoPostagem"]) && isset($_POST["pagina"])){//Se o usuário estiver realmente logado e os dados necessários informados
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
		$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_postagem = $_POST["codigoPostagem"];
		$pagina = $_POST["pagina"];
		$inicio = (5 * $pagina) - 5;
		$comentarios_feitos = $_SESSION["comentarios-feitos"];		
		$busca_comentarios=$pdo->prepare(
		"SELECT 
			C.codigo,
			C.codUsuario,
			C.codPostagem,
			C.conteudo,
			C.dataHora,
			U.foto,
			U.nome
		 FROM tb_comentarios C
		 INNER JOIN tb_usuarios U
		 ON U.codigo = C.codUsuario
		 WHERE codPostagem = :codPostagem and C.ativo 'true'
		 ORDER BY C.dataHora
		 LIMIT $inicio, 5");
		 $busca_comentarios->bindParam(":codPostagem",$codigo_postagem);
		 $busca_comentarios->execute();
		 
		 
					
		 while($linha2 = $busca_comentarios->fetch(PDO::FETCH_ASSOC)){
			$codigo_comentario = $linha2["codigo"];
			$codigo_dono_comentario = $linha2["codUsuario"];
			$conteudo_comentario = $linha2["conteudo"];
			$foto_dono_comentario = $linha2['foto'];//Pega a foto do usuário e guarda em uma variável
			$nome_dono_comentario = $linha2['nome'];
							
			//Verifica se o usuário já denunciou antes
			$busca_denuncia_comentario = $pdo->prepare("SELECT COUNT(*) quantidade FROM `tb_denuncias` WHERE codigoDenunciado = :codComentario AND codDenunciador = :codUsuario AND tipoDenunciado = 'comentario'");
			$busca_denuncia_comentario->bindParam(":codUsuario",$codigo_usuario_logado);
			$busca_denuncia_comentario->bindParam(":codComentario",$codigo_comentario);
			$busca_denuncia_comentario->execute();
			$busca_denuncia_comentario = $busca_denuncia_comentario->fetch();
			$denuncias_realizadas = $busca_denuncia_comentario["quantidade"];
			
			$busca_bloqueio= $pdo->query("SELECT COUNT(*) quantidade FROM `tb_bloqueio_usuario` WHERE (codUsuario = $codigo_usuario_logado AND codBloqueado = $codigo_dono_comentario) or (codUsuario = $codigo_dono_comentario AND codBloqueado = $codigo_usuario_logado)");
			$busca_bloqueio->execute();
			$busca_bloqueio = $busca_bloqueio->fetch();
			$bloqueios = $busca_bloqueio["quantidade"];
			
			$data_comentario = $linha2["dataHora"];
			$segundos_de_comentario = strtotime($data_atual) - strtotime($data_comentario);//Calcula a diferença da hora atual até a hora que o comentario foi inserido no banco
			$tempo_de_cometario = buscaTempo($data_comentario);
			
			if(($comentarios_feitos < 1 || $codigo_dono_comentario <> $codigo_usuario_logado || ($codigo_dono_comentario == $codigo_usuario_logado and $segundos_de_comentario > 300)) && $denuncias_realizadas < 1 && $bloqueios < 1){//Se a busca retornar 0 é porque ele não denunciou então o mesmo pode denunciar
				
				echo "<div class='comentarios'>
						  <div class='container-detalhes-dono-mensagem'>
							  <a href='perfil.php?codigo=$codigo_dono_comentario'>
							  	  <img class='foto-dono-mensagem' src='$foto_dono_comentario' />
								  <span class='nome-comentador'>$nome_dono_comentario</span>
							  </a>
						 	  <span class='tempo-comentario'>$tempo_de_cometario</span>
						  </div>
						  <div class='conteudo-comentario'>
						  	  <p>$conteudo_comentario</p>
						  </div>
						  <div class='container-opcoes-comentarios'>
							  <ul>
							      <li onClick='ocultarComentario(this)'>Ocultar</li>";
								      if($codigo_dono_comentario == $codigo_usuario_logado){
									      echo "<li onClick=confirmacao(this,'excluircomentario',$codigo_comentario)>Excluir</li>";
									  }else{
										  echo "<li onClick=confirmacao(this,'denunciarcomentario',$codigo_comentario)>Denunciar</li>";
									  }
						echo"</ul>
						  </div>					
					</div>";
					}
				}
						
				$busca_comentarios=$pdo->prepare("SELECT COUNT(*) quantidade FROM tb_comentarios WHERE codPostagem = :codPostagem");
				$busca_comentarios->bindParam(":codPostagem",$codigo_postagem);
				$busca_comentarios->execute();
				$busca_comentarios = $busca_comentarios->fetch();
				$busca_comentarios = $busca_comentarios["quantidade"];
				
				if($busca_comentarios > ($inicio+5)){
					echo "<a style='display:none;'>continuaBusca</a>";
				}
		
		
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>