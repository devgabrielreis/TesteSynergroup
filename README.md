# Sistema de cadastro de clientes

![Alt text](/screenshots/website.png)

## Índice
#### Instalação e configuração
  - [Windows 10 via XAMPP](#windows-10-via-xampp)
#### Documentação da API
  - [GET /api/currency/get.php](#get-apicurrencygetphp)
  - [GET /api/client/get.php](#get-apiclientgetphp)
  - [POST /api/client/add.php](#post-apiclientaddphp)
  - [POST /api/client/update.php](#post-apiclientupdatephp)
  - [DELETE /api/client/remove.php](#delete-apiclientremovephp)

## Instalação e configuração

### Windows 10 via XAMPP
- Baixe e instale o XAMPP
- Depois da instalação, abra o XAMPP e clique em no botão "Config" que esta na mesma linha que o Apache, depois clique em "PHP (php.ini)
- Procure pela linha "extension=pdo_mysql" no arquivo que abrir e, caso haja um ";" no começo da linha, o apague e salve o arquivo
- Vá na pasta onde você instalou o XAMPP e procura pela pasta "htdocs"
- Apague ou mova todos os arquivos que estão dentro dessa pasta
- Baixe ou clone este repositório
- Mova todos os arquivos que estão dentro da pasta "TesteSynergroup" para a pasta htdocs
- Abra o XAMPP novamente
- Clique no botão "Start" que está na frente do Apache e do "MySQL"
- Abra um navegador e entre no site "https://localhost/phpmyadmin/"
- Clique na guia "SQL" na página que abrir
- Copie o conteúdo de [create_database.sql](sql/create_database.sql) e cole na página
- Execute os comandos
- Volte na para a guia "SQL" e agora copie e cole o conteúdo de [fill_currencies.sql](sql/fill_currencies.sql)
- Execute os comandos
- Volte para a guia "SQL" e copie e cole o conteúdo do arquivo [set_user.sql](sql/set_user.sql)
- Antes de executar o comando, substitua o <USER> pelo nome de usuário do sistema e <PASSWORD> pela senha do sistema, memorize esses valores
- Execute o comando
- Agora, [crie algumas variáveis de sistema](https://www.alura.com.br/artigos/configurar-variaveis-ambiente-windows-linux-macos) que serão utilizadas pelo sistema
- Crie uma variável chamada "CADASTRO_CLIENTES_DB_NAME"(sem as aspas) com o valor "cadastroclientes"(sem as aspas)
- Crie uma variável chamada "CADASTRO_CLIENTES_DB_HOST"(sem as aspas) com o valor "localhost"(sem as aspas)
- Crie uma variável chamada "CADASTRO_CLIENTES_DB_PASS"(sem as aspas) e coloque como valor a senha que você definiu no banco de dados
- Crie uma variável chamada "CADASTRO_CLIENTES_DB_USER"(sem as aspas) e coloque como valor o nome de usuário que você definiu no banco de dados
- Aperte OK para fechar as janelas
- Abra o XAMPP e reinicie o Apache e o MySQL (clique em "Stop" e depois em "Start")
- Após isso, abra seu navegador e entre em https://localhost e o sistema deverá estar funcionando

## Documentação da API
### GET /api/currency/get.php
Retorna uma lista com todas as moedas armazenadas no banco de dados

#### Parâmetros
Nenhum
#### Resposta
##### Sucesso (Código de resposta 200)
Retorna um JSON com uma lista contendo todas as moedas, cada moeda possui os seguintes atributos:
- code: (int) o código da moeda
- abbreviation: (string) a sigla da moeda
- decimalPlaces: (int) a quantidade de casas decimais da moeda
##### Falha(Código de resposta 400 ou 500)
Retorna um JSON com a mensagem de erro:
- error: (string) a mensagem de erro

### GET /api/client/get.php
Retorna uma lista com todas os clientes armazenados no banco de dados

#### Parâmetros
Nenhum
#### Resposta
##### Sucesso (Código de resposta 200)
Retorna um JSON com uma lista contendo todas os clientes, cada cliente possui os seguintes atributos:
- code: (string) o código do cliente
- clientName: (string) a razão social do cliente
- currencyCode: (int) o código da moeda do cliente
- creationDate: (string) a data da criação do cliente no formato yyyy-mm-dd
- lastSaleDate: (string|null) null ou a data da última venda do cliente, no formato yyyy-mm-dd
- totalSales: (float) o total de vendas do cliente
- currencyAbbreviation: (string|null) null ou a sigla da moeda do cliente
- currencyDecimalPlaces: (int|null) null ou a quantidade de casas decimais da moeda do cliente
##### Falha(Código de resposta 400 ou 500)
Retorna um JSON com a mensagem de erro:
- error: (string) a mensagem de erro

### POST /api/client/add.php
Adiciona um cliente no banco de dados

#### Parâmetros
Os parâmetros devem ser enviados num corpo de mensagem "application/json"
- code: (string) o código do cliente
- clientName: (string) a razão social
- currencyCode: (int) o código da moeda do cliente
#### Resposta
##### Sucesso (Código de resposta 200)
Nenhum corpo de resposta
##### Falha(Código de resposta 400 ou 500)
Retorna um JSON com a mensagem de erro:
- error: (string) a mensagem de erro

### POST /api/client/update.php
Edita os dados de um cliente

#### Parâmetros
Os parâmetros devem ser enviados num corpo de mensagem "application/json"
- oldCode: (string) o código do cliente a ser editado
- newCode: (string)(opcional) o novo código do cliente
- clientName: (string)(opcional) a nova razão social do cliente
- currencyCode: (int)(opcional) o código da nova moeda do cliente
- lastSaleDate: (string)(opcional) a nova data da última venda do cliente, no formato yyyy-mm-dd
- totalSales: (float)(opcional) o novo total de vendas do cliente
#### Resposta
##### Sucesso (Código de resposta 200)
Nenhum corpo de resposta
##### Falha(Código de resposta 400 ou 500)
Retorna um JSON com a mensagem de erro:
- error: (string) a mensagem de erro

### DELETE /api/client/remove.php
Remove um cliente do banco de dados

#### Parâmetros
Os parâmetros devem ser enviados num corpo de mensagem "application/json"
- code: (string) o código do cliente
#### Resposta
Nenhum corpo de resposta
##### Sucesso (Código de resposta 200)
Nenhum corpo de resposta
##### Falha(Código de resposta 400 ou 500)
Retorna um JSON com a mensagem de erro:
- error: (string) a mensagem de erro