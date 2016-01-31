API de Bookmarks

Endereço para testes: http://jtony-bookmarks-api.herokuapp.com

Summary:
-API Rest construída com microframework Silex (baseado em Simfony) seguindo arquitetura MVC, hospedada no Heroku com nginx utilizando DB postgress e Redis

-Geração de senhas criptografadas com bcrypt dificultando invasões, com a seguinte estrutura 
      prefixo randômico armazenado no registro do usuário + a senha imputada pelo usuário + sufixo armazenado nas variáveis de ambiente do sistema

-Autenticação baseada na geração e consumo de Tokens armazenados em servidor Redis.  Distinção entre tokens de Administrador e Usuário do sistema (Usuários criam listas de bookmarks e tem acesso apenas a edição e visualização de suas listas. Administradores visualizam, mas não editam qualquer lista de todos os usuários)


No momento a API se encontra disponível para avaliação no endereço de testes, por isso não criei uma receita mais elaborada para sua configuração que no entanto é bastante simples, deve-se criar as tabelas localizadas em bco/tables, criar um servidor redis e as seguintes variáveis de ambiente:

BOOKMARKS_DBDRIVER=pdo_pgsql
BOOKMARKS_DBHOST=*****
BOOKMARKS_DBPORT=*****sua
BOOKMARKS_DBNAME=*****
BOOKMARKS_DBUSER=*****
BOOKMARKS_DBPWD=*****

BOOKMARKS_REDISSCHEME=*****
BOOKMARKS_REDISNAME=*****
BOOKMARKS_REDISHOST=*****
BOOKMARKS_REDISPORT=*****
BOOKMARKS_REDISPWD=*****

SECURITY_HASH_SUFIX=A9!*-0z@#5
TOKEN_TIMEOUT=300

CRYPT_METHOD=2a
CRYPT_COST=06
CRYPT_SALT=*****


O QUE FALTA FAZER:
-Refatorar Controllers e Logics para que utilizem DataTransferObjects para transferir dados (hoje com arrays e não é a maneira mais adequada) e também validar os dados imputados
-Aplicação cliente
-Swagger


ENDPOINTS:

OPEN ROUTES
POST http://jtony-bookmarks-api.herokuapp.com/users/auth
	REQUEST:
		FORM DATA
			email: usuariouser@gmail.com
			password: usuario
	RESPONSE:
		HEADERS:
			x-access-token: 
				200: valid-token-access
				404: null
		BODY: 
			200: "User usuariouser loged in"
			404: "User not found"


POST http://jtony-bookmarks-api.herokuapp.com/users
[this route redirect to 'POST:/users/auth' internaly]
	REQUEST:
		FORM DATA:
			name: Usuário Oliveira
			email: uoliveira@gmail.com
			nick: uoliveira
			password: uoliveira
	RESPONSE:
		HEADERS:
			x-access-token: 
				200: valid-token-access
				409: null
		BODY: 
			200: "User uoliveira loged in"
			409: "User with email: uoliveira@gmail.com already exists"



AUTENTICATE TOKEN ROUTES
GET http://jtony-bookmarks-api.herokuapp.com/users/{id_user}/bookmarks
	REQUEST:
		HEADERS:
			x-access-token: valid-token-access from /users/auth route
	RESPONSE:
		BODY:
			200: [{"id":1,"url":"www.t1.com"}, {"id":2,"url":"www.t2.com"}]
			401: unauthorized or expires token

POST http://jtony-bookmarks-api.herokuapp.com/{id_user}/bookmarks
	REQUEST:
		HEADERS:
			x-access-token: valid-token-access from /users/auth route
		FORM DATA
			url: www.t1.com
	RESPONSE:
		BODY:
			200: Url saved
			401: unauthorized or expires token
			409: Url: www.t1.com already exists