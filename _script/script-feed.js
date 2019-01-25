// JavaScript Document
/*Codigo do formulário de postar e editar postagem daqui para baixo ----------------------------------------------------------------------*/
jQuery(document).ready(function() {
	/*Configurando animações do input*/
	jQuery(".input-postar").on("focusin",function(){
		jQuery(this).parent(".divs-input").css("border-color","#11b5bf");
	});	
	jQuery(".input-postar").on("focusout",function(){
		jQuery(this).parent(".divs-input").css("border-color","rgba(0,0,0,0.7)");
	});
	
	jQuery("#campo-titulo").on("focusin",function(){
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		jQuery(elemento).fadeOut(300);
	});
	
	jQuery("#campo-titulo").on("focusout",function(){
		var titulo = cleanStr(jQuery(this).val());
		if(titulo.length < 1 || (titulo.length === 1 && titulo.search(" ") === 0 )){
			var elemento = jQuery(this).siblings(".erro-input-esquerda");
			jQuery(elemento).children(".tooltiptext").html("Campo Obrigatório!");
			jQuery(elemento).fadeIn(300);
			jQuery(this).parent().css("border-color","#d72828");
		}
	});
	
	//Campo selecionar tipo postagem
	var contadoraTipoPost = 0;
	jQuery("#select-tipoPost").on("click",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		var valorElemento = jQuery(this).val();
		if(contadoraTipoPost == 0){
			jQuery(elemento).removeClass("erro-input-selects").addClass("aviso-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Atenção ao escolher o tipo de postagem.");
			jQuery(elemento).css("display","inline-block");
			contadoraTipoPost++;
		}else if(valorElemento != "casual" && valorElemento != ""){
			jQuery("#informacoes-posts").slideDown(1000);
			jQuery(this).siblings(".aviso-erro").css("display","none");
			if(valorElemento == "perdido"){
				jQuery("#label-local").html("Ultimo local visto:");
			}else if(valorElemento == "encontrado"){
				jQuery("#label-local").html("Local encontrado:");
			}else{
				jQuery("#label-local").html("Local:");
			}
			contadoraTipoPost = 0;
		}else if(valorElemento == "casual"){
			jQuery(this).siblings(".aviso-erro").css("display","none");
			jQuery("#informacoes-posts").slideUp(800);
			contadoraTipoPost = 0;
		}else{
			jQuery("#informacoes-posts").slideUp(1000);
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo tipo postagem Obrigatório!");
			jQuery(elemento).css("display","inline-block");
			contadoraTipoPost = 0;
		}
	
	});
		
	jQuery("#select-tipoPost").on("focusout",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		var valorElemento = jQuery(this).val();
		
		if(valorElemento != ""){
			jQuery(this).siblings(".aviso-erro").fadeOut(100);
		}else{
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo tipo postagem Obrigatório!");
		}
		contadoraTipoPost = 0;
	});
	
	//Campo especie postagem
	var contadoraEspecie = 0;
	jQuery("#campo-especie").on("click",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		var valorElemento = jQuery(this).val();
		if(contadoraEspecie == 0){
			jQuery(elemento).removeClass("erro-input-selects").addClass("aviso-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Atenção a especie do animal é importante para a visualização da sua postagem.");			jQuery(elemento).css("display","inline-block");
			contadoraEspecie++;
		}else if(valorElemento == "cachorro"){
			jQuery(this).siblings(".aviso-erro").css("display","none");
			jQuery("#seleciona-tamanho").slideDown(500);
			jQuery("#label-porte").fadeIn(500).css("display","block");
			contadoraEspecie = 0;
		}else if(valorElemento == ""){
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo especie postagem obrigatório!");
			jQuery(elemento).css("display","inline-block");
			jQuery("#seleciona-tamanho").slideUp(500);
			jQuery("#label-porte").fadeOut(500);
			contadoraEspecie = 0;
		}else{
			jQuery(this).siblings(".aviso-erro").css("display","none");
			jQuery("#seleciona-tamanho").slideUp(500);
			jQuery("#label-porte").fadeOut(500);
			contadoraEspecie = 0;
		}
	});
	
	jQuery("#campo-especie").on("focusout",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		var valorElemento = jQuery(this).val();
		
		if(valorElemento != ""){
			jQuery(this).siblings(".aviso-erro").fadeOut(100);
		}else{
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo especie postagem obrigatório!");
			jQuery(elemento).css("display","inline-block");
		}
		contadoraEspecie = 0;
	});
	
	jQuery("#campo-especie").on("change",function(){
		var valorElemento = jQuery(this).val();
		jQuery("#campoRaca").children("optgroup").fadeOut();
		if(valorElemento == "cachorro"){
			jQuery("#campoRaca").children(".grupo-racas-cachorro").fadeIn();
		}else if(valorElemento == "gato"){
			jQuery("#campoRaca").children(".grupo-racas-gato").fadeIn();
		}else if(valorElemento == "passaro"){
			jQuery("#campoRaca").children(".grupo-racas-passaro").fadeIn();
		}
		jQuery("#campoRaca").val("").change();
	});
	
	//Campo raça 
	var contadorCampoRaca = 0;
	jQuery("#campoRaca").on("click",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		if(contadorCampoRaca == 0){
			jQuery(elemento).removeClass("erro-input-selects").addClass("aviso-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo raça é relevante mas não obrigatório.");
			jQuery(elemento).css("display","inline-block");
			contadorCampoRaca++;
		}else{
			contadorCampoRaca = 0;
		}
	});
	
	jQuery("#campoRaca").on("focusout",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		jQuery(this).siblings(".aviso-erro").fadeOut(100);
		contadorCampoRaca = 0;
	});
	
	//Campo cor
	var contadorCampoCor = 0;
	jQuery("#campo-cor").on("click",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		if(contadorCampoCor == 0){
			jQuery(elemento).removeClass("erro-input-selects").addClass("aviso-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo cor é relevante mas não obrigatório.");
			jQuery(elemento).css("display","inline-block");
			contadorCampoCor++;
		}else{
			contadorCampoCor = 0;
		}
	});
	
	jQuery("#campo-cor").on("focusout",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		jQuery(this).siblings(".aviso-erro").fadeOut(100);
		contadorCampoRaca = 0;
	});
	
	//Campo sexo
	var contadoraCampoSexo = 0;
	jQuery("#campo-sexo").on("click",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		var valorElemento = jQuery(this).val();
		if(contadoraCampoSexo == 0){
			jQuery(elemento).removeClass("erro-input-selects").addClass("aviso-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Atenção ao escolher o sexo do animal.");
			jQuery(elemento).css("display","inline-block");
			contadoraCampoSexo++;
		}else if(valorElemento == ""){
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo sexo Obrigatório!");
			jQuery(elemento).css("display","inline-block");
			contadoraCampoSexo = 0;
		}else{
			jQuery(this).siblings(".aviso-erro").css("display","none");
			contadoraCampoSexo = 0;
		}
	});
	
	jQuery("#campo-sexo").on("focusout",function(){
		var elemento = jQuery(this).siblings(".aviso-erro");
		var valorElemento = jQuery(this).val();
		
		if(valorElemento != ""){
			jQuery(this).siblings(".aviso-erro").fadeOut(100);
		}else{
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo sexo Obrigatório!");
			jQuery(elemento).css("display","inline-block");
		}
		contadoraCampoSexo = 0;
	});
	
	//Marcarando input de telefone de contato
	jQuery("#campoTelefone").mask("(99) 99999-9999");
	
	//Mascara animada
	jQuery("#campoTelefone").on("focusout",function(event){  
            var target, phone, element;  
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
            phone = target.value.replace(/\D/g, '');  
            jQuery(target).unmask();
            if(phone.length <= 10) {  
                jQuery(target).mask("(99) 9999-99999");  
            } else {  
                jQuery(target).mask("(99) 99999-9999");  
            }  
    });
	
	jQuery("#campoTelefone").on("focusin",function(){
		var elemento = jQuery(this).siblings(".erro-aviso-esquerda");
		var valorElemento = jQuery(this).val();
		jQuery(elemento).removeClass("erro-input-esquerda").addClass("aviso-input-esquerda");
		jQuery(elemento).children(".tooltiptext").html("Número de telefone opcional.");
		jQuery(elemento).fadeIn(300);
	});
	
	jQuery("#campoTelefone").on("focusout",function(){
		var telefone = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-aviso-esquerda");
		var valorElemento = jQuery(this).val();
		
		if(telefone.length < 14 && telefone.length > 0){
			jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
			jQuery(elemento).children(".tooltiptext").html("Telefone inválido");
			jQuery(elemento).fadeIn(100);
			jQuery(this).parent().css("border-color","#d72828");
		}else{
			jQuery(elemento).fadeOut(100);
		}
	});
	
	//Campo localidade
	
	jQuery("#campo-local").on("focusin",function(){
		var elemento = jQuery(this).siblings(".erro-aviso-esquerda");
		jQuery(elemento).removeClass("erro-input-esquerda").addClass("aviso-input-esquerda");
		jQuery(elemento).children(".tooltiptext").html("Por segurança recomendamos não informar o número de sua moradia.");
		jQuery(elemento).css("display","block");
		
	});
	
	jQuery("#campo-local").on("focusout",function(){
		
		var localidade = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-aviso-esquerda");
		if(localidade.length < 1){
			jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
			jQuery(elemento).children(".tooltiptext").html("Campo Obrigatório!");
			jQuery(elemento).fadeIn(100);
			jQuery(this).parent().css("border-color","#d72828");
		}else if(localidade.length < 10){
			jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
			jQuery(elemento).children(".tooltiptext").html("Preencha a localidade corretamente!");
			jQuery(elemento).fadeIn(100);
			jQuery(this).parent().css("border-color","#d72828");
		}else{
			jQuery(elemento).fadeOut(100);
			
		}
	});
	
	
	jQuery("#descricao-postagem").on("keyup",function(){
		jQuery("#avisos-postar-descricao").html("");
	});

});
var uploading = false;
function postar(formulario){
	
	//Chama todas a variáveis necessárias
	var titulo = cleanStr(formulario.campoTitulo.value);
	var tipoPost = formulario.campoTipo.value;
	var descricao = formulario.campoDescricao.value;
	var media = ultimoArquivo;
	var especie = "";
	var porte = "";
	var raca = "";
	var cor = "";
	var sexo = "";
	var telefone = "";
	var localidade = "";
	
	
	if(uploading){
		jQuery("#erro-media").children(".tooltiptext").html("Espere o arquivo ser enviado.");
		jQuery("#erro-media").fadeIn(300);
	}else if(titulo.length < 1 || (titulo.length == 1 && titulo.search(" ") === 0 )){//Se o título estiver vazio:
		
		var elemento = jQuery("#campo-titulo").siblings(".erro-input-esquerda");//Pega o elemento do aviso de input
		jQuery(elemento).children(".tooltiptext").html("Campo Obrigatório!");//Coloca a mensagem de "Campo obrigatório" no tolltip do input
		jQuery(elemento).fadeIn(300);//Mostra o input
		jQuery("#campo-titulo").parent().css("border-color","#d72828");//Muda a cor da borda da div container-input para vermelho
		jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-titulo").offset().top});//Scrola até aparecer o input	
	}else{//Se o título tiver preenchido:
		
		if(tipoPost == "casual"){
			
			//Verifica palavões e posta
			
				jQuery.ajax({
					url: '_composicao_site/publicar_postagem.php',
					type: 'post',
					dataType: 'json',
					data:{
						titulo: titulo,
						tipoPost: tipoPost,
						descricao: descricao,
						media: media,
						especie: especie,
						porte: porte,
						raca: raca,
						cor: cor,
						sexo: sexo,
						telefone: telefone,
						localidade: localidade,	
					},
					beforeSend: function(){
						//Aparece o GIF de postar
						jQuery("#carregando-postar-container").fadeIn(100);
					},
					success: function(retorno){
						//Desaparece o GIF de postar
						jQuery("#carregando-postar-container").fadeOut(100);
										
						//Pega as variáveis retornadas
						var confirmacao = retorno.confirmacao;
						var input_de_erro = retorno.input_de_erro;
						var codigo_postagem_publicada = retorno.codigo_postagem_publicada;
						var resposta = retorno.resposta;
						//ultimoArquivo
										
						if(confirmacao == 0){//Se o paginá NAO confirmou a publicação verifica erros:
					
							if(input_de_erro == "titulo"){//Se o erro for no título
								var elemento = jQuery("#campo-titulo").siblings(".erro-input-esquerda");//Pega o elemento do aviso de input
								jQuery(elemento).children(".tooltiptext").html(resposta);//Coloca a mensagem de "Campo obrigatório" no tolltip do input
								jQuery(elemento).fadeIn(300);//Mostra o input
								jQuery("#campo-titulo").parent().css("border-color","#d72828");//Muda a cor da borda da div container-input para vermelho
								jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-titulo").offset().top});//Scrola até aparecer o input
											
							}else if(input_de_erro == "localidade"){//Se o erro for na localidade
												
								var elemento = jQuery("#campo-local").siblings(".erro-aviso-esquerda");//Pega o elemento do aviso de input
								jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
								jQuery(elemento).children(".tooltiptext").html(resposta);//Coloca a mensagem de "Campo obrigatório" no tolltip do input
								jQuery(elemento).fadeIn(300);//Mostra o input
								jQuery("#campo-local").parent().css("border-color","#d72828");//Muda a cor da borda da div container-input para vermelho
								jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-local").offset().top});
											
							}else if(input_de_erro == "descricao"){//Se o erro for na descrição
												
								jQuery("#avisos-postar-descricao").html(resposta);//Scrola até aparecer o input
							
							}
					
						}else{//Se o paginá CONFIRMOU a publicação:
							ultimoArquivo = "";
							finalizarPostagem();
						}
									
					},
					error: function(xhr, status, thrown){
						console.log("xhr: " + xhr + "\n status: " + status + "\n thrown: " + thrown);
					}
				});
		
		
		}else if(tipoPost == ""){
			//Se o tipo não foi especificado usuário notificado
			var elemento = jQuery("#select-tipoPost").siblings(".aviso-erro");
			jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
			jQuery(elemento).children(".tooltiptext").html("Campo tipo postagem obrigatório!");
			jQuery(elemento).css("display","inline-block");
			
			
		}else{
			//Se o título não for casual pega a especie o porte a raça e a cor:
			especie = formulario.campoEspecie.value;
			porte = formulario.campoPorte.value;
			raca = formulario.campoRaca.value;
			cor = formulario.campoCor.value;
			sexo = formulario.campoSexo.value;
			telefone = formulario.campoTelefone.value;
			localidade = cleanStr(formulario.campoLocal.value);
			//alert("Espécie: " + especie + " Porte: " + porte + " Raça: " + raca + " Cor: " + cor + " Sexo: " + sexo + " Telefone: " + telefone);
			if(especie == ""){//Se o usuário não informou a especie usuário será notificado:
				var elemento = jQuery("#campo-especie").siblings(".aviso-erro");
				jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
				jQuery(elemento).children(".tooltiptext").html("Campo especie postagem obrigatório!");
				jQuery(elemento).css("display","inline-block");
				jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-especie").offset().top});//Scrola até aparecer o input		
			}else{//Se usuário informou a espécie:
				if(sexo == ""){//Se o usuario não informou o sexo usuario será notificado
					var elemento = jQuery("#campo-sexo").siblings(".aviso-erro");
					jQuery(elemento).removeClass("aviso-input-selects").addClass("erro-input-selects");
					jQuery(elemento).children(".tooltiptext").html("Campo sexo Obrigatório!");
					jQuery(elemento).css("display","inline-block");
					jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-sexo").offset().top});//Scrola até aparecer o input	
				}else{//Se o usuário informou o sexo
					var elemento = jQuery("#campoTelefone").siblings(".erro-aviso-esquerda");
					if(telefone.length < 14 && telefone.length > 0){
						jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
						jQuery(elemento).children(".tooltiptext").html("Telefone inválido");
						jQuery(elemento).fadeIn(300);
						jQuery(this).parent().css("border-color","#d72828");
					}else{//Se o telefone estiver OK
						var elemento = jQuery("#campo-local").siblings(".erro-aviso-esquerda");
						if(localidade.length < 1){//Se a pessoa não preencheu a localidade usuário será notificado
							jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
							jQuery(elemento).children(".tooltiptext").html("Campo Obrigatório!");
							jQuery(elemento).fadeIn(300);
							jQuery(this).parent().css("border-color","#d72828");
						}else if(localidade.length < 10){//Se a pessoa preencheu a localidade errado
							jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
							jQuery(elemento).children(".tooltiptext").html("Preencha a localidade corretamente!");
							jQuery(elemento).fadeIn(300);
							jQuery(this).parent().css("border-color","#d72828");
						}else{//Se a localidade estiver OK:
						
							jQuery.ajax({
								url: '_composicao_site/publicar_postagem.php',
								type: 'post',
								dataType: 'json',
								data:{
									titulo: titulo,
									tipoPost: tipoPost,
									descricao: descricao,
									media: media,
									especie: especie,
									porte: porte,
									raca: raca,
									cor: cor,
									sexo: sexo,
									telefone: telefone,
									localidade: localidade,	
								},
								beforeSend: function(){
									//Aparece o GIF de postar
									jQuery("#carregando-postar-container").fadeIn(100);
								},
								success: function(retorno){
									//Desaparece o GIF de postar
									jQuery("#carregando-postar-container").fadeOut(100);
									
									//Pega as variáveis retornadas
									var confirmacao = retorno.confirmacao;
									var input_de_erro = retorno.input_de_erro;
								    var codigo_postagem_publicada = retorno.codigo_postagem_publicada;
								    var resposta = retorno.resposta;
									//ultimoArquivo
									
									if(confirmacao == 0){//Se o paginá NAO confirmou a publicação verifica erros:
									
										if(input_de_erro == "titulo"){//Se o erro for no título
											
											var elemento = jQuery("#campo-titulo").siblings(".erro-input-esquerda");//Pega o elemento do aviso de input
											jQuery(elemento).children(".tooltiptext").html(resposta);//Coloca a mensagem de "Campo obrigatório" no tolltip do input
											jQuery(elemento).fadeIn(300);//Mostra o input
											jQuery("#campo-titulo").parent().css("border-color","#d72828");//Muda a cor da borda da div container-input para vermelho
											jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-titulo").offset().top});//Scrola até aparecer o input
										
										}else if(input_de_erro == "localidade"){//Se o erro for na localidade
											
											var elemento = jQuery("#campo-local").siblings(".erro-aviso-esquerda");//Pega o elemento do aviso de input
											jQuery(elemento).removeClass("aviso-input-esquerda").addClass("erro-input-esquerda");
											jQuery(elemento).children(".tooltiptext").html(resposta);//Coloca a mensagem de "Campo obrigatório" no tolltip do input
											jQuery(elemento).fadeIn(300);//Mostra o input
											jQuery("#campo-local").parent().css("border-color","#d72828");//Muda a cor da borda da div container-input para vermelho
											jQuery("#container-postar-de-dentro").animate({scrollTop: jQuery("#campo-local").offset().top});
										
										}else if(input_de_erro == "descricao"){//Se o erro for na descrição
											
											jQuery("#avisos-postar-descricao").html(resposta);//Scrola até aparecer o input
										
										}
										
										
									}else{//Se o paginá CONFIRMOU a publicação:
										ultimoArquivo = "";
										finalizarPostagem();
									}
									
								},
								error: function(xhr, status, thrown){
									console.log("xhr: " + xhr + "\n status: " + status + "\n thrown: " + thrown);
								}
							});
						}
					}
				}
			}
		}
	}
	return false;
}

