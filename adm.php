<?php

session_start();
	
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"]) && $_SESSION['permissao'] == 2 ){
		include("conectar.php");
	}else{
		header("Location: index.php");
		exit;		
	}

		if(isset($_POST['btn_sair'])){
			session_destroy();
			header("Location: index.php");
			exit;
		}
	?>
    
    <?php
			if(isset($_POST['btn_ignorar'])){

					$codigoDenuncia = $_POST['btn_ignorar'];
					 mysql_query("DELETE FROM `tb_denuncias` WHERE `tb_denuncias`.`codigo` = $codigoDenuncia");
					 
					 
					 echo"<script language='javascript' type='text/javascript'>window.location.href='adm.php';</script>";
					}
				if(isset($_POST['btn_bloquearC']) && $_SESSION['permissao'] == 2){
					$codigoDenuncia = $_POST['btn_bloquearC'];
					mysql_query("UPDATE `tb_comentarios` SET `ativo` = 'false' WHERE `tb_comentarios`.`codigo` = $codigoDenuncia;");
					
					}
				if(isset($_POST['btn_bloquearU']) && $_SESSION['permissao'] == 2){
					$codigoDenuncia = $_POST['btn_bloquearU'];
					mysql_query("UPDATE `tb_usuarios` SET `ativo` = 'false' WHERE `tb_usuarios`.`codigo` = $codigoDenuncia;");
					
					}
				if(isset($_POST['btn_bloquearP']) && $_SESSION['permissao'] == 2){
					$codigoDenuncia = $_POST['btn_bloquearP'];
					mysql_query("UPDATE `tb_posts` SET `ativo` = 'false' WHERE `tb_posts`.`codigo` = $codigoDenuncia;");
					
					}
					
			?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Administrador | PawsApp </title>
<script type="text/javascript" src="_script/scriptAdm.js"></script>
<script type="text/javascript" src="_script/jquery.js"></script>
<link rel="icon" href="_imgs/logoadm.png" type="image/x-icon" />
<link href="estilao.css" rel="stylesheet" type="text/css" />
</head>

<body>

<header class="cab">
    <img id="logoadm" src="_imgs/logoadm.png" />
    <img id="menuadm" src="_imgs/menuadm.png" />
    <form method="post">
    	<input type="submit" name="btn_sair" id="sair" value="Sair" />	
    </form>
    
    
    
</header>



	<div class="menulateral">
        <div id="foto-adm"> <img src="_imgs/admfoto.png" style="max-width: 100%; border-radius:50%;"></div>
        <h1>Administrador </h1>
        <form method="GET">
             <ul>
               <li>
               		<form method="GET">
               			<input type="submit" name="menufiltrar" value="Perfil"  id="opcoes-perfil-lateral"/>
            		</form>
               </li>
               
               <li>
               		<form method="GET">
               			<input type="submit" name="menufiltrar" value="Post"  id="opcoes-perfil-lateral"/>
            		</form>
               </li>
               
               <li>
               		<form method="GET">
               			<input type="submit" name="menufiltrar" value="Comentários"  id="opcoes-perfil-lateral"/>
            		</form>
               </li>
              
              <!-- <li><input type="submit"  name="menufiltrar"  value="iconpost" style="width:100%; height:100%;" /></li>
               <li> <input type="submit"  name="menufiltrar"  value="iconcoment" style="width:100%; height:100%;"/></li>-->
             </ul>
         </form>
	</div>

<form class="menufiltrar" method="GET">
	<input type="submit" name="menufiltrar" value="iconperfil"  id="iconperfil" src="_imgs/perfilicon.png" /> 
    <input type="submit"  name="menufiltrar"  value="iconpost" id="iconpost" src="_imgs/iconpost.png" />
    <input type="submit"  name="menufiltrar"  value="iconcoment" id="iconcoment" src="_imgs/iconcomentario.png" />
