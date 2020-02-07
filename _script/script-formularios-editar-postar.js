// JavaScript Document
//Chama variáveis nescessárias
var ultimoArquivo = "";
var mediaPost = "";
var uploading = false;
/*CÓDIGO EDITAR POSTAGEM ABAIXO ------------------------------------------------------------------------------------*/
function chamaModalEditar(elemento,codigo){
	
	jQuery.ajax({
		url: '_composicao_site/busca_informacoes_postagem.php',
		dataType: 'json',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codPostagem: codigo	
		},
		beforeSend: function(){
		},
		success: function(retorno){
			//Configura os campos com a informação da postagem
			jQuery("#campo-titulo").val(retorno.titulo);
			jQuery("#select-tipoPost").val(retorno.tipo).click().click();
			jQuery("#campo-especie").val(retorno.especie).change().click().click();
			jQuery("#campo-cor").val(retorno.cor);
			jQuery("#campo-sexo").val(retorno.sexo);
			jQuery("#campoTelefone").val(retorno.contato);
			jQuery("#campo-local").val(retorno.localidade);
			jQuery("#campoRaca").val(retorno.raca);
			jQuery("#descricao-postagem").val(retorno.descricao);
			jQuery('input[value='+retorno.porte+']').attr('checked', true);
			
			var media = retorno.media;
			var tipoMedia = media.substring(media.length - 4,media.length);
			
			if(tipoMedia == ".jpg" || tipoMedia == ".png" || tipoMedia == ".gif"){
				jQuery("#media-preview").html("<img src='"+media+"' class='imagens-posts' />");
			}else if(tipoMedia == ".mp4"){
				jQuery("#media-preview").html("<video class='video-posts' controls> <source src='"+media+"' type='video/mp4' /> Desculpe, mas não foi possível carregar o áudio.</video>");
			}else{
			
			}
			mediaPost = media;
			//Apresenta o modal
			jQuery("#camada-fundo-site").fadeIn(300);
			jQuery("#container-postar").animate({top:"50%"},500);
			jQuery("html,body").css("overflow","hidden");
		},
		error: function(xhr, status, thrown){
			console.log("xhr: " + xhr + "\n status: " + status + "\n thrown: " + thrown);
		}
					
	});
}

