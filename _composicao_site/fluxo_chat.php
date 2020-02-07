<?php
  header('Content-type: application/json');
	session_start();//Começa a sessão

	require("connection.php");//Conecta com o banco
	require("script-funcoes.php");//Inclui script de funcoes

	$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
	$codigo_usuario_logado = $informacoes_do_usuario["codigo"];

  //Busca quantos favoritos essa busca alcançará
	$busca_mensagens = $pdo->prepare("SELECT M.*, CONCAT(U.nome,' ',U.sobrenome) as 'nome' FROM tb_mensagens M INNER JOIN tb_usuarios U ON U.codigo = M.id_de  WHERE id_para = :logado and lido = 0");
  $busca_mensagens->bindValue(":logado",$codigo_usuario_logado);
	$busca_mensagens->execute();

  $visualiza = $pdo->prepare("UPDATE tb_mensagens SET lido = 1 WHERE id_para = :logado and lido = 0");
  $visualiza->bindValue(":logado",$codigo_usuario_logado);
	$visualiza->execute();



	while($linha = $busca_mensagens->fetch(PDO::FETCH_ASSOC)){

			$codigo_enviante = $linha["id_de"];
			$mensagem = $linha["mensagem"];
      $nome = $linha["nome"];
      $codigoMensagem = $linha["codigo"];

      $novasMensagens[] = array(
				'codigo' => $codigo_enviante,
				'mensagem' => utf8_encode($mensagem),
        'nome' => $nome,
        'codigoMensagem' => $codigoMensagem
			);
    }

    if(isset($novasMensagens)){
        echo json_encode(array("mensagens"=>$novasMensagens, "confirmacao"=>true));
    }else{
        echo json_encode(array("confirmacao"=>false));
    }
?>
