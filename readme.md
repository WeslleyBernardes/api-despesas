# API REST
## _DESPESAS_

### 🛠 Referências
| Camada | Nome |Documentação|Versão|
| ------ | ------ | ------ | ------ |
| Backend    | Gerenciador de pacote Composer     | [Link oficial](https://getcomposer.org/)            |      |
| Backend    | Laravel                            | [Link oficial](https://laravel.com/)                | 8.12 |
| Frontend   | Vue.js                             |                                                     |      |
| Database   | MySQL                              |                                                     | 5.7  |
| Auth       | JWT                               |[github](https://github.com/tymondesigns/jwt-auth) / [docs](https://jwt-auth.readthedocs.io/en/develop/)   |     |
| StatusCode   | Referência                              | [developer.mozilla.org](https://developer.mozilla.org/pt-BR/docs/Web/HTTP/Status)                                                    |    |

### 🚀 Docker e docker-compose
### Iniciar o projeto e acessando container app
É necessário esta na raiz do projeto para executar os comandos abaixo
```docker-compose
# Inicia os containners do projeto e verifica se existe alteração nos Dockerfile 
docker-compose up --build

# Inicia os containners em background
docker-compose up -d

# Acessar o container com php bash 
docker exec - it app bash
docker exec - it db bash

# Parar os containners
docker-compose down
```
## API
### Autenticação
 É necessário obter o token informando email e senha para poder inserir, atualizar e consultar registros.
```sh
# URL 
http://localhost:8080/api/login
# Deve ser informado email e password no body
```
Retorno positivo: `(application/json)` | : status: `: 200:` 

```sh
{
    "token": "JWT"
}
```
 Retorno negativo: `(application/json)` | : status: `: 403:` 
```sh
{
    "erro": "Usuario ou senha invalido!"
}
```
#### Inserção de registro método POST
 Usuário deve possuir token JWT
 O token deve ser informado no header como Authorization: Bearer {token}
 Campos:
 * descricao (Máximo 191 caracteres)
 * data (Somente até a data atual|formato: YYYY-MM-DD) 
 * usuario (somente usuários registrados)
 * valor (Somente valores positivos ou zerado)
```sh
# URL 
http://localhost:8080/api/v1/despesa
```
Retorno positivo: `(application/json)` | : status: `: 200:`
```sh
{
    "descricao": "{string}",
    "data": "{YYYY-MM-DD}",
    "usuario": "{string}",
    "valor": "{decimal 12,2}",
    "updated_at": "{timestamps}",
    "created_at": "{timestamps}",
    "id": 11
}
```
 Retorno negativo: `(application/json)` | : status: `: 422:` 
 ```sh
{
    "message": "The given data was invalid.",
    "errors": {
        "valor": [
            "Deve ser informado um valor positivo"
        ]
    }
}
```
VALIDAÇÕES
Os parametros das validações principais pode ser visualizados nos metodos do model Despesa.
As regras abaixo são aplicadas para as solicitações via POST e PUT.
Para as solicitações via PATCH os campos a serem verificados são informados dinamicamente.
 ```sh
public function rules(){
        return [
            'descricao'=>'max:191',
            'usuario'=>'required|exists:users,name',
            'valor'=>'numeric|gte:0',
            'data'=>'before:'.date('Y-m-d'),

        ];
    }
    public function feedback(){
        return  [
            'usuario.exists'=>'Usuário não encontrado.',
            'valor.numeric'=>'Deve ser informado um valor positivo',
            'valor.gte'=>'Deve ser informado um valor positivo',
            'descricao.max'=>'A descrição deve ter no máximo 191 caracteres.',
            'require'=>'O campo :attribute é obrigatório',
            'data.before'=>'A data não pode ser futura',
        ];
    }
```
#### Visualização de registro método GET
RETORNAR TODOS OS REGISTROS
```sh
http://localhost:8080/api/v1/despesa
```
Amostra de retorno:
 ```sh
[
    {
        "id": {int},
        "descricao": "{string}",
        "data": "{datetime}",
        "usuario": "{string}",
        "valor": "{decimal}",
        "created_at": "{timestamps}",
        "updated_at": "{timestamps}"
    },
    {
        "id": {int},
        "descricao": "{string}",
        "data": "{datetime}",
        "usuario": "{string}",
        "valor": "{decimal}",
        "created_at": "{timestamps}",
        "updated_at": "{timestamps}"
    }
]
```
FILTRO
Observações
Os campos o operador e o valor podem ser ajustados não necessariamente para filtrar o usuario, por exempo pode ser informado o campo descricao e operador like (filtro=descricao:like:%c%)
```sh
http://localhost:8080/api/v1/despesa?filtro=usuario:=:Fernando
```
Retorno: `(application/json)` | : status: `: 200:`
Segue o mesmo formato informado anteriormente.

CONSULTAR REGISTRO ESPECÍFICO
Informar o ID do registro na url
```sh
http://localhost:8080/api/v1/despesa/8
```
Retorno: `(application/json)` | : status: `: 200:`
Segue o mesmo formato informado anteriormente.

CONSULTA POR REGISTRO
Se o registro existir retorna os dados do registro caso não seja encontrado retorna um erro 
```sh
http://localhost:8080/api/v1/despesa/{id}
```
Retorno: `(application/json)` | : status: `: 200:`
Segue o mesmo formato informado anteriormente.

CONSULTAR REGISTRO ESPECÍFICO
Informar o ID do registro na url
```sh
http://localhost:8080/api/v1/despesa/8
```
Retorno: `(application/json)` | : status: `: 200:`
 ```sh
[
    {
        "id": {int},
        "descricao": "{string}",
        "data": "{datetime}",
        "usuario": "{string}",
        "valor": "{decimal}",
        "created_at": "{timestamps}",
        "updated_at": "{timestamps}"
    }
]
```
 Retorno negativo: `(application/json)` | : status: `: 404:` 
 ```sh
{
    "message": "The given data was invalid.",
    "errors": {
        "valor": [
            "Deve ser informado um valor positivo"
        ]
    }
}
ATUALIZAR UM REGISTRO
API responderá aos verbos PUT E PATCH sendo que para o verbo PUT será aplicado a validação de todos os campos seguindo os critérios informados na seção acima de VALIDAÇÃO caso seja informado o verbo PATCH a validação será aplicada somente se o campo for informado.

#### License

MIT

**Free Software, Hell Yeah!**

