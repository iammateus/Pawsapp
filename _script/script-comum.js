// JavaScript Document

$(document).ready(function() {
	
	var alturaDaTela = $(window).height();//Pega a altura de toda a tela
	var larguraDaTela = $(window).width();//Pega a largura detoda a tela
	
	$('#menu-mobile').css('height',(alturaDaTela));//Ajustando o tamanho do menu mobile
	
	$(window).scroll(function(){//Cria uma função para sempre que o é dado scroll executa alguns passos
	
		var quantidadeDeScroll = $(window).scrollTop();//Pega a quantidade de que foi scrollado (referente ao top do scroll)
		
		if(quantidadeDeScroll > (alturaDaTela - 200)){//Condição para aparecer ou não o ícone de voltar ao começo (se a tela tiver sido scrollada o suficiente então o icone aparece)
			$("#subir-tela").removeClass('desaparece');//Remove a classe que faz o ícone desaparecer
			$("#subir-tela").addClass('aparece');//Adiciona a classe que faz o ícone aparecer
			$("#logo-mobile").css("display","none");//se a tela não tiver sido scrollada o suficiente então o icone desaparece
		}else{
			$("#subir-tela").removeClass('aparece');//Remove a classe que faz o icone aparecer
			$("#subir-tela").addClass('desaparece');//Adiciona a classe que faz o ícone desaparecer
		}
	
	
	});
	
	$("#subir-tela").click(function(){//Função para subir a tela ate o topo
		$('html, body').animate({scrollTop:0},800);
	})
	
	$("#abrir-fechar-menu").click(function(){//Função para abrir e feixar menu mobile
		var leftElemento = document.getElementById("menu-mobile").offsetLeft;
		if(leftElemento < 0){
			$("#menu-mobile").animate({left:'0px'},800);
			$("#abrir-fechar-menu").attr("src","_imgs/fechar-menu.png");
			$("html,body").css({"overflow":"hidden"});
		}else{
			$("#menu-mobile").animate({left:'-100%'},800);
			$("#abrir-fechar-menu").attr("src","_imgs/abrir-menu.png");
			$("html,body").css({"overflow":"auto"});
		}
	});
	
});