<?php
	function pegaInformacoesUsuario(){//Função a pegar as informações do usuário e retornar uma vetor com elas

		include("connection.php");//Conecta com o banco
		$email = $_SESSION["email"];//Pega o email na session
		$senha = $_SESSION["senha"];//Pega a senha na session
		$permissao = $_SESSION["permissao"];//Pega a permissão na session

		//Comando para buscar as informações
		$select_informacoes = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email = :email and senha = :senha");
		$select_informacoes->bindParam(":email",$email);
		$select_informacoes->bindParam(":senha",$senha);
		$select_informacoes->execute();

		$informacoes = $select_informacoes->fetch();//Coloca as informações em um vetor

		return $informacoes;//Retorna o vetor
	}

	function pegaExtensaoArquivo($arquivo){//Função para pegar as estenção do arquivo

		$extencao = substr($arquivo,strlen($arquivo)-4,4);

		return $extencao;

	}

	function verificaPalavroes($string){

	   include("connection.php");//Conecta com o banco
       // Retira espaços, hífens e pontuações da String
       $arrayRemover = array( '.', '-', ' ' );
       $arrayNormal = array( "", "", "" );
       $normal = str_replace($arrayRemover, $arrayNormal, $string);

       // Remove os acentos da string
       $de = 'àáãâéêíóõôúüç';
       $para   = 'aaaaeeiooouuc';
       $string_final = strtr(strtolower($normal), $de, $para);

       // Array em Filtro de Palavrões
	   $busca_palavras = $pdo->query("SELECT conteudoObliquo FROM tb_palavrasdebaixocalao;");
	   $busca_palavras->execute();

	   while($matriz = $busca_palavras->fetch(PDO::FETCH_ASSOC)){
	      $array[] = $matriz["conteudoObliquo"];
	   }//Pegando todas a palavras não permitidas

	   $tem_palavrao = 0;

	   $tamanho_vetor = count($array)-1;

	   for($x = 0;$x <= $tamanho_vetor;$x++){
	       if(strpos($string_final,$array[$x]) !== false){
		       $tem_palavrao++;
		   }
	   }

	   if($tem_palavrao > 0){
           return true;
       } else {
           return false;
       }
   	}

	function buscaTempo($data){

		//Guardando data e hora atual:
		
		$data_atual = date('Y-m-d H:i:s');//Pega a hora atual.

		//Calculo de tempo de algo:
		$segundos = strtotime($data_atual) - strtotime($data);
		$minutos_restantes = intval(($segundos % 3600)/60);
		$horas = intval($segundos / 3600);

		if($horas < 1 && $minutos_restantes < 1){
			$descricao_tempo = "Bem agora";

		}else if($horas < 1 && $minutos_restantes >= 1){
			$descricao_tempo = $minutos_restantes."m";

		}else if($horas < 24){
			$descricao_tempo = $horas."h ".$minutos_restantes."m";

		}else if($horas >= 24 && $horas <= 48){
			$descricao_tempo = "Ontem";

		}else{
			$data = substr($data,0,10);
			$data = implode('/',array_reverse(explode('-',$data)));
			$descricao_tempo = $data;
		}

		return $descricao_tempo;
	}

	function generateRandomString($length = 10) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
    		}
   			 return $randomString;
		}

?>
