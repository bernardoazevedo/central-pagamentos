## Logout

URL: `http://localhost/api/logout`
Método: `POST`  
Autenticação: `Sim`  
Forma de autenticação: `Bearer Token`

Resposta:

### Sucesso

Se estiver logado, receberá uma mensagem informando que foi deslogado com status `200`.

Status: `200`
```json
{
    "message": "Logged out"
}
```

---

