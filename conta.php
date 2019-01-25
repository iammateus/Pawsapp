<?php
	session_start();
	if(isset($_SESSION["email"]) && isset($_SESSION["senha"]) && isset($_SESSION["permissao"])){
		require("_composicao_site/connection.php");//Conecta com o banco
		require("_composicao_site/script-funcoes.php");//Inclui script de funcoes
		
		$informacoes_do_usuario = pegaInformacoesUsuario();//Pegando informações do usuario e colocando-as em um vetor
		$codigo_usuario_logado = $informacoes_do_usuario["codigo"];
		$data_nascimento = implode('/', array_reverse(explode('-', $informacoes_do_usuario["dataNascimento"])));
		
		$esta_logado = true;
	}else{
		$esta_logado = false;
	}
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="Rede social adoção, adotar animais, encontrar animais">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Void Main">
    <title>Paws App | 
    <?php
    	if($esta_logado){
			echo "Gerenciar Conta";
		}else{
			echo "Cadastro";
		}
    
    ?>
    </title>
    <link rel="shortcut icon" href="_imgs/logo5.png" type="image/x-icon" />
    <link rel="stylesheet" href="_css/estilocadastro.css"/>
    <link rel="stylesheet" href="_css/modal-confirmacao.css"/>
    <link rel="stylesheet" href="_css/estilo-baloes.css"/>
    <script type="text/javascript" src="_script/jquery.js"></script>
    <script type="text/javascript" src="_script/mascara-input.js"></script>
    <script src="_script/script-cadastrar.js"></script>
</head>

