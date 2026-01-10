Aplicação de registro de usuários

Projeto em Ambiente Docker em PHP Laravel, banco de dados MySQL, cashing com Redis, gerenciamento de filas com RabbitMQ e envio de email.

Os seguintes requisitos foram desenvolvidos:
- Receber cadastro via API
- Salvar usuário no banco de dados
- Armazenar usuário no cache
- Disparar fila para envio de email
- Simulação de envio de email
- Autenticar usuários logados
- Retornar token de acesso na aplicação

A rota principal do projeto é a de registrar usuário, ao efetuar o registro a aplicação irá enviar uma mensagem no respectivo email informado.
A aplicação conta também com as rotas de login, que ao passar os parâmetros de usuário informados na rota de registro, irá retornar um token e a rota GET de verificar usuário logado.

Configuração:
 - docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs para instalar as dependências do projeto.
 - Configurar o .env de forma adequada para rodar no ambiente desejado, com foco nos campos de aplicação (APP_), banco de dados (DB_).
 - ./vendor/bin/sail up -d --build para subir o container do projeto.
 - ./vendor/bin/sail artisan jwt:secret para gerar a chave do JWT. ./vendor/bin/sail artisan migrate para configurar a estrutura das tabelas.
 - ./vendor/bin/sail composer require vladimir-yuldashev/laravel-queue-rabbitmq para instalar a integração do rabbitmq
 - O envio de email já está setado na .env.example para enviar para o laravel.log, para testar o envio de email recomenda-se usar o mailtrap.
