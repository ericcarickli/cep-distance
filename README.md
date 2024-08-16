# Implantação
  1. Verifique se na sua máquina possui o docker instalado.
  2. Clone o projeto.
  3. Na raiz do projeto, rode o seguinte comando no terminal: 
```jsx
docker-compose up --build
```     
  4. Após o docker iniciar por completo, rode em outro terminal o seguinte comando:
 ```jsx
sh start-worker.sh
```     
   5. Acesse o projeto pelo navegador na url http://localhost:8000
   6. Teste da maneira que achar melhor :)

# Documentação

## Backend routes

| HTTP Request | Endpoint | Body | Descrição |
| ------ | ------ | ------ | ------ |
| GET | /distances | - | Lista todos as distâncias já calculadas |
| POST | /distance | {cep_from, cep_to} | Faz o cálculo da distância entre dois ceps |
| POST | /calculate-mass | multipart/form-data | Faz cáculo de distância em massa a partir de um arquivo .csv que deve possuir duas colunas 'CEP origem' e 'CEP fim' |

# Observações
- Na rota /distance utilizando método POST, é validado se os CEP's enviados são válidos, e se a Brasil API possui as coordenadas desse CEP, caso alguma dessas verificações não seja cumprida é lançado uma exception mostrando para o usuário que deu aconteceu um erro.
- Na rota /calculate-mass utilizando método POST, é carregado um arquivo .csv que deve possuir duas colunas 'CEP origem' e 'CEP fim', a partir do momento que a requisição é recebida, um novo job é criado e adicionado na fila para ser executado de forma assíncrona.
