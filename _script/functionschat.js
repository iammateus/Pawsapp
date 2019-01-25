jQuery(function(){
	var userOnline = Number(jQuery('span.useronline').attr('id'));
	var clicou = [];

	function in_array(valor, array){
		for(var i = 0; i<array.length;i++){
			if(array[i] == valor){
				return true;
			}
		}
		
		return false;
	}
	
	
	function add_janela(id, nome, statusjanela){
		//pega a quantidade de janelas abertas:
		var janelas = Number(jQuery('#chats .janela').length);	
		//pega o tamanho de uma janela somado à margem entre elas e multiplica pela quantidade de janelas abertas no momento para se obter o espaço total ocupado pelas mesmas, a partir desse style será possivel posicionar uma nova janela quando ela for clicada na barra do chat, para elas não ficarem em cima uma da outra:
		var pixels = (270+5)*janelas;
		var style = 'float:none; position:absolute; bottom:0; right:'+pixels+'px';
		
		//o (':')significa de vc para com quem se está conversando, é o id que fica em usersonline, o 3:5.
		var splitDados = id.split(':');
		//usuario com quem se está conversando, o indice 1 é o numero 5 do id, com que se está conversando e o indice 0 é o numero 3 do id, que é você:
		var id_user = Number(splitDados[1]);
		
		var janela = '<div class="janela" id="janela_'+id_user+'" style="'+style+'">';
		janela += '<div class="header_janela"><a href="#" class="fechar">X</a><span class="nome">'+nome+'</span><span id="'+id_user+'" class="'+statusjanela+'"></span></div>';
    	janela += '<div class="body"><div class="mensagens"><ul></ul></div>';
        janela += '<div class="enviar_mensagem" id="'+id+'"><input type="text" name="mensagem" class="msg" id="'+id+'" /></div></div></div>';
		
		//verifica quantas janelas estão abertas no momento, a partir disso retorna a posição que a nova janela vai ocupar
		jQuery('#chats').append(janela);
}
		
function retorna_historico(id_conversa){
	//var ultimoId = -1;
	jQuery.ajax({
		type: 'POST',
		url: 'sys/historico.php',
		data:{conversacom: id_conversa, online: userOnline},
		dataType:'json',
		success: function(retorno){
			jQuery.each(retorno, function(i, msg){
				
				if(jQuery('#janela_'+msg.janela_de).length > 0){
					if(userOnline == msg.id_de){
						jQuery('#janela_'+msg.janela_de+' .mensagens ul').append('<li id="'+msg.id+'" class="eu"><p>'+msg.mensagem+'</p></li>')
					}else{
						jQuery('#janela_'+msg.janela_de+' .mensagens ul').append('<li id="'+msg.id+'"><p>'+msg.mensagem+'</p></li>');
					}
					
				}
			});
			
			[].reverse.call(jQuery('#janela_'+id_conversa+' .mensagens li')).appendTo(jQuery('#janela_'+id_conversa+' .mensagens ul'));
			var altura = jQuery('#janela_'+id_conversa+' .mensagens').height();
			jQuery('#janela_'+id_conversa+' .mensagens').animate({scrollTop: altura}, '500');
		}
	});	
}
	
	
	jQuery('body').on('click', '#usersonline a', function(){
		var id = jQuery(this).attr('id');
		
		jQuery(this).removeClass('comecar');
		
		var statusjanela = jQuery(this).next().attr('class');
		var splitIds = id.split(':');
		var idJanela = Number(splitIds[1]);
		
		
		//se o usuario tentar abrir uma janela que ainda não foi aberta na barra do chat, ou seja, se a quantidade de janelas com determinado id for igual a 0, então ele dará permissão para que essa janela seja aberta
		if(jQuery('#janela_'+idJanela).length == 0){
		//quando clicar no nome da pessoa na barra do chat ele abre uma nova janela:
			var nome = jQuery(this).text();
			add_janela(id, nome, statusjanela);
			retorna_historico(idJanela);
		}else{
			//para não poder abrir a mesma janela mais de uma vez
			jQuery(this).removeClass('comecar');
		}
		
	});
	
	//para MINIMIZAR A JANELA, quando clicar na header da janela ele vai pegar o body que está abaixo dessa header, ou seja, o que no código está depois do header, por isso o next, e vai sumir com ele em 80 milisegundos:
	jQuery('body').on('click', '.header_janela', function(){
		var next = jQuery(this).next();
		next.toggle(50);
	});
	
	//seleciona o close:
	jQuery('body').on('click', '.fechar', function(){
	//o parent vai pegar o que estiver antes do close, como tem dois parents ele vai pegar oque estiver anterior ao anterior do close que no caso é a janela:
		var parent = jQuery(this).parent().parent();
		var idParent = parent.attr('id');
		var splitParent = idParent.split('_');
		var idJanelaFechada = Number(splitParent[1]);
		
		//conta quantas janelas estão abertas e tira 1 já que a contagem começa do 0:
		var contagem = Number(jQuery('.janela').length)-1;
		//da esquerda para a direita, o indice começa do 0 então se tem 4 janelas abertas o indice vai até 3, sendo assim se 4 janelas estiverem abertas a primeira janela que foi aberta será o indice 3, a linha a seguir pega o indice da janela: 
		var indice = Number(jQuery('.fechar').index(this));
		//pega o numero de janelas abertas menos o indice da janela a ser fechada, o resultado será quantas janelas serão exibidas à frente da que foi excluida, se a excluida for a primeira janela da direita para a esquerda por exemplo e tiverem 4 janelas abertas (nºde janelas abertas contando do 0)3-(indice da janela)3=0 sendo assim nenhuma janela será exibida à frente da janela excluida
		var restamAfrente = contagem-indice;
		
		
		for(var i = 1; i <= restamAfrente; i++){
			//retira da direita a quantidade de pixels da janela junto ao espaçamento em 200 milisegundos
			jQuery('.janela:eq('+(indice+i)+')').animate({right:"-=275"}, 200);	
		}
		//se a janela for removida o comecar que é oque ativa a pessoa a abrir uma nova janela volta a funcionar para aquele id
		parent.remove();
		jQuery('#usersonline li#'+idJanelaFechada+' a').addClass('comecar');
		
	});
	//no body(parte abaixo do header da janela) ele vai pegar um comando keyup que é um comando de teclado e a classe do input que é o msg
	jQuery('body').on('keyup', '.msg', function(e){
		//se a pessoa apertar a tecla enter(tecla 13)
		if(e.which == 13){
			
			//a variavel texto recebe o que a pessoa digitou(this é o input no momento) o .val é pra pegar o valor do que o cara digitou, ou seja, o que ele digitou no input
			var texto = jQuery(this).val();
			var id = jQuery(this).attr('id');
			var split = id.split(':');
			var para = Number(split[1]);
			//pra mandar o post pra uma page
			jQuery.ajax({
				type: 'POST',
				dataType: 'text',
				url:'sys/submit.php',
				data: {mensagem: texto, de: userOnline, para: para},
				success: function(retorno){
					if(retorno == 'ok'){
						jQuery('.msg').val('');
					}else{
						alert("Ocorreu um erro")
					}
				},
				error: function(){
					alert("erro")
				}
				
			});	
		}	
	});
	//esse user talvez tenha que ser substituido pois ele quer o usuario que está online
	/*function verifica(timestamp, lastid, user){
		var t;
		jQuery.ajax({
			url:'sys/stream.php',
			type:'GET',
			data: 'timestamp='+timestamp+'&lastid='+lastid+'&user='+user,
			dataType:'json',
			success: function(retorno){
				//alert(retorno);
				clearInterval(t);
				if(retorno.status == 'resultados' || retorno.status == 'vazio'){
					t = setTimeout(function(){
					verifica(retorno.timestamp, retorno.lastid, userOnline);
					},1000);
					
					if(retorno.status == 'resultados'){
						jQuery.each(retorno.dados, function(i, msg){
							if(jQuery('#janela_'+msg.janela_de).length == 0){
								jQuery('#usersonline #'+msg.janela_de+' .comecar').click();
								clicou.push(msg.janela_de);
							}
							
							if(!in_array(msg.janela_de, clicou)){
								if(jQuery('.mensagens ul li#'+msg.id).length == 0 && msg.janela_de >0){
									if(userOnline == msg.id_de){
										jQuery('#janela_'+msg.janela_de+' .mensagens ul').append('<li class="eu" id="'+msg.id+'"><p>'+msg.mensagem+'</p></li>');	
									}else{
										jQuery('#janela_'+msg.janela_de+' .mensagens ul').append('<li id="'+msg.id+'">< div class="imgpequena"><img src="_imgs_users/'+msg.foto_usuario+'" border="0"/></div><p>'+msg.mensagem+'</p></li>');	
									}



								}					
							}	
						});
						var altura = jQuery('.mensagens').height();
						jQuery('.mensagens').animate({scrollTop: altura}, '500');
						
					}
					/*clicou[''];
					jQuery('#usersonline ul').html('');
					jQuery.each(retorno.users, function(i, user){
					var incluir = '<li id="'+user.id+'"><div class="imgpequena"<img src="_imgs_users/'+user.foto+'" border="0"/></div>';
					incluir += '<a hred="#" id="'+userOnline+' : '+user.id+'" class="comecar">'+user.nome+'</a>';
					incluir += '<span id="'+user.id+'" class="status'+user.status+'"></span></li>';
					jQuery('span#'+user.id).attr('class', 'status '+user.status);
					jQuery('#usersonline ul').append(incluir);
					});
				}else if(retorno.status == 'erro'){
					alert('Ficamos confusos, atualize a pagina');
				}
				
			},
			error:function(retorno){
				alert(retorno);
				clearInterval(t);
				t=setTimeout(function(){
					verifica(retorno.timestamp, retorno.lastid, userOnline);
				},15000);
			}
		});	
	}
	verifica(0,0,userOnline);
	console.log(clicou);
	*/
	
});
