<?php
// Recebe a variavel $id
// Exibe uma imagem com um link apontando para detalhar/$id
// utilizado nas views index e pesquisar

$imagem = $this->Html->image('detalhar24x24.png',array('title'=>"Detalhar registro ${id}",'alt'=>"Detalhar registro ${id}",));
print $this->Html->link($imagem, array('action' => 'detalhar',$id), array('escape'=>false),null, false);

?>