## Listar clientes

URL: `http://localhost/api/client`
Método: `GET` 
Autenticação: `Sim`
Forma de autenticação: `Bearer Token`

Resposta:

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

Resposta:

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
            "gateways_id": 1,
            "external_id": "ed8e556a-d1de-4f5c-8512-9a199654df7d",
            "status": "PAID",
            "amount": 12599,
            "card_last_numbers": "4444"
        },
        {
            "id": 2,
            "gateways_id": 1,
            "external_id": "e0022744-4041-49db-8904-47b8a28ce37f",
            "status": "PAID",
            "amount": 0,
            "card_last_numbers": "4444"
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