<?php
// Recebe a variavel $id
// Exibe uma imagem com um link apontando para editar/$id
// utilizado nas views index e pesquisar

$imagem = $this->Html->image('edit24x24.png',array('title'=>"Editar registro ${id}",'alt'=>"Editar registro ${id}",));
print $this->Html->link($imagem, array('action' => 'editar',$id), array('escape'=>false),null, false);

?>