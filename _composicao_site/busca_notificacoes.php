<?php

	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado:
		
		//Requerindo algumas páginas: 
		require("connection.php");//Conecta com o banco.
		require("script-funcoes.php");//Inclui script de funcoes.
		
		//Guardando data e hora atual:
		date_default_timezone_set('America/Sao_Paulo');//Configura o fuso horário.
		$data_atual = date('Y-m-d H:i:s');//Pega a hora atual.
		
		//Guardando informações do usuário
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor.
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];//Pegando o código do usuário do vetor e colocando numa variável
		
		//Busca notificações do usuário.
		$busca_notificacoes = $pdo->prepare(
		"SELECT 
		 	N.codigo,
			N.codRecebedor,
			N.codRemetente,
			N.codPost,
			N.tipo,
			N.descricao,
			N.foiLido,
			N.dataHora,
			R.foto,
			R.ativo
		FROM tb_notificacoes N
		INNER JOIN tb_usuarios R
		ON R.codigo = N.codRemetente
		WHERE codRecebedor = :codigoUsuario and R.ativo = 'true'
		ORDER BY N.dataHora DESC
		LIMIT 0, 20");
		$busca_notificacoes->bindParam("codigoUsuario",$codigo_usuario_logado);
		$busca_notificacoes->execute();
		
		echo "<header class='containeropcoes-notificacao'>
			  	  <a href='#' onClick='marcarNotificacoesComoLidas()'>
				      Marcar todas como lidas
				  </a>
			  </header>";
		
		
		
		//Repetição para mostrar as notificações na tela
		while($informacoes_notificacoes = $busca_notificacoes->fetch(PDO::FETCH_ASSOC)){
			
			//Pega código, código do rementete, código da postagem, tipo de notificação, descrição, se foi lido, data e hora da postagem, foto do remetente do vetor do while
			$codigo_notificacao = $informacoes_notificacoes["codigo"];
			$codigo_remetente = $informacoes_notificacoes["codRemetente"];
			$codigo_post_notificacao = $informacoes_notificacoes["codPost"];
			$tipo_notificacao = $informacoes_notificacoes["tipo"];
			$descricao = $informacoes_notificacoes["descricao"];
			$foi_lido = $informacoes_notificacoes["foiLido"];
			$data_hora_notificacao = $informacoes_notificacoes["dataHora"];
			$foto_remetente = $informacoes_notificacoes["foto"];
			
			//Pega a classe a ser usada para estilizar a cor das notificações
			$classe_lido_ou_nao_lido = $foi_lido ? "lido" : "nao-lido";
			
			//Pega o tempoDaNotificação
			$tempo_notificacao = buscaTempo($data_hora_notificacao);
			
			//Escreve o html da notificação
			if($tipo_notificacao == "comentario" || $tipo_notificacao == "favorito"){
				$link_notificacao = "post.php?codigo=$codigo_post_notificacao";
				$funcao_notificacao = "";
			}else if($tipo_notificacao == "seguir"){
				$link_notificacao = "usuario.php?codigo=$codigo_remetente";
				$funcao_notificacao = "";
			}else{
				$link_notificacao = "#";
				$funcao_notificacao = "onClick='gravaNotificacaoLida(this,$codigo_notificacao)'";
			}
			
			echo "<a href='$link_notificacao'>
					<div $funcao_notificacao class='notificacoes $classe_lido_ou_nao_lido'>
				     	<img class='remetente-notificacao' src='$foto_remetente' />
					  	<div class='container-descricao-notificacao'>
					    	<p>$descricao</p>
					  	</div>
					  	<span class='tempo-notificacao'>$tempo_notificacao</span>
			      	</div>
				  </a>";
			
			
		}
	
	}else{//Se o usuário não estiver logado:
		echo "Usuário não está logado / Erro";
		exit;//Encerra o funcionamento da página atual.
	}

?>