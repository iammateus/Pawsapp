<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["codigoPostagem"])){//Se o usuário estiver realmente logado e os dados necessários informados
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário 
		$data_atual = date('Y-m-d H:i:s');//Pega a hora atual
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$codigo_postagem = $_POST["codigoPostagem"];
				
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
		 WHERE codPostagem = :codPostagem AND codUsuario = :codigoUsuario 
		 ORDER BY C.dataHora DESC
		 LIMIT 0, 1");
		 $busca_comentarios->bindParam(":codPostagem",$codigo_postagem);
		 $busca_comentarios->bindParam(":codigoUsuario",$codigo_usuario_logado);
		 $busca_comentarios->execute();
					
		$linha2 = $busca_comentarios->fetch(PDO::FETCH_ASSOC);
		$codigo_comentario = $linha2["codigo"];
		$codigo_dono_comentario = $linha2["codUsuario"];
		$conteudo_comentario = $linha2["conteudo"];
		$foto_dono_comentario = $linha2['foto'];//Pega a foto do usuário e guarda em uma variável
		$nome_dono_comentario = $linha2['nome'];
							
		
				$data_comentario = $linha2["dataHora"];
				$tempo_de_cometario = buscaTempo($data_comentario);
				
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
					              <li onClick='ocultarComentario(this)'>Ocultar</li>
								  <li onClick=confirmacao(this,'excluircomentario',$codigo_comentario)>Excluir</li>
					          </ul>
					      </div>					
				 	  </div>";
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>