// JavaScript Document

function esqueceuSenha(){
	
	email = $("#email").val();
	
	$.ajax({
		url: '_composicao_site/esqueceu_senha.php',
		dataType: 'text',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			email: email	
		},
		beforeSend: function(){
			$("#avisos-logar").html("Espere...");
		},
		success: function(retorno){
			$("#avisos-logar").html(retorno);			
		},
		error: function(){
		
		}
	});
}

function entrar(dadosFormulario){
	
	email = dadosFormulario.email.value;
	
	senha = dadosFormulario.senha.value;
	
	if(senha.length > 7){
		
		$.ajax({
		
			url: '_composicao_site/entrar.php',
			dataType: 'text',
			type: 'post',
			contentType: 'application/x-www-form-urlencoded',
			data:{
				email: email,
				senha: senha	
			},
			beforeSend: function(){
				$("#avisos-logar").html("Espere...");
			},
			success: function(retorno){
				
				if(retorno == "1"){
					window.location.href = "feed.php";
				}else if(retorno == "2"){
					window.location.href = "adm.php";
				}else{
					$("#avisos-logar").html(retorno);
				}
				
			},
			error: function(){
			
			}
					
		});
		
	 	return false;
	}else{
		return false;
	}
	
}

function buscarDeslogado(){
	var casual = jQuery("#casual").is(":checked")?1:0;
	var doacao = jQuery("#doacao").is(":checked")?1:0;
	var perdidos = jQuery("#perdidos").is(":checked")?1:0;
	var encontrados = jQuery("#encontrados").is(":checked")?1:0;
	
	if(casual == 0 && doacao == 0 && perdidos == 0 && encontrados ==0){
		jQuery("#casual").click();
		casual = 1;
	}
	
		
	$.ajax({
		url: '_composicao_site/buscar_deslogado.php',
		dataType: 'html',
		type: 'post',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			casual: casual,
			doacao: doacao,
			perdidos: perdidos,
			encontrados: encontrados	
		},
		beforeSend: function(){
		},
		success: function(retorno){
			jQuery("#visualizacao-posts").html(retorno);
			
			var onImgLoad = function(selector, callback){
			$(selector).each(function(){
				if (this.complete) {
					callback.apply(this);
				}
				else {
					$(this).on('load', function(){
						callback.apply(this);
					});
				}
			});
			};
			onImgLoad('img', function(){
				$(this).animate({opacity:"1"},700);
				$(this).parent(".container-img").css("background-image","none");
			});
			jQuery('.gifplayer:not(.ready)').gifplayer();//Prepara o player do gif
			jQuery('.gifplayer:not(.ready)').addClass('ready');//Colcando a classe "ready" para que o gif não buge
		},
		error: function(){
		}
	});
}

// Abaixo uma função para centralizar as imagens (Logo e Cachorro desktop) e para fazer a tela inicial do mobile ficar com a altura da tela