function verificaCampo(){
		
	//Verifica campos:
	var formulario = document.getElementById("form-postar");
		
	//Chama todas a variáveis necessárias
	var codigo = cleanStr(formulario.codigo.value);
	var titulo = cleanStr(formulario.campoTitulo.value);
	var tipoPost = formulario.campoTipo.value;
	var descricao = formulario.campoDescricao.value;
	var media = mediaPost;
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
			
			//ajax palavrões
			jQuery.ajax({
				url: '_composicao_site/verifica_palavroes.php',
				type: 'post',
				dataType: 'json',
				data:{
					titulo: titulo,
					tipoPost: tipoPost,
					descricao: descricao,
					media: mediaPost,
					especie: especie,
					porte: porte,
					raca: raca,
					cor: cor,
					sexo: sexo,
					telefone: telefone,
					localidade: localidade,	
				},
				beforeSend: function(){
				
				},
				success: function(retorno){
				
									
					//Pega as variáveis retornadas
					var confirmacao = retorno.confirmacao;
					var input_de_erro = retorno.input_de_erro;
					var resposta = retorno.resposta;
									
					if(confirmacao){//Tem palavrão:
									
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
											
					}else{//Não tem
						jQuery("#container-confimacoes").css("top","45%");
						jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja editar publicação?");
						jQuery("#botao-confirma").on("click",function(){
							jQuery("#form-postar").submit();
							fecharModal();
						});
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
							
							//ajax palavões
							jQuery.ajax({
								url: '_composicao_site/verifica_palavroes.php',
								type: 'post',
								dataType: 'json',
								data:{
									titulo: titulo,
									tipoPost: tipoPost,
									descricao: descricao,
									media: mediaPost,
									especie: especie,
									porte: porte,
									raca: raca,
									cor: cor,
									sexo: sexo,
									telefone: telefone,
									localidade: localidade,	
								},
								beforeSend: function(){
							
								},
								success: function(retorno){
								
									
									//Pega as variáveis retornadas
									var confirmacao = retorno.confirmacao;
									var input_de_erro = retorno.input_de_erro;
									var resposta = retorno.resposta;
									//ultimoArquivo
									
									if(confirmacao){//Tem palavrão:
									
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
											
										}else if(input_de_erro == "descricao"){//Não tem:
												
											jQuery("#avisos-postar-descricao").html(resposta);//Scrola até aparecer o input
										
										}
											
											
									}else{
										jQuery("#container-confimacoes").css("top","45%");
										jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja editar publicação?");
										jQuery("#botao-confirma").on("click",function(){
											jQuery("#form-postar").submit();
											fecharModal();
										});
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
}
function editar(formulario){
	
	//Verifica campos:
	var formulario = document.getElementById("form-postar");
		
	//Chama todas a variáveis necessárias
	var codigo = cleanStr(formulario.codigo.value);
	var titulo = cleanStr(formulario.campoTitulo.value);
	var tipoPost = formulario.campoTipo.value;
	var descricao = formulario.campoDescricao.value;
	var media = mediaPost;
	var especie = "";
	var porte = "";
	var raca = "";
	var cor = "";
	var sexo = "";
	var telefone = "";
	var localidade = "";
	
	if(tipoPost == "casual"){
		
		jQuery.ajax({
			url: '_composicao_site/editar_publicacao.php',
			dataType: 'text',
			type: 'POST',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				codigo: codigo,
				titulo: titulo,
				tipoPost: tipoPost,
				descricao: descricao,
				media: mediaPost,
				especie: especie,
				porte: porte,
				raca: raca,
				cor: cor,
				sexo: sexo,
				telefone: telefone,
				localidade: localidade,	
			},
			beforeSend: function(){
				jQuery("#carregando-postar-container").fadeIn(300);
			},
			success: function(retorno){
				jQuery("#carregando-postar-container").fadeOut(300);
				if(retorno == "Alterado"){
					ultimoArquivo = "";
					fecharModal();
					window.setTimeout(window.location.replace("post.php?codigo="+codigo),300);

				}else{
					alert(retorno)
				}
			},
			error: function(){
				alert("Erro na hora de acionar o AJAX");
			}
		});
		
	}else{
		//Se o título não for casual pega a especie o porte a raça e a cor:
		especie = formulario.campoEspecie.value;
		porte = formulario.campoPorte.value;
		raca = formulario.campoRaca.value;
		cor = formulario.campoCor.value;
		sexo = formulario.campoSexo.value;
		telefone = formulario.campoTelefone.value;
		localidade = cleanStr(formulario.campoLocal.value);
		
		jQuery.ajax({
			url: '_composicao_site/editar_publicacao.php',
			dataType: 'text',
			type: 'POST',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				codigo: codigo,
				titulo: titulo,
				tipoPost: tipoPost,
				descricao: descricao,
				media: mediaPost,
				especie: especie,
				porte: porte,
				raca: raca,
				cor: cor,
				sexo: sexo,
				telefone: telefone,
				localidade: localidade,	
			},
			beforeSend: function(){
				jQuery("#carregando-postar-container").fadeIn(300);
			},
			success: function(retorno){
				jQuery("#carregando-postar-container").fadeOut(300);
				if(retorno == "Alterado"){
					ultimoArquivo = "";
					fecharModal();
					window.setTimeout(window.location.replace("post.php?codigo="+codigo),300);
				}else{
					alert(retorno)
				}
			},
			error: function(){
				alert("Erro na hora de acionar o AJAX");
			}
		});
		
	}
	return false;
}
/*CÓDIGO EDITAR POSTAGEM ACIMA ------------------------------------------------------------------------------------*/
var alturaDaTela = 0;
jQuery(document).ready(function() {
	alturaDaTela = jQuery(window).height();
	jQuery("#container-postar").css("max-height",alturaDaTela - 20);
	jQuery("#container-postar-de-dentro").css("max-height",alturaDaTela - 80);
	
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
	mediaPost = ultimoArquivo;
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