<body>
	<div id="interface">
    	<div id="camada-fundo-site">
        	<div id="container-confimacoes">
           		<div onclick="fecharModal()" id="botao-fechar-filtrar"></div>
                <h1 id="titulo-confirmacao" class="titulo-modal">Confirmação</h1>
                <p id="conteudo-confirmacao"></p>
                <div id="opcoes-confirmacao" class="container-botoes-modal">
                	<div id="botao-confirma" class="botoes-modal" onclick="">Confirmar</div>
                    <div class="botoes-modal" onclick="fecharModal()">Cancelar</div>
               	</div>
          	</div>
        </div>
    	<header id="cabecalho">
        	<a href="index.php"><img src="_imgs/logoteste.png" id="logo"></a>
            <h1 id="titulo">
            	<?php
					if($esta_logado){
						echo "Gerenciar Conta";
					}else{
						echo "Cadastrar";
					}
    
    			?>
            </h1>
        </header>
        <!-- Inicio do formulario -->
        <form id="form-cadastrar" method="post" 
        <?php 
			if($esta_logado){
				echo "onSubmit='return confirmacaoValidacaoEditarCadastrar(this,".'"editar"'.");'";
			}else{
				echo "onSubmit='return validacaoCadastrar(this);'";
			}
		?>
        />
        
        
                	<fieldset id="fild-dados-pessoais">
            	<label class="descricao-grupo-input">Dados Pessoais</label><br/>
                
                <div class="container-dupla">
                	<div class="divs-input">
                    	<input class="input-postar inputTextoOnly" type="text" name="nome" id="nome" placeholder="Nome: " 
                        <?php 
							if($esta_logado){
								echo "value='".$informacoes_do_usuario["nome"]."'";
							}
                        ?>
                        />
                        <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                        	<span class="tooltiptext"></span> <!--Descrição do aviso-->
                        </div>
					</div>
                    
                    <div class="divs-input">
                    	<input class="input-postar inputTextoOnly" type="text" id="sobrenome" name="sobrenome" placeholder="Sobrenome: " 
                        <?php 
							if($esta_logado){
								echo "value='".$informacoes_do_usuario["sobrenome"]."'";
							}
                        ?>
                        />
                        <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                        	<span class="tooltiptext"></span> <!--Descrição do aviso-->
						</div>
					</div>
				</div>
                    
				<div class="divs-input div-input-pequeno">
                	<input id="dataNasc" class="input-postar" type="text" name="dataNasc" placeholder="Data de nascimento: " 
                    <?php 
						if($esta_logado){
							echo "value='".$data_nascimento."'";
						}
                    ?>
                    />
                    <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                    	<span class="tooltiptext"></span> <!--Descrição do aviso-->
					</div>
				</div>
                
                <fieldset id="sexos">
                	<input name="sexo" type="radio" id="feminino" value="F" /><label for="feminino">Feminino</label>
                	<input name="sexo" type="radio" id="masculino" value="M"/><label for="masculino">Masculino</label>
                	<div class="erro-input-esquerda esquerda-menor" id="aviso-sexo"> <!--Icone de erro, que engloba a descrição do aviso-->
                    	<span class="tooltiptext"></span> <!--Descrição do aviso-->
					</div>
                </fieldset>
        	</fieldset>  
                
			<fieldset id="fild-localidade">
            	<label class="descricao-grupo-input">Localidade</label><br/>
                <div class="container-dupla">
                	<div class="container-selects-postar">
                		<select class="selects-postar" id="uf" name="UF">
                        	<option value="" disabled selected>Estado:</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AM">AM</option>
                            <option value="AP">AP</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MG">MG</option>
                            <option value="MS">MS</option>
                            <option value="MT">MT</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="PR">PR</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="RS">RS</option>
                            <option value="SC">SC</option>
                            <option value="SE">SE</option>
                            <option value="SP">SP</option>
                            <option value="TO">TO</option>
						</select>
                    </div>
                        
					<div class="divs-input">
                    	<input class="input-postar inputTextoOnly" type="text" id="cidade" name="cidade" placeholder="Cidade: " 
                        <?php 
							if($esta_logado){
								echo "value='".$informacoes_do_usuario["cidade"]."'";
							}
                    	?>
                        />
                    	<div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                    		<span class="tooltiptext"></span> <!--Descrição do aviso-->
                    	</div>
                    </div>
				</div>
            </fieldset>
                
			<fieldset id="fild-contatos">
            	<label class="descricao-grupo-input">Contatos</label><br/>
                <div class="container-dupla">
                	<div class="divs-input div-input-pequeno">
                    	<input id="telefoneFixo" class="input-postar" type="text" name="telefoneFixo" placeholder="Telefone: " 
                        	<?php 
								if($esta_logado){
									echo "value='".$informacoes_do_usuario["telefoneFixo"]."'";
								}
                    		?>
                        />
                        <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                        	<span class="tooltiptext"></span> <!--Descrição do aviso-->
                        </div>
                    </div>
                    
                    <div class="divs-input div-input-pequeno">
                    	<input id="celular" class="input-postar" type="text" name="celular" placeholder="Celular: " 
                        	<?php 
								if($esta_logado){
									echo "value='".$informacoes_do_usuario["celular"]."'";
								}
                    		?>
                        />
                        <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                        	<span class="tooltiptext"></span> <!--Descrição do aviso-->
						</div>
					</div>
				</div>
                
                <div id="container-email" class="divs-input">
                	<input id="email" class="input-postar" type="text" name="email" placeholder="E-mail: " pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" 
                    	<?php 
							if($esta_logado){
								echo "value='".$informacoes_do_usuario["email"]."'";
							}
                    	?>
                    />
                    <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                    	<span class="tooltiptext"></span> <!--Descrição do aviso-->
                    </div>
				</div>
                
                <?php 
					if($esta_logado){
						echo 
						"
						<div id='container-senha-atual' class='divs-input'>
                			<input id='senha-atual' class='input-postar' type='password' name='senhaAtual' placeholder='Senha atual: '/>
                    		<div class='erro-input-esquerda esquerda-menor'>
                    			<span class='tooltiptext'></span>
                    		</div>
						</div>
						";
					}
                ?>
                
                <div class="container-dupla">
                	<div id="container-status-senha">
                    	<div id="status-senha" class="weak">
                        </div>
                    </div>
                	<div class="divs-input">
                    	<input id="senha" class="input-postar" type="password" 
						<?php
							if($esta_logado){ echo "placeholder='Nova Senha: '";}else{ echo "placeholder='Senha: '";}
						?>
                        " name="senha" />
                        <div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                        	<span class="tooltiptext"></span> <!--Descrição do aviso-->
                        </div>
					</div>
                        
                    <div class="divs-input">
                    	<input id="confirmarSenha" class="input-postar" type="password" name="confirmarSenha" placeholder="Confirmar Senha: " />
						<div class="erro-input-esquerda esquerda-menor"> <!--Icone de erro, que engloba a descrição do aviso-->
                        	<span class="tooltiptext"></span> <!--Descrição do aviso-->
                        </div>
					</div>
				</div>
                <ul id="password-tips">
                	<li>Use uma senha de 8 ou mais dígitos</li>
                    <li>Use números ou símbulos</li>
                    <li>Use letras maiúculas e minúsculas</li>
                    <li>Evite usar a mesma senha em muitos sites</li>
                </ul>
            </fieldset>
            <div class="container-mensagens">
            	<span id="avisos-cadastrar-descricao"></span>
            	<div id="carregando-cadastrar-container">
                	<img id="carregando-cadastrar" src="_imgs/200_s.gif" />
                	<p>Carregando...</p>
            	</div> 
            </div>
            <div id="container-botoes">
            	<input class="botoes-cadastrar"  type="submit" name="btnCadastrar"
                <?php
					if($esta_logado){
						echo "value='Editar'";
					}else{
						echo "value='Cadastrar'";
					}
				?>
                />
                <div 
                <?php
					if($esta_logado){
						echo "onClick='voltar(true)'";
					}else{
						echo "onClick='voltar(false)'";
					}
				?>            
                class="botoes-cadastrar">Voltar</div>
			</div>
		</form>
        <?php
			if($esta_logado){
				echo "<script>
					  	jQuery(document).ready(function(){
							preencheFormulario('".$informacoes_do_usuario["sexo"]."','".$informacoes_do_usuario["uf"]."');
						});
					  </script>";
			}
		?>
    </div>
</body>
</html>