function finalizarPostagem(){
	
	esvaziaCampos();
	
	//Busca o a postagem publicada
	if(larguraDaTela < 960){//Se o site estiver mobile busca post mobile
		jQuery.ajax({
			url: '_composicao_site/busca_ultima_postagem_mobile.php',
			type: 'post',
			dataType: 'text',
			data:{
			},
			beforeSend: function(){
				//jQuery(".gif-carregando").stop().fadeIn(250);
			},
			success: function(retorno){
				//jQuery(".gif-carregando").stop().fadeOut(250);
				jQuery("#container-posts-mobile").prepend(retorno);
				jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
				jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
			},
			error: function(erro){
				alert("Erro na hora de acionar o AJAX");
			}
		});
	}else{//Se o site estiver desktop busca post desktop
		jQuery.ajax({
			url: '_composicao_site/busca_ultima_postagem_desktop.php',
			type: 'post',
			dataType: 'text',
			data:{
			},
			beforeSend: function(){
				//jQuery(".gif-carregando").stop().fadeIn(250);
			},
			success: function(retorno){
				//jQuery(".gif-carregando").stop().fadeOut(250);
				jQuery("#container-postagem-desktop").prepend(retorno);
				jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
				jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
			},
			error: function(erro){
				alert("Erro na hora de acionar o AJAX");
			}
		});
	}
}
function esvaziaCampos(){
	//Esvazia os inputs
	jQuery("#campo-titulo").val("");
	jQuery("#select-tipoPost").val("").change();
	jQuery("#campo-especie").val("").change();
	jQuery("#radio-pequeno").prop("checked", true)
	jQuery("#campoRaca").val("").change();
	jQuery("#campo-cor").val("").change();
	jQuery("#campo-sexo").val("").change();
	jQuery("#campoTelefone").val("");
	jQuery("#campo-local").val("");
	jQuery("#descricao-postagem").val("");
	//Desaparece campo porte
	jQuery("#seleciona-tamanho").slideUp(500);
	jQuery("#label-porte").fadeOut(500);
	//Desaparece formulário de informações mais precisas
	jQuery("#informacoes-posts").slideUp(1000);
	fecharModal();
} 
/*Codigo do formulário de postar e editar daqui para cima ----------------------------------------------------------------------*/
function confirmacao(elemento,tipo,codigo){
	jQuery("body").css("overflow","hidden");
	jQuery("#camada-fundo-site").fadeIn();
	jQuery("#container-confimacoes").css("top","45%");
	jQuery("#botao-confirma").off();
	jQuery("#titulo-confirmacao").html("Confirmação");
	if(tipo == "excluir"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja excluir a postagem?");
		jQuery("#botao-confirma").on("click",function(){
			excluirPostagem(elemento,codigo);
			fecharModal();
		});
	}else if(tipo == "ocultar"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja ocultar a postagem?");
		jQuery("#botao-confirma").on("click",function(){
			ocultaPostagem(elemento);
			fecharModal();
		});
	}else if(tipo == "bloquear"){
				var nome = jQuery(elemento).closest('div').siblings('.container-detalhes-post').children(".container-detalhes-dono ").children("a").children(".nome-user").html();
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja bloquear "+nome+"?");
		jQuery("#botao-confirma").on("click",function(){
			bloquearUsuario(elemento,codigo);
			fecharModal();
		});
	}else if(tipo == "denunciar"){
		jQuery("#form-motivo-denuncia").fadeIn();
		jQuery("#titulo-confirmacao").html("Motivo");
		jQuery("#conteudo-confirmacao").html("Ajude-nos a entender o que está acontecendo:");
		jQuery("#botao-confirma").on("click",function(){
			denunciarPostagem(elemento,codigo);
			fecharModal();
		});
	}else if(tipo == "excluircomentario"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja excluir o comentário?");
		jQuery("#botao-confirma").on("click",function(){
			excluirComentario(elemento,codigo)
			fecharModal();
		});
	}else if(tipo == "denunciarcomentario"){
		jQuery("#form-motivo-denuncia").fadeIn();
		jQuery("#titulo-confirmacao").html("Motivo");
		jQuery("#conteudo-confirmacao").html("Ajude-nos a entender o que está acontecendo:");
		jQuery("#botao-confirma").on("click",function(){
			denunciarComentario(elemento,codigo);
			fecharModal();
		});
	}else if(tipo == "deseguir"){
		var nome = jQuery(elemento).siblings("a").children("span").html();
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja não seguir "+nome+"?");
		jQuery("#botao-confirma").on("click",function(){
			seguirUsuarioDeseguir(elemento,codigo,2)
			fecharModal();
		});
	}else if(tipo == "desbloquear"){
		var nome = jQuery(elemento).siblings("span").html();
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja desbloquear "+nome+"?");
		jQuery("#botao-confirma").on("click",function(){
			desbloquearUsuario(elemento,codigo);
			fecharModal();
		});
	}else if(tipo == "sair"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja sair?");
		jQuery("#botao-confirma").on("click",function(){
			window.location.replace("feed.php?sair=true");
		});
	}
	
	//excluirPostagem(elemento,$codigo_post)
}
function pesquisar(texto){
	
	var textoTratado = cleanStr(texto);
	
	if(textoTratado.length > 0){
		
		jQuery.ajax({
				url: '_composicao_site/pesquisar-logado.php',
				type: 'post',
				dataType: 'text',
				data:{
					textoPesquisa: textoTratado,	
				},
				beforeSend: function(){
					jQuery(".gif-carregando").stop().fadeIn(250);
				},
				success: function(retorno){
					jQuery(".gif-carregando").stop().fadeOut(250);
	
					if(novaResolucao < 960){
						jQuery("#pesquisa-mobile").html(retorno)
					}else{
						jQuery("#container-resultados-pesquisa-desktop").html(retorno)
					}
				},
				error: function(erro){
					alert("Erro na hora de acionar o AJAX");
				}
						
		});
		
	}else{
		if(novaResolucao < 960){
			jQuery("#pesquisa-mobile").html("<div class='nenhum-pesquisa'>Pesquise algo!</div> <img class='icone-grande-pesquisa-mobile' src='_imgs/buscaper.png' />");
		}else{
			jQuery("#container-resultados-pesquisa-desktop").html("<div class='nenhum-pesquisa elemento-pesquisa-desktop'>Pesquise algo!</div><img class='icone-grande-pesquisa-mobile' src='_imgs/buscaper.png' />");
		}
	}
}

