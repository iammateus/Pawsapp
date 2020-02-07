// JavaScript Document
function verMaisMenos(elemento){
	
	var label = jQuery(elemento).siblings("label");
	var ul = jQuery(elemento).parent("div").siblings("ul");
	
	if(jQuery(ul).css("display") == "none"){
		jQuery(ul).slideDown(500);
		jQuery(label).html("Ver menos");
		jQuery(elemento).removeClass("ver-mais").addClass("ver-menos");
	}else{
		jQuery(ul).slideUp(500);
		jQuery(label).html("Ver mais");
		jQuery(elemento).removeClass("ver-menos").addClass("ver-mais");
	}
	
	
}
function favoritarDesfavoritar(elemento,codigoPostagem){
		
	jQuery.ajax({
		url: '_composicao_site/favoritar_desfavoritar.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codPostagem: codigoPostagem	
		},
		beforeSend: function(){
		},
		success: function(retorno){
			if (retorno == "Adicionado aos favoritos"){
				jQuery(".botao-post[onClick='favoritarDesfavoritar(this,"+codigoPostagem+")']").each(function(){
					jQuery(this).removeClass("favoritar").addClass("desfavoritar");
				})	
			}else if (retorno == "Removido dos favoritos"){
				jQuery(".botao-post[onClick='favoritarDesfavoritar(this,"+codigoPostagem+")']").each(function(){
					jQuery(this).removeClass("desfavoritar").addClass("favoritar");
				})
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
	
}

function abrirFecharMenupostagem(elemento){
	jQuery(".menu-postagem").not(jQuery(elemento).siblings(".menu-postagem")).fadeOut(500);
	jQuery(elemento).siblings(".menu-postagem").fadeToggle(500);	
}

function seguirUsuarioDeseguir(elemento,codigoUsuarioAlvo,valor){
	
	jQuery.ajax({
		url: '_composicao_site/'+'seguir_postador.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoUsuarioAlvo: codigoUsuarioAlvo,
			tipo: valor
		},
		beforeSend: function(){
			//jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			if(valor == 1){
				if(retorno == "Seguindo"){
					jQuery(elemento).html("Não Seguir");
					
					//Na tela de perfil o botão de seguir e não seguir precisa ser modificado com + ou -
					jQuery("button[onClick='seguirNaoSeguir(this,"+codigoUsuarioAlvo+")']").each(function(){
						jQuery(this).html("-");
					});	
					
				}else{
					jQuery(elemento).html("Seguir Usuário");
					//Na tela de perfil o botão de seguir e não seguir precisa ser modificado com + ou -
					jQuery("button[onClick='seguirNaoSeguir(this,"+codigoUsuarioAlvo+")']").each(function(){
						jQuery(this).html("+");
					});
					
				}
			}else{
				if(retorno != "Seguindo"){
					jQuery(elemento).parent(".container-usuario").remove();
					jQuery("li[onClick='seguirUsuarioDeseguir(this,"+codigoUsuarioAlvo+",1)']").each(function(){
						jQuery(this).html("Seguir Usuário");
					})
					if(jQuery("#conteudo-popover").is(":visible") && jQuery("#conteudo-popover").children().length < 2){
						jQuery("#conteudo-popover").append("<div class='nenhum'> Você ainda não segue ninguém. </div>");
					}else if(jQuery("#seguindo-mobile").children().length < 2){
						jQuery("#seguindo-mobile").append("<div class='nenhum'> Você ainda não segue ninguém. </div>");
					}
				}
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
}

function cleanStr(str) {
    while (str.indexOf("\t") > -1) {
        str = str.replace("\t", " ");
    }
    while (str.indexOf("  ") > -1) {
        str = str.replace("  ", " ");
    }
    return str;
}

function comentar(formulario){
	
	try{
		codigoPostagem = parseInt(formulario.codigoPostagem.value);
	}catch(erro){
		alert(erro);
	}
	
	conteudo = formulario.textarea.value.toString();
	conteudoComentario = cleanStr(conteudo);
	
	if(conteudoComentario.length > 0){
		jQuery.ajax({
			url: '_composicao_site/'+'comentar.php',
			dataType: 'text',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				codigoPostagem:codigoPostagem,
				conteudoComentario: conteudoComentario	
			},
			beforeSend: function(){
				jQuery(formulario).children(".icone-enviar-comentar").stop().fadeIn(200);
			},
			success: function(retorno){
				if(retorno == "palavrao"){
					jQuery(formulario).children(".icone-enviar-comentar").attr("src", "_imgs/error-input.png");
					jQuery(formulario).children(".mensagem-comentar").html("Palavras ofensivas");
				}else if(retorno == "bem sucedido"){
					carregaUltimoComentario(formulario,codigoPostagem);
					jQuery(".icone-enviar-comentar").stop().fadeOut(200);
					jQuery(formulario).children(".textarea-comentar").val("");
				}else{
					jQuery(".icone-enviar-comentar").stop().fadeOut(200);
					alert(retorno);
				}
			},
			error: function(){
				alert("Erro na hora de acionar o AJAX");
			}
		});
	}
	return false;
}

function carregaUltimoComentario(elemento,codPostagem){
	
	jQuery.ajax({
		url: '_composicao_site/'+'carrega_ultimo_comentario.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoPostagem: codigoPostagem	
		},
		beforeSend: function(){
			//jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			var objDiv = jQuery(elemento).parent(".container-inputs-comentar").siblings(".container-comentarios");
			jQuery(objDiv).append(retorno).animate({
  				scrollTop: jQuery(objDiv)[0].scrollHeight - jQuery(objDiv)[0].clientHeight
			}, 1000);
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
	
}

function excluirComentario(elemento,codigo_comentario){
	jQuery.ajax({
		url: '_composicao_site/excluir_comentario.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codComentario: codigo_comentario,	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			if(retorno == "Excluido"){
				jQuery(elemento).closest(".comentarios").slideUp(200);
			}else{
				alert(retorno);	
			}
		},
		error: function(){
			
		}
					
	});
}

function carregarMaisComentarios(elemento,codPostagem,pagina){
	
	jQuery.ajax({
		url: '_composicao_site/mais_comentarios.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoPostagem: codPostagem,
			pagina: pagina	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			if(retorno.indexOf("<a style='display:none;'>continuaBusca</a>") !== -1){
				//alert("continua");
				jQuery(elemento).attr("onClick", "carregarMaisComentarios(this,"+codPostagem+","+(pagina+1)+")");
			}else{
				//alert("Não continua");
				jQuery(elemento).stop().fadeOut(200);
			}
			var objDiv = jQuery(elemento).siblings(".container-comentarios");
			jQuery(objDiv).append(retorno).animate({
  				scrollTop: jQuery(objDiv)[0].scrollHeight - jQuery(objDiv)[0].clientHeight
			}, 1000);	
		},
		error: function(){
			
		}
					
	});	
}

function ocultarComentario(elemento){
	jQuery(elemento).closest(".comentarios").slideUp(200);
}

function limpaComentar(elemento){
	jQuery(elemento).siblings(".mensagem-comentar").html("");
	jQuery(elemento).siblings(".icone-enviar-comentar").stop().fadeOut(200);
}

function denunciarComentario(elemento,codigoComentario){
	var motivo = jQuery("input[name='motivoDenuncia']:checked").val();
	jQuery.ajax({
		url: '_composicao_site/denunciar_comentario.php',
		dataType: 'text',
		type: 'POST',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoComentarioAlvo: codigoComentario,
			motivoDenuncia: motivo,
		},
		beforeSend: function(){
			//jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			if(retorno == "Denunciado"){
				jQuery(elemento).closest(".comentarios").slideUp(200);
			}else{
				alert(retorno);
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
}
jQuery(document).ready(function() {
    jQuery("html").click(function(e){//Se o usuário clicar em algo
		if(!jQuery(e.target).hasClass("abrir-menu-postagem") && !jQuery(e.target).closest("div").hasClass("menu-postagem") && !jQuery("#camada-fundo-site").is(":visible")){//Se o elemento clicado não for o botão de abrir o menu E não for algum elemento do proprio menu
			jQuery('.menu-postagem').fadeOut(300);//Menu some.
		}
	});
});

