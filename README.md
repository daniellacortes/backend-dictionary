# Back-end Challenge - Dictionary

## Introdução

Este projeto foi realizado a fim de completar o desafio proposto pela Coodesh a fim de que eu possa demonstrar as minhas habilidades como Back-end Developer. O projeto está alocado no repositório [coodesh/backend-dictionary](https://github.com/coodesh/backend-dictionary).

O intuito desse projeto é desenvolver um aplicativo para listar palavras em inglês, utilizando como base a API [Free Dictionary API](https://dictionaryapi.dev/), de modo que possam ser exibidos termos em inglês e gerenciar as palavras visualizadas.


## Desenvolvimento do Projeto

Nesse tópico serão descritas as etapas realizadas para a produção do presente projeto, sendo dividido em subtópicos para melhor compreensão.

### Primeiros Passos

A princípio, as instruções foram lidas atentamente a fim de identificar a lógica e as tecnologias, metodologias e modo de desenvolvimento que deveriam ser aplicados.

As tecnologias, metodologias e modo de desenvolvimento foram escolhidos atendendo aos seguintes pontos:
- Adequação à proposta do desafio;
- Enquadramento ao que foi proposto e ao prazo;
- Requisitos na vaga pretendida;
- Diferenciais na vaga pretendida;
- Demonstração das habilidade da autora.

Diante disso, a princípio, para o projeto será utilizado:
- a linguagem PHP 8.2;
- o framework Laravel 11.34;
- o banco de dados MySQL;
- para os testes unitários PHPUnit;
- conteinerização com o Docker;
- aplicação de padrões Clean Code;
- validação de chamadas assíncronas;
- descrição da documentação da API utilizando o conceito de Open API 3.0;
- instrução de como instalar e usar o projeto;
- deploy na AWS;
- implementação de paginação com cursores;
- commit diário (no mínimo) do desenvolvimento do projeto no GitHub e BitBucket;
- descrição diária (no mínimo) das atividades realizadas no README.md.

### Instalação do Projeto

Para a instalação do projeto, foi iniciado dentro do terminal Ubuntu (usando WSL2) um projeto Laravel usando Sail para a conteinerização no Docker.
Na sequência, o projeto iniciado foi sincronizado com o repositório remoto do GitHub e do BitBucket.

### Criação do Banco de Dados
Localmente, foi criado o banco de dados e o arquivo .env foi configurado para que o projeto possa se conectar ao banco de dados.
Após, foi criada a tabela "Words" através de uma migration para que o projeto possa ser executado, bem como o modelo da mencionada tabela.

### Importação das Palavras
Na sequência, foi criado um comando que possui uma função para que o projeto possa importar as palavras do [arquivo](https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json) disponibilizado no repositório da API Free Dictionay API para o banco de dados.

Como o arquivo estava em formato JSON, foi necessário realizar o tratamento dos dados no arquivo de Comando para que fosse possível importar as palavras para o banco de dados.

Ocorre que ao tentar inserir as palavras no banco de dados, foi identificado que o arquivo possuía mais de 300.000 palavras, o que resultou em um tempo de execução muito grande e, por consequência, um erro de timeout.

Diante disso, o array de palavras foi dividido em partes menores, através do método array_chunck, e, em seguida, inseridas no banco de dados. Arbitrariamente foi estipulado o tamanho do array de 1000 palavras, o que gerou resultados satisfatórios. Devido ao curto prazo, não foi possível implementar um tratamento para que o tamanho do array fosse dinâmico, de modo que o tempo de execução atingisse a sua melhor performance.

Antes de importar as palavras, foi criado um comando para que o projeto possa limpar a tabela "Words" do banco de dados. Essa decisão foi tomada tanto para que não fossem inseridas na tabela palavras duplicadas, quanto para que a tabela não contasse eventualmente palavras retiradas do arquivo, o que poderia gerar inconsistências.

#### Testando a Importação das Palavras
Foi criado um teste unitário para que o projeto possa testar o comando de importação de palavras a fim de verificar se as palavras foram importadas corretamente, se o banco de dados está sendo devidamente limpo e se os erros estão sendo tratados corretamente.

Para isso foi usado o PHPUnit e a resposta da requisição foi mockada para que o teste fosse executado de forma mais rápida.
