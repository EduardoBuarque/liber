#Liber - Software livre para gestão comercial
##### Versão 0.3

###Sobre
Criado em php, utilizando o framework CakePHP em sua versão 2.3.

Destina-se a ser um software de fácil entendimento e utilização.

###Instalação
* Obtenha o CakePHP em: *https://github.com/cakephp/cakephp/zipball/2.3.0*
* Faça download do Liber em: *https://github.com/tobiasgnu/liber/zipball/master*
* Extraia ambos os arquivos baixados. 
* Mova o diretório obtido na extração do Cakephp para uma área acessível do seu servidor web** \* **
* Mova o diretório liber para dentro do diretório do cakephp
* Vá até o diretório do liber e execute o comando: git submodule update --init
* Certifique-se de que o diretório app/tmp tem permissoes de leitura e escrita para o usuário que executa o servidor web
* Importe o arquivo liber/Config/Schema/dump.sql no MySQL Server
* Edite o arquivo Config/database.php.default com as informações de acesso do banco de dados a ser utilizado e salve-o com o nome database.php
* Habilite o mod_rewrite, ou equivalente, no servidor web. (Instruções para [apache](http://book.cakephp.org/pt/view/917/Apache-e-mod_rewrite), [lighttp](http://book.cakephp.org/pt/view/918/Lighttpd-e-mod_magnet), [nginx](http://book.cakephp.org/pt/view/919/URLs-amig%C3%A1veis-em-nginx) e [IIS7](http://book.cakephp.org/pt/view/1636/URL-Reescrita-no-IIS7-Windows-hosts))
* Acesse o Liber através de um browser. Por exemplo: se os arquivos foram colocados na raiz do servidor web, na sua máquina local, acesse: http://127.0.0.1/cakephp/liber
* Por padrão o usuário 'liber' vem configurado com a senha '159951', ambos sem aspas simples

Mais acerca da instalação, acesse: *http://book.cakephp.org/pt/view/909/Preparando-a-instala%C3%A7%C3%A3o*

Nota: esta localização dos arquivos é a mais simples possível e não é recomendada para servidores de produção. Para mais informações visite: *http://book.cakephp.org/pt/view/912/Instala%C3%A7%C3%A3o*

###Contribuições/Dúvidas/Sugestões
Para sugestões e reportar erros utilize *https://github.com/tobiasgnu/liber/issues*

Contribuições podem ser efetuadas através do GitHub. Vide *http://help.github.com/fork-a-repo/*

Para outros assuntos relacionados, ou caso ache mais conveniente, envie um e-mail para tobiasette em gmail ponto com
