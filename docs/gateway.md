[Voltar](../README.md)

# Gateways

## Editar gateway

URL: `http://localhost/api/gateway/{id}`  
Método: `PATCH`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` do gateway  
Request Body:
```json
{
    "is_active": 1,
    "priority": 2
}
```

**Observação:** Não é necessário enviar no body todos os dados para editar um gateway, você pode enviar apenas o campo que deseja atualizar. Exemplo:
```json
{
    "is_active": true
}
```

### Sucesso

Se tudo correr bem, a API retornará status `200` e os dados do gateway.  
Status: `200`
```json
{
    "id": 1,
    "name": "Pagamentos Corp",
    "is_active": true,
    "priority": 2
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
