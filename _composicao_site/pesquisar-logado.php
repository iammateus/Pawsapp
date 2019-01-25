<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && isset($_POST["textoPesquisa"])){//Se o usuário estiver realmente logado e os dados necessários informados
		
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$contadora = 0;
		
		$texto = $_POST["textoPesquisa"];
		$texto = trim($texto);
		$pesquisa = $pdo -> prepare ("SELECT codigo, concat(nome,' ',sobrenome) as nome , foto FROM tb_usuarios WHERE concat(nome,' ',sobrenome) like concat(:texto, '%') and ativo = 'true' LIMIT 0,10");
		$pesquisa->bindParam(":texto",$texto);
		$pesquisa->execute();
		
		while($informacoes_usuario_pesquisa = $pesquisa->fetch(PDO::FETCH_ASSOC)){
			
			$codigo_pesquisado = $informacoes_usuario_pesquisa["codigo"];
			$nome_pesquisado = $informacoes_usuario_pesquisa["nome"];
			$foto_pesquisado = $informacoes_usuario_pesquisa["foto"];
			
			$busca_bloqueio= $pdo->query("SELECT COUNT(*) quantidade FROM `tb_bloqueio_usuario` WHERE (codUsuario = $codigo_usuario_logado AND codBloqueado = $codigo_pesquisado) or (codUsuario = $codigo_pesquisado AND codBloqueado = $codigo_usuario_logado)");
			$busca_bloqueio->execute();
			$busca_bloqueio = $busca_bloqueio->fetch();
			$bloqueios = $busca_bloqueio["quantidade"];
			
			if($bloqueios < 1){
				$contadora++;
				echo "<a href='perfil.php?codigo=$codigo_pesquisado'> 
					      <div class='elemento-pesquisa'>
						      <img class='foto-usuario-pesquisa' src='$foto_pesquisado' />
						  	  <span class='nome-usuario'>$nome_pesquisado<span>
						  </div>
				     </a>";
			}
		}
		if($contadora < 1){
			echo "<div class='nenhum elemento-pesquisa-desktop'>Nenhum Resultado Encontrado</div>";
		}
		
		
	}else{//Se o usuário não estiver logado ou os dados não foram informados
		echo "Dados não informados / Erro";
		exit;//Encerra o funcionamento da página atual
	}

?>