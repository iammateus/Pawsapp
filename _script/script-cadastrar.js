// JavaScript Document
jQuery(document).ready(function(){
	//Marcarando input de telefone de contato e celular
	jQuery("#telefoneFixo").mask("(11) 9999-9999");
	jQuery("#celular").mask("(99) 99999-9999");
	
	//Mascara data nascimento
	$('#dataNasc').mask('99/99/9999');
	//Mascara celular adapitada para 8 e 9 dígitos
	jQuery("#celular").on("focusout",function(event){  
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
	
	//Inputs de texto sem caractere especial nem números
	jQuery(".inputTextoOnly").on("keyup",function(retornoEvento){
		var texto = jQuery(this).val();
		jQuery(this).val(strSemCarEspeciais(texto));
	});
	jQuery(".inputTextoOnly").on("focusout",function(retornoEvento){
		var texto = jQuery(this).val();
		jQuery(this).val(strSemCarEspeciais(texto));
	});
	
	//Código validação de nomee e sobrenome
	jQuery("#nome").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#nome").on("focusout",function(){
		var valorElemento = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		if(valorElemento.length < 1 || valorElemento == " "){
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Campo nome é obrigatório.");
		}
	});
	
	jQuery("#sobrenome").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#sobrenome").on("focusout",function(){
		var valorElemento = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		if(valorElemento.length < 1 || valorElemento == " "){
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Campo sobrenome é obrigatório.");
		}
	});
	
	//Válidação data de nascimento
	jQuery("#dataNasc").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#dataNasc").on("focusout",function(){
		var data = jQuery(this).val();
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		if(data.length < 10){
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Campo data de nascimento é obrigatório.");
		}else{
			var dataVetor = data.split('/');
			var dia = dataVetor[0];
			var mes = dataVetor[1];
			var ano = dataVetor[2];
			var agora = new Date();
			agora = agora.getFullYear();
			if(dia < 1 || dia > 31){
				jQuery(elemento).fadeIn(300);
				jQuery(elemento).children(".tooltiptext").html("Dia informado inválido.");
			}else if(mes < 1 || mes > 12){
				jQuery(elemento).fadeIn(300);
				jQuery(elemento).children(".tooltiptext").html("Mês informado inválido.");
			}else if(ano < 1900 || ano > agora){
				jQuery(elemento).fadeIn(300);
				jQuery(elemento).children(".tooltiptext").html("Informe uma ano entre 1900 á "+ agora +".");
			}
		
		}
	});
	
	jQuery("#uf").on("change",function(){
		if(jQuery(this).val() != "" && jQuery("#avisos-cadastrar-descricao").html() == "Campo estado obrigatório."){
			jQuery("#avisos-cadastrar-descricao").html("");
		}
	});
	
	jQuery("#cidade").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#cidade").on("focusout",function(){
		var valorElemento = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		if(valorElemento.length < 1 || valorElemento == " "){
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Campo cidade é obrigatório.");
		}
	});
		
	jQuery("#email").on("focusin",function(){
		jQuery(this).siblings(".esquerda-menor").fadeOut(300);
	});
	
	jQuery("#email").on("focusout",function(){
		var valorElemento = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".esquerda-menor");
		if(valorElemento.length < 1 || valorElemento == " "){
			jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Campo email é obrigatório.");
		}else if(!validateEmail(valorElemento)){
			jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Email informado inválido.");
		}else{
			buscaDisponibilidadeEmail(valorElemento);
		}
	});
	
	//configuração campo senha atual
	
	jQuery("#senha-atual").on('keypress', function(retornoEvento){
        if (retornoEvento.which == 32){//Verifica se a pessoa digitou espaço se digitou cancela pq a senha não aceita espaço
        	return false;
		}
    });
	
	jQuery("#senha-atual").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#senha-atual").on("focusout",function(){
		var senha_atual = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		if(senha_atual.length < 8){
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Senha curta, informe a senha corretamente.");
		}
	});
	
	jQuery("#senha").on('keypress', function(retornoEvento){
        if (retornoEvento.which == 32){//Verifica se a pessoa digitou espaço se digitou cancela pq a senha não aceita espaço
        	return false;
		}
    });
	
	jQuery("#senha").on('keyup', function(retornoEvento){
		if(retornoEvento.which !== 32){
        	passwortMeter(cleanStr(jQuery(this).val()));
		}
	});
	
	jQuery("#senha").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#senha").on("focusout",function(){
		var primeiraSenha = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".erro-input-esquerda");
		if(primeiraSenha.length < 8){
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Senha informada muito curta.");
		}
	});
	
	jQuery("#confirmarSenha").on('keypress', function(retornoEvento){
        if (retornoEvento.which == 32){//Verifica se a pessoa digitou espaço se digitou cancela pq a senha não aceita espaço
        	return false;
		}
    });
	
	jQuery("#confirmarSenha").on("focusin",function(){
		jQuery(this).siblings(".erro-input-esquerda").fadeOut(300);
	});
	
	jQuery("#confirmarSenha").on("focusout",function(){
		var primeiraSenha = cleanStr(jQuery("#senha").val());
		var segundaSenha = cleanStr(jQuery(this).val());
		var elemento = jQuery(this).siblings(".esquerda-menor");
		if(primeiraSenha == segundaSenha && primeiraSenha.length > 7){
			jQuery(elemento).removeClass("erro-input-esquerda").addClass("ok");
			jQuery(elemento).children(".tooltiptext").html("A senhas batem.");
			jQuery(elemento).fadeIn(300);			
		}else if(segundaSenha.length < 1){
			jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
			jQuery(elemento).children(".tooltiptext").html("Campo confirmar senha obrigatório.");
			jQuery(elemento).fadeIn(300);
		}else if(primeiraSenha != segundaSenha){
			jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
			jQuery(elemento).children(".tooltiptext").html("Campos senhas não batem.");
			jQuery(elemento).fadeIn(300);
		}
		
	});

});

