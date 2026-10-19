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
├── .github/workflows/php-ci.yml
├── database/
│   └── schema.sql
├── funcionarios/
│   ├── cadastrar.php
│   ├── cadastrar_form.php
│   ├── config.php
│   ├── csv_utils.php
│   ├── editar.php
│   ├── excluir.php
│   ├── exportar_csv.php
│   ├── index.php
│   ├── listar.php
│   └── styles.css
├── tests/
│   └── test_csv.php
└── README.md
```

## Requisitos

- PHP 8.2 ou superior com a extensão PDO MySQL habilitada
- MySQL ou MariaDB

## Configuração local

1. Crie o banco e a tabela usando o script versionado na raiz do repositório:

   ```bash
   mysql -u root -p < database/schema.sql
   ```

   O script cria o banco `cadastro_funcionarios` e a tabela `funcionarios`. Execute-o em um ambiente local de desenvolvimento.

2. Configure as variáveis de ambiente usadas por `funcionarios/config.php`:

   - `DB_HOST`: host do banco (padrão: `127.0.0.1`)
   - `DB_PORT`: porta do banco (padrão: `3307`)
   - `DB_NAME`: nome do banco (padrão: `cadastro_funcionarios`)
   - `DB_USER`: usuário do banco (padrão: `root`)
   - `DB_PASS`: senha do usuário (padrão: vazia)

   Ajuste host, porta, usuário e senha ao seu ambiente. O usuário do banco deve ter apenas as permissões necessárias para esta aplicação. Se uma senha já esteve publicada no repositório, troque-a: removê-la do arquivo atual não a remove do histórico de commits.

3. Inicie o servidor PHP a partir da pasta `funcionarios`:

   ```bash
   php -S 127.0.0.1:8000
   ```

4. Acesse [http://127.0.0.1:8000](http://127.0.0.1:8000) no navegador.

## Testes automatizados

O workflow do GitHub Actions verifica a sintaxe de todos os arquivos PHP e executa os testes unitários da proteção de fórmulas CSV em cada push ou pull request para `main`.

Para executar o teste CSV localmente:

```bash
php tests/test_csv.php
```

## Segurança e limitações

- Consultas devem usar instruções preparadas e valores exibidos em HTML devem ser escapados.
- A exclusão ocorre por POST com token CSRF, não por link GET.
- O cadastro e a edição validam os campos no servidor e exigem token CSRF.
- A exportação CSV neutraliza valores que poderiam ser interpretados como fórmulas por planilhas.
- Este projeto é educacional e ainda precisa de testes de integração com MySQL e revisão de segurança antes de qualquer uso com dados reais.
- Ainda não há autenticação nem autorização. Não publique esta aplicação com dados pessoais acessíveis publicamente.

## Próximas melhorias

- Adicionar testes de integração para cadastro, edição, listagem e exclusão com banco de dados de teste.
- Implementar autenticação e autorização.
- Registrar capturas de tela da aplicação em execução.

## Autor

Márcio Martins  
[LinkedIn](https://www.linkedin.com/in/marcio-martins-eufrazio/) · [Perfil GitHub](https://github.com/Marcio-Martins)