function atualizaFotoPerfil(formulario){
	
	// Move cropped image data to hidden input
	var imageData = jQuery('.image-editor').cropit('export');
	jQuery('.hidden-image-data').val(imageData);
	
	var nomeFoto = jQuery('#input-foto').val();
	
	// Print HTTP request params
    var formValue = jQuery(formulario).serialize();
	
	if(typeof imageData !== "undefined" && nomeFoto !== ""){
		jQuery.ajax({
			type: "POST",
	      	url: 'atualiza_foto_perfil.php',
	      	data: formValue,
	      	success: function(msg){
	        	if(msg != "erro"){
	  				jQuery(".foto-usuario-logado").attr("src",msg);
	  				fecharModal();
				}else{
					alert(msg);
				}

	      	}		
		});
		jQuery('#input-foto').val("");
	}

	// Prevent the form from actually submitting
	return false;
}

var ultimoCampo = -1;
function abrirContainerAplicacao(valor){
			
	if(jQuery("#container-menu-desktop-popover").is(":visible") && valor === ultimoCampo){
		jQuery("#container-menu-desktop-popover").fadeOut(100);
		jQuery("#botao-notificacao-desktop").css('background-image','url(_imgs/n.png)');
		jQuery("#botao-chat-desktop").css('background-image','url(_imgs/c.png)');
		jQuery("#botao-menu-desktop").css('background-image','url(_imgs/m.png)');
	}else{
		jQuery("#container-menu-desktop-popover").fadeIn(100);
		jQuery("#ponteiro-popover").attr('class', '');
		jQuery("#botao-notificacao-desktop").css('background-image','url(_imgs/n.png)');
		jQuery("#botao-chat-desktop").css('background-image','url(_imgs/c.png)');
		jQuery("#botao-menu-desktop").css('background-image','url(_imgs/m.png)');
		switch(valor){//Verifica com switch qual botao foi acionado
			case 1://Se foi o do mostrar feed
				jQuery("#ponteiro-popover").addClass("ponteiro-chat");
				jQuery("#botao-chat-desktop").css('background-image','url(_imgs/c2.png)')
				jQuery("#conteudo-popover").html("Buscando... <br><br><br>");
			break;
			case 2://Se foi o do mostrar notificações
				jQuery("#ponteiro-popover").addClass("ponteiro-notificacoes");
				jQuery("#botao-notificacao-desktop").css('background-image','url(_imgs/n2.png)');
				buscarNotificacoes(1);				
			break;
			case 3://Se foi o do mostrar chat
				jQuery("#ponteiro-popover").addClass("ponteiro-menu");
				jQuery("#botao-menu-desktop").css('background-image','url(_imgs/m2.png)')
				buscaMenuDesktop();
			break;
			
		}
		ultimoCampo = valor;
	}
		
}
//Configuração da função de busca pessoas que o usuário logado segue
function buscaSeguindo(valor){
		jQuery.ajax({
			url: '_composicao_site/busca-seguindo.php',
			type: 'post',
			dataType: 'html',
			data:{
				tipo: valor,	
			},
			beforeSend: function(){
				jQuery(".gif-carregando").stop().fadeIn(250);
			},
			success: function(retorno){
				jQuery(".gif-carregando").stop().fadeOut(250);
				if(valor == 1){
					jQuery("#conteudo-popover").html(retorno)
				}else{
					jQuery("#seguindo-mobile").html(retorno)
				}
			},
			error: function(){
				alert("Erro na hora de acionar o AJAX");
			}
					
		});	
}