</form>


		<div id="postagens">
        <?php

        if(!isset($_GET['menufiltrar']) || $_GET['menufiltrar'] == "iconpost"  || $_GET['menufiltrar'] == "Post" ){
            $busca = 1;
        }elseif($_GET['menufiltrar'] == "iconperfil" || $_GET['menufiltrar'] == "Perfil"){
            $busca = 2;

        }elseif($_GET['menufiltrar'] == "iconcoment" || $_GET['menufiltrar'] == "Comentários"){
            $busca = 3;
        }
		
		 if(!isset($_GET['opcoes']) == "ignorarDenuncia"){							
			//mysql_query("DELETE FROM `tb_denuncia` WHERE `tb_denuncia`.`Codigo` = $codigo");                            
        }elseif($_GET['opcoes'] == "BloquearDenuncia"){
					//mysql_query("DELETE FROM `tb_usuario` WHERE `tb_usuario`.`Codigo` = $codigo");
		}

					include('conectar.php');

                    if($busca == 1){
						//$postagem = mysql_query("SELECT * FROM `tb_posts`");
						//select * from tb_denuncias INNER JOIN tb_posts ON tb_posts.codigo = tb_denuncias.codElemento
						$postagem = mysql_query("select tb_denuncias.codigoDenunciado, tb_posts.imgPost,tb_posts.tipoPost, tb_posts.descricaoPost, tb_posts.nomeAnimal, tb_usuarios.nome, tb_usuarios.sobrenome, tb_usuarios.foto, tb_denuncias.motivo, tb_denuncias.codigo AS 'codigoDenuncia',tb_denuncias.codElemento from tb_denuncias INNER JOIN tb_posts ON tb_posts.codigo = tb_denuncias.codElemento INNER JOIN tb_usuarios ON tb_usuarios.codigo = tb_posts.codUsuario");
						
						if(mysql_num_rows($postagem) > 0){
						
						
						
						while($post = mysql_fetch_array($postagem)){
							$tipoPost = $post['tipoPost'];
							$imgPost = $post['imgPost'];
							$descricaoPost = $post['descricaoPost'];
							$nomeAnimal = $post['nomeAnimal'];
							$nomeUsuario = $post['nome']. " " . $post['sobrenome'];
							$fotoUsuario = $post['foto'];
							$motivo = $post['motivo'];
							$codigo = $post['codigoDenuncia'];
							$codDenunciado = $post['codigoDenunciado'];
							
							
					?>     
    		<div class="post">
        		<div class="cab-post">
            		<div class="foto-usuario-post"> <img style="max-width: 100%; border-radius:50%; " src=<?php echo $fotoUsuario; ?>>  </div>
                	<div class="info-post">
                   		 <a href="perfil.php?codigo=<?php echo($codDenunciado); ?>"><h1><?php  echo $nomeUsuario;  ?></h1></a>
                		 <h2><?php  echo $tipoPost; ?></h2>
                         <h2> <span class="motivo">Motivo:</span> <?php echo $motivo; ?></h2>
                	</div>
                 	<div class="opcoes-post">
                 		<form  method="post">
                          <input type="submit" name="btn_ignorar" value=<?php echo($post['codigoDenuncia']);?> class="ignorar-post" >
                          <input type="submit" name="btn_bloquearP" value=<?php echo($post['codElemento']);?> class="bloquear-post">
                        </form>
                    </div>
            </div>
            <div class="foto-post"><img style="max-width: 100%; width:98%;margin: 0px 0px 1% 1%;" src=<?php echo ($imgPost); ?>></div>
          </div>
        <?php    } // fim do primeiro while
			}else{//fim do if count rowelse
				echo "Sem Posts denunciados";
			}
             }  //Fim do primeiro IF

            elseif($busca == 2){
						//select * from tb_denuncias INNER JOIN tb_usuarios ON tb_usuarios.codigo = tb_denuncias.codElemento
						 $usuarios = mysql_query("select tb_usuarios.nome,tb_denuncias.tipoDenunciado, tb_usuarios.sobrenome, tb_usuarios.foto, tb_denuncias.motivo, tb_denuncias.codigoDenunciado, tb_denuncias.codigo from tb_denuncias INNER JOIN tb_usuarios ON tb_usuarios.codigo = tb_denuncias.codigoDenunciado where tb_denuncias.tipoDenunciado = 'perfil'");
                       // $usuarios = mysql_query("SELECT * FROM `tb_usuarios`");

                        while($usuario = mysql_fetch_array($usuarios)){
                            
                             $nomeUsuario = $usuario['nome'] . " " . $usuario['sobrenome'];
                             $fotoUsuario = $usuario['foto'];
							 $motivo = $usuario['motivo'];
							 $codigo = $usuario['codigoDenunciado'];
							 $codigoDenuncia = $usuario['codigo'];

        ?>
       <div class="usuario">
        	<div class="opcoes-usuario">
                        <!--style="width:40%; height:100%;"-->
                        <form  method="post">
                         	<input type="submit" name="btn_ignorar" value=<?php echo($codigoDenuncia);?> class="ignorar-usuario" >
                            <input type="submit" name="btn_bloquearU" value=<?php echo($codigo);?> class="bloquear-usuario">
                        </form>
                        
                        
                        
                    </div> <!--fim da div opcoes-usuario-->
        	<div class="cab-usuario">
            	<div class="img-usuario">  <img style="width:100%; height:100%;margin:-36.5% 0% 0% -8% ; max-width: 100%; border-radius: 50%" src=<?php echo($fotoUsuario); ?>  > </div>
                <div class="nome-usuario"><a href="perfil.php?codigo=<?php echo($codigo); ?>"> <h1><?php echo($nomeUsuario); ?> </h1> </a></div>
                <div class="motivo-usuario"> <h2> <span class="motivo">Motivo:</span> <?php echo $motivo; ?></h2> </div>
            </div> <!--FIm div cab-usuario-->
        </div><!-- fim div usuarios-->
        
        
		<?php 
            } // fim do segundo while
        }// Fim do segundo IF
           
            elseif($busca == 3){
						//
                       // $comentarios = mysql_query("SELECT * FROM `tb_comentarios` INNER JOIN `tb_usuarios` ON tb_usuarios.codigo=tb_comentarios.codUsuario");
						//select * from tb_denuncias INNER JOIN tb_comentarios ON tb_comentarios.codigo = tb_denuncias.codElemento INNER JOIN `tb_usuarios` ON tb_usuarios.codigo=tb_comentarios.codUsuario
						
						 $comentarios = mysql_query("select tb_denuncias.codigo,tb_denuncias.motivo, tb_denuncias.codElemento, tb_denuncias.codigoDenunciado, tb_usuarios.nome, tb_usuarios.sobrenome, tb_usuarios.foto, tb_comentarios.conteudo from tb_denuncias INNER JOIN tb_comentarios ON tb_comentarios.codigo = tb_denuncias.codElemento INNER JOIN `tb_usuarios` ON tb_usuarios.codigo=tb_comentarios.codUsuario");
                        while($comentario = mysql_fetch_array($comentarios)){
                            
                            $nomeUsuario = $comentario['nome'] . " " . $comentario['sobrenome'];
                            $fotoUsuario = $comentario['foto'];
							$conteudo = $comentario['conteudo'];
							$motivo = $comentario['motivo'];
							$codigo = $comentario['codigo'];
							$codElemento = $comentario['codElemento'];
							$codigoDenunciado = $comentario['codigoDenunciado'];

        ?>

        <div class="conteiner-comentarios">
        	<div class="foto-usuario-comentario"><img style="width:100%; height:100%;margin:2% 0% 0% 10% ; max-width: 100%; border-radius: 50%" src=<?php echo($fotoUsuario); ?> ></div>
             <div class="comentario">
             	<div class="cab-comentario"> 
                    <div class="nome-comentario"><a href="perfil.php?codigo=<?php echo($codigoDenunciado); ?>"><h1> <?php echo($nomeUsuario); ?> </h1></a> </div>
                </div><!-- fim da div cab-comentario-->
                <div class="opcoes-comentario">
                         <form  method="post">
                         	<input type="submit" name="btn_ignorar" value=<?php echo($codigo);?> class="ignorar-comentario" >
                            <input type="submit" name="btn_bloquearC" value=<?php echo($codElemento);?> class="bloquear-comentario">
                        </form>
                    </div><!--Fim da div opcoes-comentario-->
                <div class="comentario-denunciado">
                	<h2> <span class="motivo">Motivo:</span> <?php echo $motivo; ?></h2>
                	<p> <?php echo($conteudo); ?> </p>
                </div> <!-- Fim da div comentario-denunciado -->
             </div><!--Fim da div comentario-->
        </div> <!--Fim da div conteiner-comentarios-->
        <?php }// fim do terceiro while
			}// fim do terceiro if?>
            
            
    </div>

</body>
</html>