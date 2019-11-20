<?php

$nome_imagem = $_FILES['imagem']['name'];
$tipo_imagem=$_FILES['imagem']['type'];
$tamanho_imagem=$_FILES['imagem']['size'];
$destino=$_SERVER['DOCUMENT_ROOT'] . '/public/imagens/';

move_uploaded_file($_FILES['imagem']['tmp_name'],$destino.$nome_imagem);

?>