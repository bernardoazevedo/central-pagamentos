[Voltar](../README.md)

# Clientes

## Listar clientes

URL: `http://localhost/api/client`  
Método: `GET`   
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  

### Sucesso

Se tudo correr bem, a API listará os clientes retornando status `200`.  
Status: `200`
```json
[
    {
        "id": 1,
        "name": "client Name",
        "amount": 2000
    },
    {
        "id": 2,
        "name": "client Name",
        "amount": 4050
    }
]
```

### Erro

Se não houver clientes cadastrados, será retornado status `404`, informando a situação.  
Status: `404`
```json
{
    "message": "No items stored"
}
```

---

## Buscar cliente

URL: `http://localhost/api/client/{id}`  
Método: `GET`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` do cliente

### Sucesso

Se tudo correr bem, a API listará o cliente identificado pelo ID informado e suas compras, retornando status `200`.  
Status: `200`
```json
{
    "id": 3,
    "name": "Client Name",
    "email": "client@client.com",
    "transactions": [
        {
            "id": 1,
            "status": "CHARGED_BACK",
            "amount": 493895
        },
        {
            "id": 2,
            "status": "PAID",
            "amount": 493895
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
