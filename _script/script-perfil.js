// JavaScript Document
var alturaDaTela = jQuery(window).height();
var larguraDaTela = jQuery(window).width();
jQuery(document).ready(function(){
	jQuery("#abrir-menu").on('click',function(){
		jQuery("#container-menu").fadeToggle(500);
	});
	jQuery("#container-posts").css("min-height",jQuery(window).height());
});
var continuaBusca = true;
var comeco = 0;
function buscaPostagens(codigoUser,tipoBusca){
	
	if(continuaBusca){
		jQuery.ajax({
			url: '_composicao_site/busca_post_usuario.php',
			dataType: 'json',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				codigoNego: codigoUser,
				tipoBusca: tipoBusca,
				comeco: comeco	
			},
			beforeSend: function(){
			},
			success: function(retorno){
				if(retorno.status == "posts encontrados"){
					jQuery("#container-posts").append(retorno.retornohtml);
					comeco = retorno.proximocomeco;
					//alert(retorno.proximocomeco)
					jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
					jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
				}else{
					continuaBusca = false;
					if(retorno.status == "fim de post"){
						jQuery("#container-posts").append("<span class='mensagem_perfil_posts'>Final das publicações.</span>");
						jQuery("#container-posts").css("min-height",0);
					}else{
						jQuery("#container-posts").append("<span class='mensagem_perfil_posts'>Usuário ainda não publicou nada.</span>");
						jQuery("#container-posts").css("min-height",0);
					}
				}
				
			},
			error: function(/*xhr, status, thrown*/erro){
				alert("xhr: " + xhr + "\n status: " + status + "\n thrown: " + thrown);
			}
		});
	}
}
function seguirNaoSeguir(elemento,codigo_usuario_perfil){
	jQuery.ajax({
		url: '_composicao_site/seguir_postador.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			codigoUsuarioAlvo: codigo_usuario_perfil,
			tipo: 1
		},
		beforeSend: function(){
			//jQuery(".gif-carregando").stop().fadeIn(250);
		},
		success: function(retorno){
			if(retorno == "Seguindo"){
				jQuery(elemento).html("-");
				
				jQuery("li[onClick='seguirUsuarioDeseguir(this,"+codigo_usuario_perfil+",1)']").each(function(){
					jQuery(this).html("Não Seguir");
				});	
				
			}else{
				jQuery(elemento).html("+");
				
				jQuery("li[onClick='seguirUsuarioDeseguir(this,"+codigo_usuario_perfil+",1)']").each(function(){
					jQuery(this).html("Seguir Usuário");
				});
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
					
	});
}