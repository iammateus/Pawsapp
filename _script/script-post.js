// JavaScript Document
jQuery(document).ready(function(){
	jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
	jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
	resposividade();
	
	jQuery(window).resize(function() {
		resposividade();
	});

});
jQuery(window).on('load', function(){
	resposividade();
});
/*Codigo do formulário de postar daqui para cima ----------------------------------------------------------------------*/
function confirmacao(elemento,tipo,codigo){
	jQuery("#botao-confirma").off();
	jQuery("body").css("overflow","hidden");
	jQuery("#camada-fundo-site").fadeIn();
	jQuery("#container-confimacoes").css("top","45%");
	jQuery("#titulo-confirmacao").html("Confirmação");
	if(tipo == "excluir"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja excluir a postagem?");
		jQuery("#botao-confirma").on("click",function(){
			excluirPostagem(elemento,codigo);
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
	}else if(tipo == "edicao"){
		jQuery("#container-confimacoes").css("top","-50%");
		verificaCampo();		
	}else if(tipo == "sair"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja sair?");
		jQuery("#botao-confirma").on("click",function(){
			window.location.replace("post.php?sair=true");
		});	
	}
	
}
function fecharModal(){
	//Desaparece modal de confirmação
	jQuery("#container-confimacoes").animate({"top":"-50%"},500);
	jQuery("#form-motivo-denuncia").fadeOut();
	
	var topConfirmacao = parseFloat(jQuery("#container-confimacoes").css("top").replace("px",""));
	var topEditar = parseFloat(jQuery("#container-postar").css("top").replace("px",""));

	if(topConfirmacao < 0){
		jQuery("#camada-fundo-site").fadeOut(300);
		jQuery("#container-postar").animate({"top":"-50%"},500);
		jQuery("html,body").css("overflow","auto");
	}else if(topConfirmacao > 0 && topEditar < 0){
		jQuery("#camada-fundo-site").fadeOut(300);
		jQuery("html,body").css("overflow","auto");
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
				location.reload();
			}else{
				alert("Erro: "+retorno);
			}
			
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
	
}

function resposividade(){
	var larguraTela = jQuery(window).width();
	var larguraDoLogo = jQuery("#logo-cabecario").width();
	
	var leftDoLogo = (100 - (larguraDoLogo * 100)/larguraTela)/2;
	jQuery("#logo-cabecario").css({"left":leftDoLogo+"%"});
}



function excluirPostagem(elemento,codigoPost){

	jQuery.ajax({
		url: '_composicao_site/excluir_postagem.php',
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
				location.reload();
			}else{
				alert(retorno);
			}
		},
		error: function(){
			
		}
					
	});

}

function denunciarPostagem(elemento,codPostagem){
	var motivo = jQuery("input[name='motivoDenuncia']:checked").val();
	jQuery.ajax({
		url: '_composicao_site/denunciar_postagem.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codPostagem: codPostagem,
			motivo:motivo	
		},
		beforeSend: function(){

		},
		success: function(retorno){
			if(retorno == "Denunciado"){
				location.reload();
			}else{
				alert(retorno);	
			}
		},
		error: function(){
			
		}
					
	});
}