function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function strSemCarEspeciais(texto){
		var DecChar;//Chama a variável usada para guardar os números decimais dos chars
		var vetorTexto = Array.from(texto);//Faz um vetor com o texto
		for(var x = 0;x < vetorTexto.length + 1;x++){//For para verificar o decimal de letra por letra do vetor
			DecChar = texto.charCodeAt(x);//pega o codigo decimal da letra do index
			if((DecChar > 32 && DecChar < 39) || (DecChar > 39 && DecChar < 65) || (DecChar > 90 && DecChar < 97) || (DecChar > 122 && DecChar < 127) || (DecChar > 127 && DecChar < 160) || (DecChar == 247)){
				vetorTexto[x] = "";//Se o código foi de caractere especial ou de número a letra é retirada	
			}
		}
		texto = vetorTexto.join("");//Texto recebe o vetor limpo de símbolos especiais e números
		return texto;//
}

function passwortMeter(password){
	
	if(!password){
		jQuery("#container-status-senha").fadeOut(300);
	}else{
		if(password.length < 8){
			jQuery("#container-status-senha").fadeIn(300);
			jQuery("#status-senha").removeClass().addClass("weak");
			jQuery("#status-senha").html("Curta");
		}else{
			
			var score = 0;
			
			// award every unique letter until 5 repetitions
			var letters = new Object();
			for(var i = 0;i < password.length;i++){
				letters[password[i]] = (letters[password[i]] || 0) + 1;
				score += 6.0 / letters[password[i]];
			}
		
			// bonus points for mixing it up
			var variations = {
				digits: /\d/.test(password),
				lower: /[a-z]/.test(password),
				upper: /[A-Z]/.test(password),
				nonWords: /\W/.test(password),
			}
		
			variationCount = 0;
			for (var check in variations) {
				variationCount += (variations[check] == true) ? 1 : 0;
			}
			score += (variationCount - 1) * 15;
			
			jQuery("#container-status-senha").fadeIn(300);
			jQuery("#container-status-senha").fadeIn(300);
			if(score < 31){
				//Fraca
				jQuery("#status-senha").removeClass().addClass("weak");
				jQuery("#status-senha").html("Fraca");
			}else if(score < 94){
				//Média
				jQuery("#status-senha").removeClass().addClass("medium");
				jQuery("#status-senha").html("Média");
			}else{
				//Forte
				jQuery("#status-senha").removeClass().addClass("strong");
				jQuery("#status-senha").html("Forte");
			}
					
		}
	}
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

function validacaoCadastrar(formulario){

	var nome = cleanStr(formulario.nome.value);
	var sobrenome = cleanStr(formulario.sobrenome.value);
	var dataNascimento = cleanStr(formulario.dataNasc.value);
	var sexo = cleanStr(formulario.sexo.value);
	var cidade = cleanStr(formulario.cidade.value);
	var data = cleanStr(formulario.dataNasc.value);
	var estado = cleanStr(formulario.uf.value);
	var telefoneFixo = cleanStr(formulario.telefoneFixo.value);
	var celular = cleanStr(formulario.celular.value);
	var email = cleanStr(formulario.email.value);
	var senha = cleanStr(formulario.senha.value);
	var confirmarSenha = cleanStr(formulario.confirmarSenha.value);
	var elemento;
	
	if(nome.length < 1 || nome == " "){//Verifica se o nome NÃO foi informado
	
		elemento = jQuery("#nome").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Campo nome é obrigatório.");
		jQuery('html,body').animate({scrollTop: 100});
		
	}else if(sobrenome.length < 1 || sobrenome == " "){//Verifica se o sobrenome NÃO foi informado
	
		elemento = jQuery("#sobrenome").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Campo sobrenome é obrigatório.");
		jQuery('html,body').animate({scrollTop: 100});
		
	}else if(data.length < 10){//Verifica se o data NÃO foi informado
	
		elemento = jQuery("#dataNasc").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Campo data de nascimento é obrigatório.");
		jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		
	}else{//Se a data estiver informada
	
		elemento = jQuery("#dataNasc").siblings(".erro-input-esquerda");
		var dataVetor = data.split('/');
		var dia = dataVetor[0];
		var mes = dataVetor[1];
		var ano = dataVetor[2];
		var agora = new Date();
		agora = agora.getFullYear();
		
		if(dia < 1 || dia > 31){//Verifica se informou o dia corretamente
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Dia informado inválido.");
			jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		}else if(mes < 1 || mes > 12){//Verifica se informou o mês corretamente
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Mês informado inválido.");
			jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		}else if(ano < 1900 || ano > agora){//Verifica se informou o ano corretamente
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Informe uma ano entre 1900 á "+ agora +".");
			jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		
		}else{//Se a data foi preenchida corretamente
			
			if(estado == ""){//Verifica o estado
				
				jQuery("#avisos-cadastrar-descricao").html("Campo estado obrigatório.")
				
			}else if(email.length < 1){//Verifica o tamanho do email
				
				elemento = jQuery(this).siblings(".esquerda-menor");
				jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
				jQuery(elemento).children(".tooltiptext").html("Campo email obrigatótio.");
				
			}else if(!validateEmail(email)){//Verifica se o email é valido
			
				elemento = jQuery(this).siblings(".esquerda-menor");
				jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
				jQuery(elemento).children(".tooltiptext").html("Email informado inválido.");
			
			}else{ 
				
				if(cidade.length < 1 || cidade == " "){
					
					elemento = jQuery("#cidade").siblings(".erro-input-esquerda");
					jQuery(elemento).fadeIn(300);
					jQuery(elemento).children(".tooltiptext").html("Campo cidade é obrigatório.");
					jQuery('html,body').animate({scrollTop: 130});//Scrola até aparecer o input
					
				}else if(senha.length < 8){
					elemento = jQuery("#senha").siblings(".erro-input-esquerda");
					jQuery(elemento).fadeIn(300);
					jQuery(elemento).children(".tooltiptext").html("Campo senha obrigatório.");
					
				}else if(senha != confirmarSenha){
					elemento = jQuery("#confirmarSenha").siblings(".erro-input-esquerda");
					jQuery(elemento).fadeIn(300);
					jQuery(elemento).children(".tooltiptext").html("Campo senhas não batem.");
				}else{
					jQuery.ajax({
						url: '_composicao_site/cadastrar.php',
						type: 'POST',
						dataType: 'json',
						data:{
							nome: nome,
							sobrenome: sobrenome,
							dataNascimento: dataNascimento,
							sexo: sexo,
							cidade: cidade,
							estado: estado,
							celular: celular,
							email: email,
							senha: senha,
							telefoneFixo: telefoneFixo,
							confirmarSenha: confirmarSenha,	
						},
						beforeSend: function(){
							jQuery("#carregando-cadastrar-container").stop().fadeIn(250);
						},
						success: function(retorno){

							jQuery("#carregando-cadastrar-container").stop().fadeOut(250);
							var confirmacao = retorno.confirmacao;
							var input_de_erro = retorno.input_de_erro;
							var resposta = retorno.resposta;
							if(confirmacao){
								window.location.replace("feed.php");
							}else{
								if(input_de_erro == "nome"){//Se o erro for no título
									var elemento = jQuery("#nome").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html(resposta);
									jQuery('html,body').animate({scrollTop: 100});
								
								}else if(input_de_erro == "sobrenome"){//Se o erro for na localidade
									var elemento = jQuery("#sobrenome").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html(resposta);
									jQuery('html,body').animate({scrollTop: 100});
								}else if(input_de_erro == "email"){//Se o erro for na descrição
									var elemento = jQuery("#email").siblings(".esquerda-menor");
									jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
									jQuery(elemento).children(".tooltiptext").html("Email indisponível.");
									jQuery(elemento).fadeIn(300);
								}else if(input_de_erro == "cidade"){//Se o erro for na descrição
									elemento = jQuery("#cidade").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html("Campo cidade é obrigatório.");
									jQuery('html,body').animate({scrollTop: 130});//Scrola até aparecer o input
								}
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
	return false;
}

function buscaDisponibilidadeEmail(email){
	var disponivel = false;
	jQuery.ajax({
		url: '_composicao_site/disponibilidade_email.php?email='+email,
		type: 'GET',
		dataType: 'text',
		success: function(retorno){
			var elemento = jQuery("#email").siblings(".esquerda-menor");
			if(retorno == "1"){
				jQuery(elemento).removeClass("erro-input-esquerda").addClass("ok");
				jQuery(elemento).children(".tooltiptext").html("Email disponível.");
				jQuery(elemento).fadeIn(300);
			}else if(retorno == "0"){
				jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
				jQuery(elemento).children(".tooltiptext").html("Email indisponível.");
				jQuery(elemento).fadeIn(300);
			}else{
				alert(retorno);
			}
		},
		error: function(){
			alert("Erro na hora de acionar o AJAX");
		}
	});
}

function voltar(logado){
	if(logado){
		window.location.replace('feed.php');
	}else{
		window.location.replace('index.php');
	}
}

//Configuração editar conta
function confirmacaoValidacaoEditarCadastrar(formulario,tipo){

	jQuery("body").css("overflow","hidden");
	jQuery("#camada-fundo-site").fadeIn();
	jQuery("#container-confimacoes").css("top","45%");
	jQuery("#botao-confirma").off();
	jQuery("#titulo-confirmacao").html("Confirmação");
	
	if(tipo == "editar"){
		jQuery("#conteudo-confirmacao").html("Você tem certeza que deseja editar seu cadastro?");
		jQuery("#botao-confirma").on("click",function(){
			validacaoEditarCadastrar(formulario);
			jQuery("#camada-fundo-site").fadeOut(500);
			jQuery("#container-confimacoes").animate({top:"-50"},500);
		});
	}
	return false;
	
	//excluirPostagem(elemento,$codigo_post)

}

function preencheFormulario(sexo,estado){
	jQuery("input[value="+sexo+"]").attr("checked",true);
	jQuery("#uf").val(estado).change();
}

function validacaoEditarCadastrar(formulario){
	var senhaAtual = cleanStr(formulario.senhaAtual.value);
	var nome = cleanStr(formulario.nome.value);
	var sobrenome = cleanStr(formulario.sobrenome.value);
	var dataNascimento = cleanStr(formulario.dataNasc.value);
	var sexo = cleanStr(formulario.sexo.value);
	var cidade = cleanStr(formulario.cidade.value);
	var data = cleanStr(formulario.dataNasc.value);
	var estado = cleanStr(formulario.uf.value);
	var telefoneFixo = cleanStr(formulario.telefoneFixo.value);
	var celular = cleanStr(formulario.celular.value);
	var email = cleanStr(formulario.email.value);
	var senha = cleanStr(formulario.senha.value);
	var confirmarSenha = cleanStr(formulario.confirmarSenha.value);
	var elemento;
	
	if(senhaAtual.length < 8){
		
		elemento = jQuery("#senha-atual").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Senha curta, informe a senha corretamente.");
	
	}else if(nome.length < 1 || nome == " "){//Verifica se o nome NÃO foi informado
	
		elemento = jQuery("#nome").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Campo nome é obrigatório.");
		jQuery('html,body').animate({scrollTop: 100});
		
	}else if(sobrenome.length < 1 || sobrenome == " "){//Verifica se o sobrenome NÃO foi informado
	
		elemento = jQuery("#sobrenome").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Campo sobrenome é obrigatório.");
		jQuery('html,body').animate({scrollTop: 100});
		
	}else if(data.length < 10){//Verifica se o data NÃO foi informado
	
		elemento = jQuery("#dataNasc").siblings(".erro-input-esquerda");
		jQuery(elemento).fadeIn(300);
		jQuery(elemento).children(".tooltiptext").html("Campo data de nascimento é obrigatório.");
		jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		
	}else{//Se a data estiver informada
	
		elemento = jQuery("#dataNasc").siblings(".erro-input-esquerda");
		var dataVetor = data.split('/');
		var dia = dataVetor[0];
		var mes = dataVetor[1];
		var ano = dataVetor[2];
		var agora = new Date();
		agora = agora.getFullYear();
		
		if(dia < 1 || dia > 31){//Verifica se informou o dia corretamente
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Dia informado inválido.");
			jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		}else if(mes < 1 || mes > 12){//Verifica se informou o mês corretamente
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Mês informado inválido.");
			jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		}else if(ano < 1900 || ano > agora){//Verifica se informou o ano corretamente
			jQuery(elemento).fadeIn(300);
			jQuery(elemento).children(".tooltiptext").html("Informe uma ano entre 1900 á "+ agora +".");
			jQuery('html,body').animate({scrollTop: 120});//Scrola até aparecer o input
		
		}else{//Se a data foi preenchida corretamente
			
			if(estado == ""){//Verifica o estado
				
				jQuery("#avisos-cadastrar-descricao").html("Campo estado obrigatório.")
				
			}else if(email.length < 1){//Verifica o tamanho do email
				
				elemento = jQuery(this).siblings(".esquerda-menor");
				jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
				jQuery(elemento).children(".tooltiptext").html("Campo email obrigatótio.");
				
			}else if(!validateEmail(email)){//Verifica se o email é valido
			
				elemento = jQuery(this).siblings(".esquerda-menor");
				jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
				jQuery(elemento).children(".tooltiptext").html("Email informado inválido.");
			
			}else{ 
				
				if(cidade.length < 1 || cidade == " "){
					
					elemento = jQuery("#cidade").siblings(".erro-input-esquerda");
					jQuery(elemento).fadeIn(300);
					jQuery(elemento).children(".tooltiptext").html("Campo cidade é obrigatório.");
					jQuery('html,body').animate({scrollTop: 130});//Scrola até aparecer o input
					
				}else if(senha.length < 8){
					elemento = jQuery("#senha").siblings(".erro-input-esquerda");
					jQuery(elemento).fadeIn(300);
					jQuery(elemento).children(".tooltiptext").html("Campo senha obrigatório.");
					
				}else if(senha != confirmarSenha){
					elemento = jQuery("#confirmarSenha").siblings(".erro-input-esquerda");
					jQuery(elemento).fadeIn(300);
					jQuery(elemento).children(".tooltiptext").html("Campo senhas não batem.");
				}else{
					jQuery.ajax({
						url: '_composicao_site/editar_cadastro.php',
						type: 'POST',
						dataType: 'json',
						data:{
							nome: nome,
							sobrenome: sobrenome,
							dataNascimento: dataNascimento,
							sexo: sexo,
							cidade: cidade,
							estado: estado,
							celular: celular,
							email: email,
							senhaAtual: senhaAtual,
							senha: senha,
							telefoneFixo: telefoneFixo,
							confirmarSenha: confirmarSenha,	
						},
						beforeSend: function(){
							jQuery("#carregando-cadastrar-container").stop().fadeIn(250);
						},
						success: function(retorno){
							jQuery("#carregando-cadastrar-container").stop().fadeOut(250);
							var confirmacao = retorno.confirmacao;
							var input_de_erro = retorno.input_de_erro;
							var resposta = retorno.resposta;
							if(confirmacao){
								window.location.replace("conta.php");
							}else{
								if(input_de_erro == "nome"){//Se o erro for no título
									var elemento = jQuery("#nome").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html(resposta);
									jQuery('html,body').animate({scrollTop: 100});
								
								}else if(input_de_erro == "sobrenome"){//Se o erro for na localidade
									var elemento = jQuery("#sobrenome").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html(resposta);
									jQuery('html,body').animate({scrollTop: 100});
								}else if(input_de_erro == "email"){//Se o erro for na descrição
									var elemento = jQuery("#email").siblings(".esquerda-menor");
									jQuery(elemento).removeClass("ok").addClass("erro-input-esquerda");
									jQuery(elemento).children(".tooltiptext").html("Email indisponível.");
									jQuery(elemento).fadeIn(300);
								}else if(input_de_erro == "cidade"){//Se o erro for na descrição
									elemento = jQuery("#cidade").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html("Campo cidade é obrigatório.");
									jQuery('html,body').animate({scrollTop: 130});//Scrola até aparecer o input
								}else if(input_de_erro == "senhaAtual"){
									elemento = jQuery("#senha-atual").siblings(".erro-input-esquerda");
									jQuery(elemento).fadeIn(300);
									jQuery(elemento).children(".tooltiptext").html("Senha atual informada está incorreta.");
								}
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
	return false;
}

function fecharModal(){
	jQuery("#camada-fundo-site").fadeOut(500);
	jQuery("#container-confimacoes").animate({top:"-50"},500);
}