function resposividade_tela_inicial(alturaDaTela,larguraDaTela){//A função recebe dois argumentos um é a altura da tela e o outro a largura 
	
	// Centralização das imagens do logo desktop e do cachorro na position absolute do site desktop
	if(alturaDaTela < 500){
		$("#logo-mobile").css({"height":(alturaDaTela-10),"padding-top":"5px"});
	}
	
	var larguraDoLogoMobile = document.getElementById('logo-mobile').offsetWidth;
	var leftDoLogoMobile = (100 - (224 * 100)/larguraDaTela)/2;//Calculo para encontrar a margem exata para a centalização do logo desktop
	$("#logo-mobile").css({"left":leftDoLogoMobile+"%"});
	var larguraDoLogo = 152;//Pega a largura da imagem do logo desktop
	var larguraDoCachorro = document.getElementById('imagem-cachorro').offsetWidth;//Pega a largura da imagem do cachorro desktop
	var leftDoLogo = (100 - (larguraDoLogo * 100)/larguraDaTela)/2;//Calculo para encontrar a margem exata para a centalização do logo desktop
	var leftDoCachorro = (100 - (larguraDoCachorro * 100)/larguraDaTela)/2;	//Calculo para encontrar a margem exata para a centalização do cachorro desktop	
	$("#logo").css("left",leftDoLogo+"%");//Coloca a margem encontrada no calculo na imagem do logo desktop (fazendo ela se centralizar em todas as telas automaticamente)
	$("#imagem-cachorro").css("left",leftDoCachorro+"%");//Coloca a margem encontrada no calculo na imagem do cachorro desktop (fazendo ela se centralizar em todas as telas automaticamente)
}
alturaDaTela = $(window).height();//Pega a altura de toda a tela
larguraDaTela = $(window).width();//Pega a largura detoda a tela
$(document).load(function() {
	alturaDaTela = $(window).height();//Pega a altura de toda a tela
	larguraDaTela = $(window).width();//Pega a largura detoda a tela
});
$(document).ready(function() {
	buscarDeslogado();
	
	var onImgLoad = function(selector, callback){
    $(selector).each(function(){
        if (this.complete) {
            callback.apply(this);
        }
        else {
            $(this).on('load', function(){
                callback.apply(this);
            });
        }
    });
	};
	onImgLoad('img', function(){
    	$(this).animate({opacity:"1"},700);
		$(this).parent(".container-img").css("background-image","none");
	});
	
	
	$("#email").focus(function(){
		$("#email").parent().css({"border-color":"#11b5bf"});
		$("#email ~ div").fadeOut();
	});
	$("#email").focusout(function(){
		$("#email").parent().css({"border-color":"rgba(0,0,0,0.7"});
		var emailInformado = $("#email").val();
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if(!re.test(emailInformado)){
    		$("#email ~ div").fadeIn();
			$("#email").parent().css({"border-color":"#d72828"});
		}
	});
	$("#senha").focus(function(){
		$("#senha").parent().css({"border-color":"#11b5bf"});
		$("#senha ~ div").fadeOut();
	})
	$("#senha").focusout(function(){
		$("#senha").parent().css({"border-color":"rgba(0,0,0,0.7"});
		var senhaInformada = $("#senha").val();
		if(senhaInformada.length < 8){
			$("#senha ~ div").fadeIn();
			$("#senha").parent().css({"border-color":"#d72828"});
		}
	});
	
	
	alturaDaTela = $(window).height();//Pega a altura de toda a tela
	larguraDaTela = $(window).width();//Pega a largura detoda a tela
	
	$('#cabecario-mobile').css('height',alturaDaTela);//Colocando a tela inicial mobile em 100%
	
	resposividade_tela_inicial(alturaDaTela,larguraDaTela);//Chama a função e coloca as variáveis como argumento
	
	$('#logo').animate({opacity:1},3000);//Animação simples do logo desktop para que ele apareça com uma leve atraso
	
	$(window).resize(function(){ //Cria uma função para sempre que a tela trocar de tamanho executar alguns passos
		
		larguraDaTela = $(window).width();//Pega a largura da tela atualizada
		
		resposividade_tela_inicial(alturaDaTela,larguraDaTela);//Chama a função de centralizar os imagens desktop
		
		/*	
			   A div "visualizacao" muda de margin-top constantemente 
			quando os dispositivo é mobile por conta do menu fixo po-
			rém quando o site é desktop ela deve ter sempre a margem
			do topo 0
		*/	
		
		if(larguraDaTela > 900){//Se a tela é desktop então:
			$("#visualizacao").css({"margin-top":"0px"});//Margem da div "visualizacao" é 0			
		}
	
	});
	
	$(window).scroll(function(){//Cria uma função para sempre que o é dado scroll executa alguns passos
		
		var quantidadeDeScroll = $(window).scrollTop();//Pega a quantidade de que foi scrollado (referente ao top do scroll)
		
		/*
			As animações em baixo só serão executadas se o dispositivo
			for mobile então temos um if para fazer essa verificação
		*/
		
		if(larguraDaTela <= 900){//IF de confirmação (só faz de o dispositivo for mobile)
			
			if(quantidadeDeScroll > (alturaDaTela - 90)){//Se o usuário deu scroll suficiente para descer toda a tela:
				
				$("#logo-mobile").css("display","none");//O logo mobile desaparece
				$("#cabecario-mobile").css({"height" : "80px","position": "fixed"});//O cabeçario fica fixo na tela e menor
				$("#visualizacao").css({"margin-top":alturaDaTela});//O campo de visualização se ajusta para não ficar escondido em cima (aqui o top da div "visualicacao" é alterado)
				
			}else{
				$("#visualizacao").css({"margin-top":"0px"});//O logo mobile aparece
				$("#cabecario-mobile").css({"height" : alturaDaTela,"position": "relative"});//O cabeçario fica relativo e maior
								
				//if(quantidadeDeScroll < alturaDaTela - 500){//Se o usuário voltou pro começo da pagina o logo reaparece
					$("#logo-mobile").stop().fadeIn(1500);//O logo mobile aparece
				//}
			}
		}
		
	});
	
	$("#ver-mais-ver-menos").click(function(){
		
		var heightElement = $("#cabecaio-visualizacao").height();
				
		if(heightElement < 350){
			$("#cabecaio-visualizacao").animate({height:'350px'},800);
			$("#opcoes-visualizacao").slideDown(800);
			$("#ver-mais-ver-menos").attr("src","_imgs/icone_ver_menos.png");
		}else{
			$("#cabecaio-visualizacao").animate({height:'170px'},800);
			$("#opcoes-visualizacao").slideUp(800);
			$("#ver-mais-ver-menos").attr("src","_imgs/icone_ver_mais.png");
		}
	});
	
	$(".logar").click(function(){
		$("#fundo-logar").fadeIn(100);
		$("#container-logar").animate({top:"50%"},350);
		$("html,body").css({"overflow":"hidden"});
	});
	$("#fechar-logar").click(function(){
		$("#container-logar").animate({top:"-50%"},350);
		$("#fundo-logar").fadeOut(350);
		$("html,body").css({"overflow":"auto"});
	});
	
	$(".botoes-de-opcoes").on("click",function(){
		buscarDeslogado();
	});
});
	
