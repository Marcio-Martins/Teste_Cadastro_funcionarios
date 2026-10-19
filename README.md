# Cadastro de Funcionários — PHP

Projeto de estudo para praticar páginas web em PHP para gerenciamento de registros de funcionários.

## Funcionalidades

- Cadastro e listagem de funcionários
- Busca por nome ou prontuário
- Edição e exclusão de registros
- Exportação de dados para CSV
- Paginação da listagem

## Tecnologias

- PHP com PDO
- MySQL
- HTML e CSS

## Estrutura

```text
Teste_Cadastro_funcionarios/
├── README.md
├── .gitignore
└── funcionarios/
    ├── cadastrar.php
    ├── cadastrar_form.php
    ├── config.php
    ├── editar.php
    ├── excluir.php
    ├── exportar_csv.php
    ├── index.php
    ├── listar.php
    └── styles.css
```

## Requisitos

- PHP com a extensão PDO MySQL habilitada
- MySQL ou MariaDB
- Banco de dados `cadastro_funcionarios` e tabela `funcionarios` criados localmente

## Configuração local

O arquivo `funcionarios/config.php` lê a configuração do banco de dados por variáveis de ambiente. Não armazene senhas no código nem no Git.

Configure estas variáveis no ambiente em que o PHP será executado:

- `DB_HOST`: host do banco (padrão: `127.0.0.1`)
- `DB_PORT`: porta do banco (padrão: `3307`)
- `DB_NAME`: nome do banco (padrão: `cadastro_funcionarios`)
- `DB_USER`: usuário do banco (padrão: `root`)
- `DB_PASS`: senha do usuário (padrão: vazia)

Ajuste host, porta, usuário e senha de acordo com seu ambiente. O usuário do banco deve ter apenas as permissões necessárias para esta aplicação. Se sua senha já esteve publicada no repositório, troque-a: remover a senha do arquivo atual não a remove do histórico de commits.

Depois de criar e configurar o banco e a tabela esperada pelo projeto, inicie o servidor PHP na pasta `funcionarios`:

```bash
php -S 127.0.0.1:8000
```

Acesse `http://127.0.0.1:8000` no navegador. A criação do banco e da tabela ainda precisa corresponder às colunas utilizadas pelos scripts.

## Segurança e limitações

- Consultas de busca e operações de escrita devem usar consultas preparadas.
- Valores exibidos em HTML devem ser escapados.
- A exclusão deve ocorrer por POST com token CSRF, não por link GET.
- Este projeto é educacional e ainda precisa de testes funcionais e de segurança antes de qualquer uso com dados reais.
- A exportação CSV deve ser revisada para prevenir fórmulas maliciosas em campos exportados.

## Próximas melhorias

- Adicionar script SQL versionado para criar o banco e a tabela.
- Adicionar testes automatizados e instruções para executá-los.
- Implementar autenticação e autorização antes de disponibilizar dados pessoais.
- Revisar a exportação CSV e os fluxos de cadastro.
- Registrar capturas de tela da aplicação em execução.

## Autor

Márcio Martins  
[LinkedIn](https://www.linkedin.com/in/marcio-martins-eufrazio/) · [Perfil GitHub](https://github.com/Marcio-Martins)
