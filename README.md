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
A aplicação conta também com as rotas de login que ao passar os parâmetros de usuário informados na rota de registro, irá retornar um token
