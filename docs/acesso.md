[Voltar](../README.md)

# Acesso

## Login

URL: `http://localhost/api/login`  
Método: `POST`  
Autenticação: `Não`  
Request Body:
```json
{
    "email": "admin@admin.com",
    "password": "admin123"
}
```

### Sucesso

Você receberá um token de acesso, com status `200`. Esse token deve ser utilizado como Bearer Token nas rotas seguintes como meio de autenticação.  
Status: `200`
```json
{
    "access_token": "1|VdRTRUTURJHE9W6wOWYSLmSKU4HqqHnbXUy6ec8q39660e20"
}
```

### Erro

Se as informações de acesso estiverem incorretas, você será informado na resposta, que terá status `401`.  
Status: `401`
```json
{
    "message": "Invalid Credentials"
}
```

Caso não informe algum dos parâmetros, você receberá um erro informando a situação, com status `422`.  
Status: `422`
```json
{
    "message": "The email field is required.",
    "errors": {
        "email": [
            "The email field is required."
        ]
    }
}
```

---

## Logout

URL: `http://localhost/api/logout`  
Método: `POST`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`

### Sucesso

Se estiver logado, receberá uma mensagem informando que foi deslogado com status `200`.  
Status: `200`
```json
{
    "message": "Logged out"
}
```

