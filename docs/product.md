[Voltar](../README.md)

# Produtos

## Criar produto

URL: `http://localhost/api/product`
Método: `POST` 
Autenticação: `Sim`
Forma de autenticação: `Bearer Token`
Request Body:
```json
{
    "name": "Product Name",
    "amount": 2000
}
```
Observação: O campo `amount` se refere ao valor da compra em centavos.

Resposta:

### Sucesso

Se tudo correr bem, a API retornará status `201` com os dados do produto criado.

Status: `201`

```json
{
    "id": 1,
    "name": "Product Name",
    "amount": 2000
}
```

### Erro

Caso algum campo esteja faltando no corpo da requisição, será retornada uma mensagem informando qual campo está faltando, com status `422`.

Exemplo:

Status: `422`

```json
{
    "message": "The amount field is required.",
    "errors": {
        "amount": [
            "The amount field is required."
        ]
    }
}
```

---

## Listar produtos

URL: `http://localhost/api/product`
Método: `GET` 
Autenticação: `Sim`
Forma de autenticação: `Bearer Token`

Resposta:

### Sucesso

Se tudo correr bem, a API listará os produtos retornando status `200`.

Status: `200`

```json
[
    {
        "id": 1,
        "name": "Product Name",
        "amount": 2000
    },
    {
        "id": 2,
        "name": "Product Name",
        "amount": 4050
    }
]
```

### Erro

Se não houver produtos cadastrados, será retornado status `404`, informando a situação.

Status: `404`
```json
{
    "message": "No items stored"
}
```

---

## Buscar produto

URL: `http://localhost/api/product/{id}`
Método: `GET` 
Autenticação: `Sim`
Forma de autenticação: `Bearer Token`
Parâmetros da rota: `id` do produto

Resposta:

### Sucesso

Se tudo correr bem, a API listará o produto identificado pelo ID informado, retornando status `200`.

Status: `200`
```json
{
    "id": 3,
    "name": "Product Name",
    "amount": 2000
}
```

### Erro

Se o `id` não for encontrado, será retornado status `404` com uma mensagem informando a situação.

Status: `404`
```json
{
    "message": "ID not found"
}
```

---

## Editar produto

URL: `http://localhost/api/product/{id}`
Método: `PATCH` 
Autenticação: `Sim`
Forma de autenticação: `Bearer Token`
Parâmetros da rota: `id` do produto
Request Body:
```json
{
    "name": "New Name",
    "amount": 8599
}
```

Observações: 
- Não é necessário enviar no body todos os dados para editar um produto, você pode enviar apenas o campo que deseja atualizar.
Exemplo:
```json
{
    "name": "New Name"
}
```


Resposta:

### Sucesso

Se tudo correr bem, a API retornará status `200` e os dados do produto.

Status: `200`

```json
{
    "id": 3,
    "name": "New Name",
    "amount": 8599
}
```

### Erro

Se o `id` não for encontrado, será retornado status `404` com uma mensagem informando a situação.

Status: `404`
```json
{
    "message": "ID not found"
}
```

Se o `amount` enviado for diferente de um valor inteiro, será retornado status `404` e o erro.

Status: `404`
```json
{
    "message": "The amount field must be an integer.",
    "errors": {
        "amount": [
            "The amount field must be an integer."
        ]
    }
}
```

---

## Excluir produto

URL: `http://localhost/api/product/{id}`
Método: `DELETE` 
Autenticação: `Sim`
Forma de autenticação: `Bearer Token`
Parâmetros da rota: `id` do produto


Resposta:

### Sucesso

Se tudo correr bem, a API retornará status `200` e o id do produto excluído.

Status: `200`

```json
{
    "message": "2 deleted"
}
```

### Erro

Se o `id` não for encontrado, será retornado status `404` com uma mensagem informando a situação.

Status: `404`
```json
{
    "message": "ID not found"
}
```
