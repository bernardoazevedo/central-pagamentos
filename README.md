## Documentação de rotas da API:

- [Acesso](docs/acesso.md)
- [Client](docs/client.md)
- [Gateway](docs/gateway.md)
- [Product](docs/product.md)
- [Transaction](docs/transaction.md)
- [User](docs/user.md)

## Instalação

- Baixe o código compactado e extraia ou clone o repositório com:
```sh
git clone https://github.com/bernardoazevedo/central-pagamentos.git
```
- Copie o arquivo `.env.example` para `.env`.
- Inicie os serviços: 
```sh
docker compose up
```
- Acesse o container da nossa aplicação com: 
```sh
docker exec -it central-pagamentos-app bash
```
- Gere as chaves de criptografia com: 
```sh
php artisan key:generate
```
- Faça a migração do banco de dados com: 
```sh
php artisan migrate
```
- Popule o banco de dados com os dados necessários com: 
```sh
php artisan db:seed
```
- Será cadastrado um usuário administrador com e-mail `admin@admin.com` e senha `admin123`, use essas informações para fazer o primeiro login.

## Cadastrar novo gateway de pagamento

- Para facilitar a adição de novos gateways e padronizar os processos, as integrações com um gateway de pagamento são chamadas por meio do `ReflectionClass`.
- Portanto, para cadastrar um novo gateway, você deve criar uma nova classe em `App\Gateways\Services` e implementar a interface `App\Gateways\GatewayInterface`.
- Após isso, você deve cadastrar esse gateway no banco de dados informando seu nome, status (ativo ou não), prioridade e nome da classe (deve ser exatamente o mesmo nome da classe criada).
- URL e credenciais de acesso do gateway devem ser adicionados ao `.env`.
- É importante adicionar o novo gateway nos testes, para isso, você deve deve adicioná-lo após os gateways já registrados em cada teste e adicionar a URL e credenciais no `.env.example`.