//Configuração da função de busca pessoas bloqueadas
function buscaBloqueados(valor){
	jQuery.ajax({
		url: '_composicao_site/busca-bloqueados.php',
		type: 'post',
		dataType: 'html',
		data:{
			tipo: valor,	
		},
		beforeSend: function(){
			jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			jQuery(".gif-carregando").stop().fadeOut(250);
			if(valor == 1){
				jQuery("#conteudo-popover").html(retorno)
			}else{
				jQuery("#bloqueados-mobile").html(retorno)
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
	});	
}

function desbloquearUsuario(elemento,codUsuarioAlvo){
	jQuery.ajax({
		url: '_composicao_site/desbloquear-usuario.php',
		type: 'post',
		dataType: 'text',
		data:{
			codigoUsuarioAlvo: codUsuarioAlvo,	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			if(retorno == "Desbloqueado"){
				jQuery(elemento).parent(".container-usuario").remove();
				if(jQuery("#conteudo-popover").is(":visible") && jQuery("#conteudo-popover").children().length < 2){
					jQuery("#conteudo-popover").append("<div class='nenhum'> Não há usuários bloqueados. </div>");
				}else if(jQuery("#bloqueados-mobile").children().length < 2){
					jQuery("#bloqueados-mobile").append("<div class='nenhum'> Não há usuários bloqueados. </div>");
				}
			}else{
				alert ("Erro: "+retorno);
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
	});	
}



//Variável de favoritos, função de buscar favoritos e função de buscar favoritos scroll abaixo
var scrollAnterior = 0;
var iniciofavoritos = 0;
var contunuaBusca = true;
function buscaFavoritos(valor){
	if(!jQuery("#favoritos-mobile").is(":visible") &&  !jQuery("#container-postagem-favoritos").is(":visible")){
		//Scroll para cima
		jQuery('html, body').animate({scrollTop:scrollAnterior},400);
		//Reseta visualizadoras favoritos
		jQuery("#container-postagem-favoritos").html("<header id='cabecario-visualizacao-desktop-favoritos'><div id='voltar-feed-noticias'></div><h1>Meus Favoritos</h1></header>");
		jQuery("#favoritos-mobile").html("<header id='cabecario-visualizacao-mobile-favoritos'><div id='voltar-feed-noticias-mobile'></div><h1>Meus Favoritos</h1></header>");
		//Reseta busca
		iniciofavoritos = 0;
		contunuaBusca = true;
		//Função de voltar
		jQuery("#voltar-feed-noticias").on('click', function(){
			jQuery("#container-postagem-favoritos").fadeOut(100);
			jQuery("#container-postagem-feed").fadeIn(100);
			jQuery('html, body').animate({scrollTop:scrollAnterior},800);
			scrollAnterior = 0;
			buscaFavoritosScroll = false;
		});
	
		jQuery("#voltar-feed-noticias-mobile").on('click', function(){
			trocarDeAba(4);
			buscaFavoritosScroll = false;
		});
	}
	if(scrollAnterior == 0){
		scrollAnterior = jQuery(window).scrollTop();
	}
	if(valor === 1){//Se a pessoa apertou o botão de favoritos desktop		
		jQuery("#container-menu-desktop-popover").fadeOut(100);
		jQuery("#botao-menu-desktop").css('background-image','url(_imgs/m.png)');
		jQuery("#container-postagem-feed").fadeOut(100);
		jQuery("#container-postagem-favoritos").fadeIn(100);
		if(contunuaBusca){
			jQuery.ajax({
				url: '_composicao_site/busca_favoritos_desktop.php',
				type: 'post',
				dataType: 'json',
				data:{
					comecoFavoritos: iniciofavoritos,	
				},
				beforeSend: function(){
					
				},
				success: function(retorno){
					if(retorno.status == "nenhum favorito"){
						contunuaBusca = false;
						jQuery("#container-postagem-favoritos").append("<div class='nenhum'>Você não tem favoritos.<div>");
					}else if(retorno.status == "fim de favoritos"){
						contunuaBusca = false;
						jQuery("#container-postagem-favoritos").append("<div class='fim'>Fim dos favoritos.<div>");
					}else{
						iniciofavoritos = retorno.proximocomeco;
						jQuery("#container-postagem-favoritos").append(retorno.retornohtml);
						jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
						jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
					}
					buscaFavoritosScroll = true;
				},
				error: function(xhr, status, thrown){
					alert(status);
					alert(thrown);
				}
			});
		}
	
	}else if(valor == 2){//Se a pessoa apertou o botão de favoritos mobile
		if(contunuaBusca){
			jQuery.ajax({
				url: '_composicao_site/busca_favoritos_mobile.php',
				type: 'post',
				dataType: 'json',
				data:{
					comecoFavoritos: iniciofavoritos,	
				},
				beforeSend: function(){
					jQuery(".gif-carregando").stop().fadeIn(250);
				},
				success: function(retorno){
					jQuery(".gif-carregando").stop().fadeOut(250);
					if(retorno.status == "nenhum favorito"){
						contunuaBusca = false;
						jQuery("#favoritos-mobile").append("<div class='nenhum'>Você não tem favoritos.<div>");
					}else if(retorno.status == "fim de favoritos"){
						contunuaBusca = false;
						jQuery("#favoritos-mobile").append("<div class='fim'>Fim dos favoritos.<div>");
					}else{
						iniciofavoritos = retorno.proximocomeco;
						jQuery("#favoritos-mobile").append(retorno.retornohtml);
						jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
						jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
					}
					buscaFavoritosScroll = true;
				},
				error: function(xhr, status, thrown){
					alert(status);
					alert(thrown);
				}
			});
		}
	}
}
var buscaFavoritosScroll = false;
jQuery(document).ready(function(){
	var larguraDaTela = jQuery(window).width();
	jQuery(window).scroll(function(){//Quando o usuário da scroll na tela:
		if(Math.ceil(jQuery(window).scrollTop()) == Math.floor(jQuery(document).height()) - jQuery(window).height()){//Se o scroll chegar no final da tela:
			if(larguraDaTela < 960 && buscaFavoritosScroll){
				buscaFavoritos(2);
			}else if(larguraDaTela >= 960 && buscaFavoritosScroll){
				buscaFavoritos(1);
			}
		}   
	});
});

function buscaMenuDesktop(){
	jQuery.ajax({
		url: '_composicao_site/busca_menu_desktop.php',
		tipe: 'post',
		beforeSend: function(){

		},
		data:{	
		},
		success: function(retorno){
			jQuery("#conteudo-popover").html(retorno);
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");	
		}	
	});
}

function buscarNotificacoes(valor){
	
	jQuery.ajax({
		url: '_composicao_site/busca_notificacoes.php',
		tipe: 'post',
		dataType:'text',
		contentType: 'application/x-www-form-urlencoded',
		beforeSend: function(){
			jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			jQuery(".gif-carregando").stop().fadeOut(250);
			if(valor === 1){
				jQuery("#conteudo-popover").html(retorno);
			}else{
				jQuery("#notificacoes-mobile-conteudo").html(retorno);
			}
	
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");	
		}	
	});
}

function buscaChat(valor){
}


function trocarDeAba(valor){//Função para navegação dinamica no site mobile
	buscaFavoritosScroll = false;
	jQuery(".container-bt-mobile").css( "background-color","transparent");//Fundo do container dos botões mobile ficam branco
	jQuery(".visualizacoes").css("display","none");//Todas as abas de visualizações somem (pesquisa,chat,feed,menu)
	switch(valor){//Verifica com switch qual botao foi acionado
		case 1://Se foi o do mostrar feed
			jQuery("#feed-mobile").css("display","block");
			jQuery("#bt-feed-mobile").parent().css( "background-color", "#dfdbdb");
		break;
		case 2://Se foi o do mostrar chat
			jQuery("#chat-mobile").css("display","block");
			jQuery("#bt-chat-mobile").parent().css( "background-color", "#dfdbdb");
		break;
		case 3://Se foi o do mostrar notificacoes
			jQuery("#notificacoes-mobile").css("display","block");
			jQuery("#bt-notificacao-mobile").parent().css( "background-color", "#dfdbdb");
			buscarNotificacoes(2);
		break;
		case 4://Se foi o do mostrar menu
			jQuery("#menu-mobile").css("display","block");
			jQuery("#bt-menu-mobile").parent().css( "background-color", "#dfdbdb");
			buscaOutraTela("menu");
		break;
		case 5://Se foi o do mostrar pesquisa
			jQuery("#pesquisa-mobile").css("display","block");
		break;
		case 6://Se foi o do mostrar favoritos
			buscaFavoritos(2);
			jQuery("#favoritos-mobile").css("display","block");
		break;
		case 7://Se foi o do mostrar favoritos
			jQuery("#seguindo-mobile").css("display","block");
			buscaSeguindo(2);
		break;
		case 8://Se foi o do mostrar favoritos
			jQuery("#bloqueados-mobile").css("display","block");
			buscaBloqueados(2);
		break;
		default:
	}
}

function marcarNotificacoesComoLidas(){
	
	jQuery.ajax({
		url: '_composicao_site/marcar_visualizado_notificacao.php',
		tipe: 'post',
		beforeSend: function(){
		},
		data:{
			codigoNotificacao: -1,
		},
		success: function(retorno){
			if(retorno == "marcado como lido"){
				jQuery(".notificacoes:not(.lido)").removeClass("nao-lido").addClass("lido");
			}else{
				alert(retorno);
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");	
		}	
	});
}

function ocultaPostagem(elemento){
	jQuery(elemento).closest(".posts").slideUp(500);
}

function buscaOutraTela(tela){
	
	
	if(tela == "menu" && (jQuery("#menu-mobile").children().length < 2)) {
	
	jQuery.ajax({
		url: '_composicao_site/'+tela+'-mobile.php',
		tipe: 'post',
		beforeSend: function(){
			jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			jQuery(".gif-carregando").stop().fadeOut(250);
			jQuery("#"+tela+"-mobile").append(retorno);
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");	
		}	
	});
	
	}
		
}

function bloquearUsuario(elemento,valor){
	
	jQuery.ajax({
		url: '_composicao_site/bloquear-usuario.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoUsuarioAlvo: valor,
		},
		beforeSend: function(){
			//jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			if(retorno == "Bloqueado"){
				//bloquearUsuario(elemento,valor)
				jQuery("li[onClick='confirmacao(this,'bloquear',"+valor+")']").closest(".posts").each(function(){
					jQuery(this).slideUp(800);
				})	
			}else{
				alert("Erro: "+retorno);
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
	
}

function visualizarPostsMobile(tipoPost,especie,porte,cor,sexo){//Função para pegar os post no banco versão mobile
	
	jQuery.ajax({
		url: '_composicao_site/'+'busca_posts_logado_mobile.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			tipoPost: tipoPost,
			especie: especie,
			porte:porte,
			cor: cor,
			sexo: sexo	
		},
		beforeSend: function(){
			jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			jQuery(".gif-carregando").stop().fadeOut(250);
			jQuery("#container-posts-mobile").append(retorno);
			jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
			jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
		
}

function visualizarPostsDesktop(tipoPost,especie,porte,cor,sexo){//Função para pegar os post no banco versão mobile
	
	jQuery.ajax({
		url: '_composicao_site/'+'busca_posts_logado_desktop.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			tipoPost: tipoPost,
			especie: especie,
			porte: porte,
			cor: cor,
			sexo: sexo,
		},
		beforeSend: function(){
			
		},
		success: function(retorno){
			jQuery("#container-postagem-desktop").append(retorno);
			jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
			jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
		
}

var ultimoArquivo = "";
function uploadFile(){
	
	var file = document.getElementById("campo-imagem").files[0];
	
	//alert(file.name+" | "+file.size+" | "+file.type);
	if(file.type == "video/mp4" || file.type == "image/png" || file.type == "image/jpeg" || file.type == "image/gif"){
		uploading = true;
		jQuery("#erro-media").fadeOut(300);
		jQuery("#container-progress").fadeIn();
		var formdata = new FormData();
		if(file.type == "image/png" || file.type == "image/jpeg"){
			ImageTools.resize(file, {
        		width: 600, // maximum width
				height: 600 // maximum height
    		}, function(blob, didItResize) {
        		file = blob;
				formdata.append("file1",file);
				formdata.append("ultimoArquivo",ultimoArquivo);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress",progressHandler, false);
				ajax.addEventListener("load",completeHandler,false);
				ajax.addEventListener("error",errorHandler,false);
				ajax.addEventListener("abort",abortHandler,false);
				ajax.open("POST", "carregar_upload.php");
				ajax.send(formdata);
    		});
		}else{
			formdata.append("file1",file);
			formdata.append("ultimoArquivo",ultimoArquivo);
			var ajax = new XMLHttpRequest();
			ajax.upload.addEventListener("progress",progressHandler, false);
			ajax.addEventListener("load",completeHandler,false);
			ajax.addEventListener("error",errorHandler,false);
			ajax.addEventListener("abort",abortHandler,false);
			ajax.open("POST", "carregar_upload.php");
			ajax.send(formdata);
		}
	
	}else{
		jQuery("#erro-media").children(".tooltiptext").html("Tipo de arquivo não suportado.");
		jQuery("#erro-media").fadeIn(300);
	}
}
function progressHandler(event){/*Função de upload processando*/
	var percent = (event.loaded / event.total) * 100;
	percent = Math.round(percent);
	jQuery("#progress").css("width",percent+"%");
	jQuery("#status").html(percent+"% ");
}

function completeHandler(event){/*Função de upload completo*/
	jQuery("#container-progress").fadeOut();
	jQuery("#erro-media").fadeOut(300);
	uploading = false;
	
	//Pega o file upado e o link do file
	var file = document.getElementById("campo-imagem").files[0];
	
	ultimoArquivo = event.target.responseText;//Atualiza o link do ultimo arquivo
	
	//verifica qual tipo é o file e faz o preview 
	if(file.type == "video/mp4"){
		jQuery("#media-preview").html("<video class='video-posts' controls> <source src='"+ultimoArquivo+"' type='video/mp4' /> Desculpe, mas não foi possível carregar o áudio.</video>");
	}else{
		jQuery("#media-preview").html("<img src='"+ultimoArquivo+"' class='imagens-posts' />");
	}	
}

function errorHandler(event){/*Função de upload erro*/
	jQuery("#status").html("0%");
	jQuery("#container-progress").fadeOut();
	jQuery("#erro-media").children(".tooltiptext").html("Falha ao enviar arquivo.");
	jQuery("#erro-media").fadeIn(300);
}
function abortHandler(event){/*Função de upload abortado*/
	jQuery("#status").html("0%");
	jQuery("#container-progress").fadeOut();
	jQuery("#erro-media").children(".tooltiptext").html("Falha ao enviar arquivo.");
	jQuery("#erro-media").fadeIn(300);
}

jQuery(window).on('beforeunload', function() {
	deletaUltimaMedia(2)
});

function fecharModalPostar(){
	esvaziaCampos();
	deletaUltimaMedia(1);
}

function deletaUltimaMedia(valor){
	if(ultimoArquivo != "" && valor == 1){//Se a pessoa apenas fechar o modal a exlcusão será feita em segundo plano
		jQuery.ajax({
			url: 'deleta-ultima-media.php',
			dataType: 'text',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				ultimaMedia: ultimoArquivo,	
			},
			beforeSend: function(){
			},
			success: function(retorno){
				jQuery("#media-preview").html("");
				jQuery("#campo-imagem").val("");
				ultimoArquivo = "";
			},
			error: function(){
				alert("Erro na hora de acionar o AJAX");
			}
		});
	}else if(ultimoArquivo != "" && valor == 2){//Se a pessoa fechar toda a pagina a exclusão não será passo a passo
		jQuery.ajax({
			url: 'deleta-ultima-media.php',
			dataType: 'text',
			async:false,
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				ultimaMedia: ultimoArquivo,	
			},
			beforeSend: function(){
			},
			success: function(retorno){
			},
			error: function(){
				alert("Erro na hora de acionar o AJAX");
			}
		});
	}
}


function abrirPostar(valor){
	jQuery("body").css("overflow","hidden");
	jQuery("#camada-fundo-site").fadeIn(500);
	jQuery("#container-postar").animate({"top":"50%"},500);
	jQuery("#media-preview").html("");
}

function abrirBuscaPersonalizada(){
	jQuery("body").css("overflow","hidden");
	jQuery("#camada-fundo-site").fadeIn(500);
	jQuery("#container-filtrar").animate({"top":"50%"},500);
}

function abrirModalTrocarFoto(){
	jQuery("body").css("overflow","hidden");
	jQuery("#camada-fundo-site").fadeIn(500);
	jQuery("#container-trocar-foto").animate({"top":"50%"},500);
}

function fecharModal(){
	jQuery("#form-motivo-denuncia").fadeOut();
	jQuery("body").css("overflow","auto");
	jQuery("#container-trocar-foto").animate({"top":"-50%"},500);
	jQuery("#container-filtrar").animate({"top":"-50%"},500);
	jQuery("#container-postar").animate({"top":"-50%"},500);
	jQuery("#container-confimacoes").animate({"top":"-50%"},500);
	jQuery("#camada-fundo-site").fadeOut(500);
}


function excluirPostagem(elemento,codigoPost){

	jQuery.ajax({
		url: '_composicao_site/'+'excluir_postagem.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoPostagem: codigoPost,	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			
			if(retorno == "Excluido"){
				jQuery(elemento).closest(".posts").slideUp(500);
			}else{
				alert(retorno);
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});

}

function denunciarPostagem(elemento,codPostagem){
	var motivo = jQuery("input[name='motivoDenuncia']:checked").val();
	//alert(motivo);
	jQuery.ajax({
		url: '_composicao_site/'+'denunciar_postagem.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codPostagem: codPostagem,
			motivo: motivo	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			if(retorno == "Denunciado"){
				jQuery(elemento).closest(".posts").slideUp(500);
			}else{
				alert(retorno);	
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
}

function gravaNotificacaoLida(elemento,codNotificacao){
	jQuery.ajax({
		url: '_composicao_site/grava_lido_notificacao.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codNotificacao: codNotificacao,	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			if(retorno == "Gravado"){
				jQuery(elemento).removeClass("nao-lido").addClass("lido");
			}else{
				alert(retorno);	
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
}
var larguraDaTela;
var novaResolucao;
jQuery(document).ready(function() {
	
	jQuery("#select-tipo-post").on('change',function(){
		if(jQuery(this).val() == "casual"){
			jQuery("#container_detalhes_filtrar").slideUp(300);
			jQuery("#select-especie-post").val("").change();
			jQuery("#select-porte-post").val("").change();
			jQuery("#select-cor-post").val("").change();
			jQuery("#select-sexo-post").val("").change();
		}else{
			jQuery("#container_detalhes_filtrar").slideDown(300);
		}
	});
	
	
	
	//Pega altura e largura da tela e guarda em variáveis:
	larguraDaTela = jQuery(window).width();
	novaResolucao = jQuery(window).width();
	var alturaDaTela = jQuery(window).height();
	
	
	jQuery("#container-postar").css("max-height",alturaDaTela - 20);
	jQuery("#container-postar-de-dentro").css("max-height",alturaDaTela - 80);
	
	//Chama função de cortar imagem
	jQuery('.image-editor').cropit();
	jQuery('.rotate-cw').click(function() {
        jQuery('.image-editor').cropit('rotateCW');
    });
    jQuery('.rotate-ccw').click(function() {
    	jQuery('.image-editor').cropit('rotateCCW');
    });
	
	//Div de visualizações do mobile pega a altura da tela como largura mínima
	
	if(larguraDaTela < 960){//Se o site estiver em mobile:
		jQuery('#barra-de-pesquisa').on('focus',function(){
			trocarDeAba(5);//A barra quando foco abrirá a tela de visualizar resultados de pesquisas
		});
		jQuery(".visualizacoes").css("min-height",(alturaDaTela-101));
		//Configura altura minima para div apresentadoras
		jQuery("#feed-mobile").css("min-height",(alturaDaTela+10));
		jQuery("#favoritos-mobile").css("min-height",(alturaDaTela+10))
	}else{//Se a tela for desktop
		//Div que mostra os usuários do chat pega a altura da tela
		jQuery("#usersonline").css("height",(alturaDaTela-51));
		
		//Configura altura minima para div apresentadoras
		jQuery("#container-postagem-feed").css("min-height",(alturaDaTela+10))
		jQuery("#container-postagem-favoritos").css("min-height",(alturaDaTela+10))
		
		jQuery('#barra-de-pesquisa').on('focus',function(){
			jQuery('#container-resultados-pesquisa-desktop').stop().fadeIn(300);//Container aparece quando a barra de pesquisa receber focus
		});
		jQuery('#icone-de-pesquisa').on('click',function(){
			jQuery('#container-resultados-pesquisa-desktop').stop().fadeIn(300);//Container aparece quando a barra de pesquisa receber focus
		});
		jQuery("html").click(function(e){//Se o usuário clicar em algo
			var temClasse = jQuery(e.target).hasClass('elemento-pesquisa-desktop');//pega a classe do elemento clicado 
			if(!temClasse){//se não for um elemento diferente de barra-de-pesquisa ou do container
        		jQuery("#container-resultados-pesquisa-desktop").stop().fadeOut(300);//conteiner desaparece
    		}
			var ulId = jQuery(e.target).closest('ul').attr('id');
			if(ulId != "lista-menu-desktop" && ulId != "lista-menu-mobile-popover" && !jQuery("#camada-fundo-site").is(":visible")){
				jQuery('#container-menu-desktop-popover').fadeOut(400);
				jQuery("#botao-notificacao-desktop").css('background-image','url(_imgs/n.png)');
				jQuery("#botao-chat-desktop").css('background-image','url(_imgs/c.png)');
				jQuery("#botao-menu-desktop").css('background-image','url(_imgs/m.png)');
			}
		})
	}

	jQuery("#select-especie-post").on('change',function(){
		if(jQuery(this).val() === "cachorro"){
			jQuery("#container-select-porte").slideDown(500);
		}else{
			jQuery("#container-select-porte").slideUp(500);
			jQuery("#select-porte-post").val("naoInformado").change();
		}
	});
	
	var alturaMenuNavegacao = alturaDaTela - 60;
	jQuery("#menu-visualizacao-desktop").css("height",alturaMenuNavegacao);
	
	var widthTextArea = jQuery("#cabecario-visualizacao-desktop").width() - 94;
	jQuery("#campo-enfeite-desktop").css({"width":widthTextArea});
	
	
	
	jQuery(window).resize(function(){
		
		novaResolucao = jQuery(window).width();
		if((larguraDaTela < 960 && novaResolucao > 959) || (larguraDaTela > 959 && novaResolucao < 960)){
			location.reload();
		}
		alturaDaTela = jQuery(window).height();
		
		jQuery("#container-postar").css("max-height",alturaDaTela - 20);
		jQuery("#container-postar-de-dentro").css("max-height",alturaDaTela - 80);
		//Abaixo algumas funções de resposividade
		jQuery(".visualizacoes").css("min-height",(alturaDaTela-101));
		
		var alturaMenuNavegacao = alturaDaTela - 60;
		jQuery("#menu-visualizacao-desktop").css("height",alturaMenuNavegacao);
		
		var widthTextArea = jQuery("#cabecario-visualizacao-desktop").width() - 100;
		jQuery("#campo-enfeite-desktop").css({"width":widthTextArea});
	
	});
	
	
	
	
});
