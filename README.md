# Cadastro de Funcionários — PHP

Projeto de estudo para praticar páginas web em PHP para gerenciamento de registros de funcionários.

## Funcionalidades

- Login com senha armazenada por hash e controle de sessão
- Encerramento de sessão via POST com token CSRF
- Cadastro e listagem de funcionários
- Busca por nome ou prontuário
- Edição e exclusão de registros
- Exportação de dados para CSV
- Paginação da listagem

## Tecnologias

- PHP 8.2+
- PDO e MySQL
- HTML e CSS
- GitHub Actions para integração contínua

## Estrutura

```text
Teste_Cadastro_funcionarios/
├── .github/workflows/php-ci.yml
├── database/
│   └── schema.sql
├── funcionarios/
│   ├── auth.php
│   ├── cadastrar.php
│   ├── criar_usuario.php
│   ├── cadastrar_form.php
│   ├── config.php
│   ├── csv_utils.php
│   ├── editar.php
│   ├── excluir.php
│   ├── exportar_csv.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── listar.php
│   └── styles.css
├── tests/
│   ├── test_csv.php
│   └── test_mysql_integration.php
└── README.md
```

## Configuração local

1. Requisitos: PHP 8.2 ou superior, extensão PDO MySQL e MySQL ou MariaDB.
2. Crie o banco e a tabela usando o script versionado na raiz do repositório:

   ```bash
   mysql -u root -p < database/schema.sql
   ```

   O script cria o banco `cadastro_funcionarios` e a tabela `funcionarios`. Execute-o em um ambiente local de desenvolvimento.

3. Configure as variáveis de ambiente usadas por `funcionarios/config.php`:

   - `DB_HOST`: host do banco (padrão: `127.0.0.1`)
   - `DB_PORT`: porta do banco (padrão: `3307`)
   - `DB_NAME`: nome do banco (padrão: `cadastro_funcionarios`)
   - `DB_USER`: usuário do banco (padrão: `root`)
   - `DB_PASS`: senha do usuário (padrão: vazia)

   Ajuste host, porta, usuário e senha ao seu ambiente. Use uma conta de banco com apenas as permissões necessárias. Se uma senha já esteve publicada no repositório, troque-a: removê-la do arquivo atual não a remove do histórico de commits.

4. Inicie o servidor PHP a partir da pasta `funcionarios`:

   ```bash
   php -S 127.0.0.1:8000
   ```

5. Crie o primeiro usuário administrador por linha de comando. Defina as variáveis de ambiente `ADMIN_EMAIL` e `ADMIN_PASSWORD` (a senha deve ter pelo menos 12 caracteres) e execute `php criar_usuario.php` a partir da pasta `funcionarios`. Não coloque a senha diretamente no código nem a publique no Git.

   No PowerShell, por exemplo, defina as variáveis apenas na sessão atual antes de executar o script:
   ```powershell
   $env:ADMIN_EMAIL = "seu-email@example.com"
   $env:ADMIN_PASSWORD = Read-Host "Digite uma senha forte"
   php criar_usuario.php
   Remove-Item Env:ADMIN_EMAIL
   Remove-Item Env:ADMIN_PASSWORD
   ```

6. Acesse [http://127.0.0.1:8000](http://127.0.0.1:8000) no navegador. Faça login com o e-mail e a senha definidos acima.

## Testes automatizados

O GitHub Actions executa em cada push ou pull request para `main`:

- Verificação de sintaxe de todos os arquivos PHP
- Testes unitários da proteção contra fórmulas na exportação CSV
- Testes de integração com MySQL para operações `INSERT`, `SELECT`, `UPDATE` e `DELETE`

Os testes de integração usam um serviço MySQL temporário no pipeline e fazem rollback dos dados de teste. Para executar o teste CSV localmente:

```bash
php tests/test_csv.php
```

O teste MySQL também pode ser executado localmente com o banco preparado e as variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS` configuradas.

## Segurança e limitações

- As consultas ao banco usam instruções preparadas e os valores exibidos em HTML são escapados.
- A exclusão ocorre por POST com token CSRF, não por link GET.
- O cadastro e a edição validam os campos no servidor e exigem token CSRF.
- A exportação CSV neutraliza valores que poderiam ser interpretados como fórmulas por planilhas.
- Este projeto é educacional e precisa de revisão de segurança antes de qualquer uso com dados reais.
- As páginas de cadastro, edição, listagem, exclusão e exportação exigem uma sessão autenticada.
- O login usa `password_hash`/`password_verify`, regenera o ID da sessão após autenticação e usa cookies HttpOnly e SameSite.
- Este é um projeto educacional: ainda faltam limites de tentativas de login e testes HTTP ponta a ponta. Não publique a aplicação com dados pessoais reais sem revisão adicional de segurança.
- Os testes de integração validam operações SQL com MySQL, mas não substituem testes HTTP completos de todos os fluxos da aplicação.

## Próximas melhorias

- Adicionar limite de tentativas de login e testes HTTP ponta a ponta.
- Adicionar testes HTTP para os fluxos de cadastro, edição, listagem e exclusão.
- Registrar capturas de tela da aplicação em execução.

## Autor

Márcio Martins  
[LinkedIn](https://www.linkedin.com/in/marcio-martins-eufrazio/) · [Perfil GitHub](https://github.com/Marcio-Martins)
