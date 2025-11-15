[Voltar](../README.md)

# Transações

## Listar transações

URL: `http://localhost/api/transaction`  
Método: `GET`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  

### Sucesso

Se tudo correr bem, a API listará as transações retornando status `200`.  
Status: `200`
```json
[
    {
        "id": 1,
        "status": "PAID",
        "amount": 493895,
        "client": {
            "id": 1,
            "name": "José Santos",
            "email": "jose@jose.com"
        },
        "products": [
            {
                "id": 1,
                "name": "Ball",
                "amount": 16255,
                "quantity": 2
            },
            {
                "id": 2,
                "name": "Doll",
                "amount": 92277,
                "quantity": 5
            }
        ]
    },
    {
        "id": 2,
        "status": "PAID",
        "amount": 493895,
        "client": {
            "id": 1,
            "name": "João Silva",
            "email": "joao@joao.com"
        },
        "products": [
            {
                "id": 1,
                "name": "Ball",
                "amount": 16255,
                "quantity": 2
            },
            {
                "id": 2,
                "name": "Doll",
                "amount": 92277,
                "quantity": 5
            },
            {
                "id": 1,
                "name": "Ball",
                "amount": 16255,
                "quantity": 2
            },
            {
                "id": 2,
                "name": "Doll",
                "amount": 92277,
                "quantity": 5
            }
        ]
    }
]
```

### Erro

Se não houver transações cadastradas, será retornado status `404`, informando a situação.  
Status: `404`
```json
{
    "message": "No items stored"
}
```

---

## Buscar transação

URL: `http://localhost/api/transaction/{id}`  
Método: `GET`   
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` da transação  

### Sucesso

Se tudo correr bem, a API listará a transação identificada pelo ID informado, retornando status `200`.  
Status: `200`
```json
{
    "id": 2,
    "status": "PAID",
    "amount": 493895,
    "client": {
        "id": 1,
        "name": "João Silva",
        "email": "joao@joao.com"
    },
    "products": [
        {
            "id": 1,
            "name": "Ball",
            "amount": 16255,
            "quantity": 2
        },
        {
            "id": 2,
            "name": "Doll",
            "amount": 92277,
            "quantity": 5
        }
    ]
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


## Criar transação

Essa rota tenta efetuar o pagamento nos gateways registrados por prioridade, caso dê erro no primeiro, ele tenta efetuar no segundo e assim por diante. Caso não dê certo em nenhum, será retornado um erro.

URL: `http://localhost/api/transaction`  
Método: `POST`  
Autenticação: `Não`  
Request Body:
```json
{
    "client": {
        "name": "A new client",
        "email": "new@new.com"
    },
    "payment_info": {
        "card_numbers": "1111222233334444",
        "cvv": "123"
    },
    "products": [
        {
            "id": 1,
            "quantity": 2
        },
        {
            "id": 2,
            "quantity": 5
        }
    ]
}
```

### Sucesso

Se tudo correr bem, a API listará a transação identificada pelo ID informado, retornando status `201`.  
Status: `201`
```json
{
    "id": 1,
    "amount": 12599,
    "status": "PAID"
}
```

### Erro

Se não for informado nenhum produto, será retornado status `422` com uma mensagem informando a situação.  
Status: `422`
```json
{
    "message": "The products field is required.",
    "errors": {
        "products": [
            "The products field is required."
        ]
    }
}
```

Se não for possível realizar o pagamento em nenhum dos gateways, uma mensagem será retornada, com status `500`.  
Status: `500`
```json
{
    "message": "Error at payment, try again in a few moments"
}
```

---

## Reembolsar transação

URL: `http://localhost/api/transaction/{id}/chargeback`  
Método: `POST`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` da transação  

### Sucesso

Se tudo correr bem, a API listará a transação reembolsada, retornando status `201`.  
Status: `201`
```json
{
    "id": 1,
    "amount": 12599,
    "status": "CHARGED_BACK"
}
```

### Erro

Se não for encontrada uma compra com esse `id`, será informado o erro, com status `404`.  
Status: `404`
```json
{
    "message": "ID not found"
}
```

Se não for possível realizar o reembolso, uma mensagem será retornada, com status `500`.  
Status: `500`
```json
{
    "message": "Error at chargeback, try again in a few moments"
}
```
