<?php
	@$connect = mysql_connect('localhost','mateus','1234');
	if(!mysql_select_db('pawsapp',$connect)){
		echo "Erro ao selecionar banco de dados :( ";
	}
	mysql_query("SET NAMES utf8");
?>