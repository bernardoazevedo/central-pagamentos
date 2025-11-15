[Voltar](../README.md)

# Usuários

## Criar usuário

URL: `http://localhost/api/user`  
Método: `POST`   
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Request Body:
```json
{
    "name": "Test User",
    "email": "test@test.com",
    "password": "test123@",
    "password_confirmation": "test123@",
    "role": "USER"
}
```

### Sucesso

Se tudo correr bem, a API retornará status `201` e os dados do usuário, com exceção da senha.  
Status: `201`
```json
{
    "id": 4,
    "name": "Test User",
    "email": "test@test.com",
    "role": "USER"
}
```

### Erro

Caso algum campo esteja faltando no corpo da requisição, será retornada uma mensagem informando qual campo está faltando, com status `422`.  
Status: `422`
```json
{
    "message": "The name field is required. (and 1 more error)",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "email": [
            "The email has already been taken."
        ]
    }
}
```

Caso já exista algum usuário com o e-mail informado, o erro será informado, também retornando status `422`.  
Status: `422`
```json
{
    "message": "The email has already been taken.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

---

## Listar usuários

URL: `http://localhost/api/user`  
Método: `GET`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  

### Sucesso

Se tudo correr bem, a API listará os usuários retornando status `200`.  
Status: `200`
```json
[
    {
        "id": 1,
        "name": "Administrador",
        "email": "admin@admin.com",
        "role": "ADMIN"
    },
    {
        "id": 2,
        "name": "User",
        "email": "user@user.com",
        "role": "USER"
    }
]
```

### Erro

Se não houver usuários cadastrados, será retornado status `404`, informando a situação.  
Status: `404`
```json
{
    "message": "No items stored"
}
```

---

## Buscar usuário

URL: `http://localhost/api/user/{id}`  
Método: `GET`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` do usuário  

### Sucesso

Se tudo correr bem, a API listará o usuário identificado pelo ID informado, retornando status `200`.  
Status: `200`
```json
{
    "id": 4,
    "name": "Test",
    "email": "test@test.com",
    "role": "USER"
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

## Editar usuário

URL: `http://localhost/api/user/{id}`  
Método: `PATCH`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` do usuário  
Request Body:
```json
{
    "name": "Test User",
    "email": "test@test.com",
    "password": "test123@",
    "password_confirmation": "test123@",
    "role": "USER"
}
```

**Observações:** 
- Não é necessário enviar no body todos os dados para editar um usuário, você pode enviar apenas o campo que deseja atualizar. Exemplo:
```json
{
    "name": "User"
}
```

- Se estiver atualizando a senha, também é necessário enviar o campo `password_confirmation` com a confirmação da senha.
Exemplo:
```json
{
    "password": "test123@",
    "password_confirmation": "test123@"
}
```


### Sucesso

Se tudo correr bem, a API retornará status `200` e os dados do usuário, com exceção da senha.  
Status: `200`
```json
{
    "id": 4,
    "name": "Test User",
    "email": "test@test.com",
    "role": "USER"
}
```

### Erro

Caso você envie `password` e não envie `password_confirmation`, será informado o erro, com status `422`.  
Status: `422`
```json
{
    "message": "The password field confirmation does not match.",
    "errors": {
        "password": [
            "The password field confirmation does not match."
        ]
    }
}
```

Caso já exista algum usuário com o e-mail informado, o erro será informado, também retornando status `422`.  
Status: `422`
```json
{
    "message": "The email has already been taken.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

Se o `id` não for encontrado, será retornado status `404` com uma mensagem informando a situação.  
Status: `404`
```json
{
    "message": "ID not found"
}
```

---

## Excluir usuário

URL: `http://localhost/api/user/{id}`  
Método: `DELETE`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`  
Parâmetros da rota: `id` do usuário  


### Sucesso

Se tudo correr bem, a API retornará status `200` e o id do usuário excluído.  
Status: `200`
```json
{
    "message": "3 deleted"
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

Se você tentar excluir o seu próprio usuário, será retornado status `406` com uma mensagem informando a situação.  
Status: `406`
```json
{
    "message": "You can't delete yourself"
}
```
