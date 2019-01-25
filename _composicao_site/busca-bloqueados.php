<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
	
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		
		$busca_existencia_bloqueados = $pdo->query("SELECT COUNT(B.codigo) quantidade FROM tb_bloqueio_usuario B INNER JOIN tb_usuarios U ON U.codigo = B.codBloqueado WHERE codUsuario = $codigo_usuario_logado and U.ativo = 'true'");// and U.status = 1
		$busca_existencia_bloqueados->execute();
		$busca_existencia_bloqueados = $busca_existencia_bloqueados->fetch();
		$busca_existencia_bloqueados = $busca_existencia_bloqueados["quantidade"];
		
		$tipo = $_POST["tipo"];
		
		echo "<header class='cabecario-seguindo-bloqueados'>";
		
		if($tipo == 1){
			
			echo "<div onClick='buscaMenuDesktop()' class='voltar-seguindo-bloqueado'></div>";
		
		}elseif($tipo == 2){
			
			echo "<div onClick='trocarDeAba(4)' class='voltar-seguindo-bloqueado'></div>";
		
		}
		
		echo "Bloqueados";
		
		
		
		echo "</header>";
		
		if($busca_existencia_bloqueados > 0){
			
			
			$busca_bloqueados = $pdo->query(
			"SELECT
				U.codigo,
				U.foto,
				U.nome
			FROM tb_bloqueio_usuario B
			INNER JOIN tb_usuarios U 
			ON U.codigo = B.codBloqueado
			WHERE codUsuario = $codigo_usuario_logado and U.ativo = 'true' ");//and U.status = 1
			$busca_bloqueados->execute();
			
			while($informacoes_usuario_bloqueado = $busca_bloqueados->fetch(PDO::FETCH_ASSOC)){
				
				$codigo_usuario_bloqueado = $informacoes_usuario_bloqueado["codigo"];
				$foto_usuario_bloqueado = $informacoes_usuario_bloqueado["foto"];
				$nome_usuario_bloqueado = ucfirst(strtolower($informacoes_usuario_bloqueado["nome"]));
				
				echo "<div class='container-usuario'>
					      <img src='$foto_usuario_bloqueado' class='foto-usuarios' />
						  <span class='nome-usuarios'>$nome_usuario_bloqueado</span> 
					  	  <div  onClick=confirmacao(this,'desbloquear',$codigo_usuario_bloqueado) class='icone-x'></div>
					  </div>";
				
			}
	
		
		}else{
			echo "<div class='nenhum'>
					 Não há usuários bloqueados.
				  </div>";
		}	
		
		
	
	
	}
?>