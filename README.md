APLICAÇÃO CLIENTE: https://jtony-bookmarks.herokuapp.com/

API de Bookmarks

Endereço para testes da API: http://jtony-bookmarks-api.herokuapp.com

Summary:
-API Rest construída com microframework Silex (baseado em Simfony) seguindo arquitetura MVC, hospedada no Heroku com nginx utilizando DB postgress e Redis

-Geração de senhas criptografadas com bcrypt dificultando invasões, com a seguinte estrutura 
      "prefixo randômico armazenado no registro do usuário + a senha imputada pelo usuário + sufixo armazenado nas variáveis de ambiente do sistema"

-Autenticação baseada na geração e consumo de Tokens armazenados em servidor Redis.  Distinção entre tokens de Administrador e Usuário do sistema (Usuários criam listas de bookmarks e tem acesso apenas a edição e visualização de suas listas. Administradores visualizam, mas não editam qualquer lista de todos os usuários)


No momento a API se encontra disponível para avaliação no endereço de testes, por isso não criei uma receita mais elaborada para sua configuração que no entanto é bastante simples.


O QUE FALTA FAZER:
-Swagger