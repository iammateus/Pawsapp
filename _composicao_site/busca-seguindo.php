<?php
	session_start();//Começa a sessão
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){//Se o usuário estiver realmente logado
	
		require("connection.php");//Conecta com o banco
		require("script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		
		$busca_existencia_seguindo = $pdo->query("SELECT COUNT(SS.codigo) quantidade FROM tb_seguidor_seguido SS INNER JOIN tb_usuarios U ON U.codigo = SS.codSeguido WHERE codSeguidor = $codigo_usuario_logado and U.ativo = 'true'");
		$busca_existencia_seguindo->execute();
		$busca_existencia_seguindo = $busca_existencia_seguindo->fetch();
		$busca_existencia_seguindo = $busca_existencia_seguindo["quantidade"];
		
		$tipo = $_POST["tipo"];
		
		echo "<header class='cabecario-seguindo-bloqueados'>";
		
		if($tipo == 1){
			
			echo "<div onClick='buscaMenuDesktop()' class='voltar-seguindo-bloqueado'></div>";
		
		}elseif($tipo == 2){
			
			echo "<div onClick='trocarDeAba(4)' class='voltar-seguindo-bloqueado'></div>";
		
		}
		
		echo "Seguindo";
		
		
		
		echo "</header>";
		
		if($busca_existencia_seguindo > 0){
			
			
			$busca_seguidos = $pdo->query(
			"SELECT
				U.codigo,
    			U.foto,
    			U.nome
			FROM tb_seguidor_seguido SS
			INNER JOIN tb_usuarios U 
			ON U.codigo = SS.codSeguido
			WHERE codSeguidor = $codigo_usuario_logado ");//and U.status = 1
			$busca_seguidos->execute();
			
			
			while($informacoes_usuario_seguido = $busca_seguidos->fetch(PDO::FETCH_ASSOC)){
				
				$codigo_usuario_seguido = $informacoes_usuario_seguido["codigo"];
				$foto_usuario_seguido = $informacoes_usuario_seguido["foto"];
				$nome_usuario_seguido = ucfirst(strtolower($informacoes_usuario_seguido["nome"]));
				
				echo "<div class='container-usuario'>
					      <a href='perfil.php?codigo=$codigo_usuario_logado'>
						      <img src='$foto_usuario_seguido' class='foto-usuarios' />
						      <span class='nome-usuarios'>$nome_usuario_seguido</span> 
					      </a>
						  <div  onClick=confirmacao(this,'deseguir',$codigo_usuario_seguido) class='icone-x'></div>
					  </div>";
				
			
			
			}
	
		
		}else{
			echo "<div class='nenhum'>
					 Você ainda não segue ninguém.
				  </div>";
		}	
		
		
	
	
	}
?>