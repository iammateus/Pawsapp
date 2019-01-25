
jQuery(document).ready(function(){
  jQuery("#container_usuarios_chat").css("height",parseInt(jQuery(window).height() - 52)+"px");
  fluxoChat();
});
function abreJanela(codigo,nome){
    if((jQuery("#container_conversas").children("div[chat='"+codigo+"']").length) < 1){
        jQuery("#container_conversas").append("<div class='janela_chat maximizada' chat='"+codigo+"'><header class='cabecario_conversas' onClick='mOuM(this)'>"+nome+"<span title='Fechar' class='fechar_janela' onClick='fecharJanela(this)'>X</span></header><div class='container_mensagens'></div><textarea onkeyup='checaBotaoChat(this)' class='campo_de_texto' placeholder='Escreva sua mensagem'></textarea></div>");
        jQuery.ajax({
      		  url: '_composicao_site/busca_mensagens_conversa.php',
      		  dataType: 'text',
      		  type: 'post',
      		  contentType: 'application/x-www-form-urlencoded',
            data:{
          			usuario2: codigo
          	},
        		beforeSend: function(){
          	},
          	success: function(retorno){
                if(retorno == "Essa conversa não tem mensagens."){
                    jQuery("div[chat='"+codigo+"']").children(".container_mensagens").append("<p class='no_mensagens'>Ah, você e "+nome+" não tem antigas mensagens, envie um Olá!:");
                }else{
                    jQuery("div[chat='"+codigo+"']").children(".container_mensagens").append(retorno);
                    jQuery("div[chat='"+codigo+"']").children(".container_mensagens").animate({
                				scrollTop: jQuery("div[chat='"+codigo+"']").children(".container_mensagens")[0].scrollHeight - jQuery("div[chat='"+codigo+"']").children(".container_mensagens")[0].clientHeight
              			}, 600);
                }
        		},
        		error: function(){
          	}
      	});
    }else if((jQuery("#container_conversas").children("div[chat='"+codigo+"']").not(".maximizada").length) > 0) {
        jQuery("#container_conversas").children("div[chat='"+codigo+"']").removeClass("minimizada");
        jQuery("#container_conversas").children("div[chat='"+codigo+"']").addClass("maximizada");
    }
    jQuery("div[chat='"+codigo+"']").children(".campo_de_texto").focus();
}
function mOuM(elemento){
    if(jQuery(elemento).parents(".janela_chat:not(.minimizada)").length < 1){
        jQuery(elemento).parents(".janela_chat").removeClass("minimizada");
        jQuery(elemento).parents(".janela_chat").addClass("maximizada");
    }else{
      jQuery(elemento).parents(".janela_chat").removeClass("maximizada");
      jQuery(elemento).parents(".janela_chat").addClass("minimizada");
    }
}
function fecharJanela(elemento){
    jQuery(elemento).parents(".janela_chat").remove();
}
function checaBotaoChat(elemento){
  //alert(window.event.keyCode)
  if(window.event.keyCode == 13){
      var usuario2 = jQuery(elemento).parents(".janela_chat").attr("chat");
      var mensagem = jQuery(elemento).val();
      mensagem = cleanStr(mensagem);
      mensagemCount = ((mensagem.replace(" ","")).replace(/(\r\n|\n|\r)/g,"")).length;
      if(mensagemCount > 0){
          jQuery(elemento).val("");
          jQuery.ajax({
              url: '_composicao_site/enviar_mensagem.php',
              dataType: 'text',
              type: 'post',
              contentType: 'application/x-www-form-urlencoded',
              data:{
                  usuario2: usuario2,
                  mensagem: mensagem
              },
              beforeSend: function(){
              },
              success: function(retorno){
                  if(retorno == "Mensagem enviada"){
                      jQuery(elemento).siblings(".container_mensagens").append("<div class='container_mensagem'><p class='enviada'>"+mensagem+"</p></div>");
                      jQuery(elemento).siblings(".container_mensagens").animate({
                          scrollTop: jQuery(elemento).siblings(".container_mensagens")[0].scrollHeight - jQuery(elemento).siblings(".container_mensagens")[0].clientHeight
                      }, 600);
                  }
              },
              error: function(){
              }
          });
      }else{
          jQuery(elemento).val("");
      }

  }else if(window.event.keyCode == 27){
      jQuery(elemento).parents(".janela_chat").remove();
  }
}
function fluxoChat(){
    jQuery.ajax({
        url: '_composicao_site/fluxo_chat.php',
        dataType: 'json',
        type: 'post',
        contentType: 'application/x-www-form-urlencoded',
        data:{
        },
        beforeSend: function(){
        },
        success: function(retorno){
            if(retorno.confirmacao){

              jQuery.each(retorno.mensagens, function(index, msg){
                //alert(msg.codigo+" "+msg.mensagem)

                if((jQuery("#container_conversas").children("div[chat='"+msg.codigo+"']").length) < 1){
                    abreJanela(msg.codigo,msg.nome);
                }else{
                    jQuery("#container_conversas").children("div[chat='"+msg.codigo+"']").removeClass("minimizada").addClass("maximizada");
                    //alert(jQuery("div[mensagem='"+msg.codigoMensagem+"']").length);
                    if(jQuery("div[mensagem='"+msg.codigoMensagem+"']").length < 1){
                      jQuery("div[chat='"+msg.codigo+"']").children(".container_mensagens").append("<div mensagem='"+msg.codigoMensagem+"' class='container_mensagem'><p class='recebida'>"+msg.mensagem+"</p></div>");
                    }
                }

                jQuery("div[chat='"+msg.codigo+"']").children(".container_mensagens").animate({
            				scrollTop: jQuery("div[chat='"+msg.codigo+"']").children(".container_mensagens")[0].scrollHeight - jQuery("div[chat='"+msg.codigo+"']").children(".container_mensagens")[0].clientHeight
          			}, 600);

              });

            }

          setTimeout(fluxoChat, 5000);

        },
        error: function(xhr, status, thrown){
		  setTimeout(fluxoChat, 5000);
		  console.log("Chat error");
        }
    });
}
