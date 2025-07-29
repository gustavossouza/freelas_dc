# 🛒 Sistema de Vendas

Um sistema completo de gerenciamento de vendas desenvolvido em Laravel 12 com design moderno e responsivo.

## ✨ Funcionalidades

### 🔐 Autenticação
- ✅ Login e registro de usuários
- ✅ Recuperação de senha
- ✅ Perfil do usuário
- ✅ Alteração de senha
- ✅ Exclusão de conta

### 👥 Clientes
- ✅ Cadastro de clientes
- ✅ Listagem com busca e filtros
- ✅ Edição de dados
- ✅ Visualização detalhada
- ✅ Exclusão de clientes

### 🛍️ Vendas
- ✅ Criação de vendas
- ✅ Itens de venda
- ✅ Cálculo automático de valores
- ✅ Status de vendas
- ✅ Histórico de vendas

### 💳 Tipos de Pagamento
- ✅ Cadastro de tipos de pagamento
- ✅ Configuração de métodos
- ✅ Status ativo/inativo

### 📅 Parcelas
- ✅ Gerenciamento de parcelas
- ✅ Controle de vencimentos
- ✅ Marcação de pagamento
- ✅ Status de parcelas

### 📄 PDF
- ✅ Geração de relatórios
- ✅ Download de documentos
- ✅ Templates personalizáveis

## 🚀 Instalação

### Pré-requisitos
- PHP 8.2 ou superior
- Composer
- MySQL/PostgreSQL
- Node.js (opcional, para assets)

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone <url-do-repositorio>
cd freelas_dc
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure o ambiente**
```bash
cp .env.example .env
```

4. **Configure o banco de dados no arquivo .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=freelas_dc
DB_USERNAME=root
DB_PASSWORD=
```

5. **Execute a instalação automática**
```bash
php artisan system:install
```

### Instalação Manual (Alternativa)

Se preferir instalar manualmente:

```bash
# Gerar chave da aplicação
php artisan key:generate

# Executar migrações
php artisan migrate:fresh

# Executar seeders
php artisan db:seed

# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 🔑 Credenciais Padrão

Após a instalação, você pode acessar o sistema com:

**Administrador:**
- Email: `admin@admin.com`
- Senha: `password`

**Usuário Teste:**
- Email: `user@test.com`
- Senha: `password`

## 🌐 Acesso

Após a instalação, acesse:
```
http://localhost:8000
```

## 📱 Funcionalidades do Sistema

### Dashboard
- Visão geral das vendas
- Estatísticas em tempo real
- Ações rápidas
- Atividades recentes

### Gestão de Clientes
- Cadastro completo com endereço
- Busca e filtros avançados
- Histórico de compras
- Dados de contato

### Controle de Vendas
- Criação de vendas com múltiplos itens
- Cálculo automático de impostos e descontos
- Controle de status
- Histórico completo

### Sistema de Parcelas
- Criação automática de parcelas
- Controle de vencimentos
- Marcação de pagamento
- Relatórios de inadimplência

### Tipos de Pagamento
- Configuração flexível
- Múltiplos métodos
- Status ativo/inativo
- Relatórios por método

## 🎨 Design

- **Design Moderno**: Interface limpa e profissional
- **Responsivo**: Funciona em todos os dispositivos
- **Animações**: Transições suaves e efeitos visuais
- **Acessibilidade**: Navegação por teclado e leitores de tela
- **UX/UI**: Experiência do usuário otimizada

## 🛠️ Tecnologias

- **Backend**: Laravel 12
- **Frontend**: Bootstrap 5, jQuery
- **Banco de Dados**: MySQL/PostgreSQL
- **Autenticação**: Laravel Auth
- **Validação**: Form Requests
- **Design**: CSS3, Font Awesome

## 📁 Estrutura do Projeto

```
freelas_dc/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controladores
│   │   ├── Requests/        # Validações
│   │   └── Middleware/      # Middlewares
│   ├── Models/              # Modelos
│   └── Console/Commands/    # Comandos Artisan
├── database/
│   ├── migrations/          # Migrações
│   └── seeders/            # Seeders
├── resources/
│   └── views/              # Views Blade
│       ├── auth/           # Autenticação
│       ├── clients/        # Clientes
│       ├── sells/          # Vendas
│       ├── installments/   # Parcelas
│       └── layouts/        # Layouts
└── routes/
    └── web.php             # Rotas web
```

## 🔧 Comandos Úteis

```bash
# Instalar sistema
php artisan system:install

# Limpar cache
php artisan cache:clear

# Executar migrações
php artisan migrate

# Executar seeders
php artisan db:seed

# Listar rotas
php artisan route:list

# Servidor de desenvolvimento
php artisan serve
```

## 📊 Banco de Dados

O sistema inclui as seguintes tabelas:

- `users` - Usuários do sistema
- `clients` - Clientes
- `type_payments` - Tipos de pagamento
- `sells` - Vendas
- `sell_items` - Itens de venda
- `installments` - Parcelas

## 🔒 Segurança

- Autenticação segura
- Validação de dados
- Proteção CSRF
- Senhas criptografadas
- Middleware de autenticação

## 🚀 Deploy

Para fazer deploy em produção:

1. Configure o ambiente de produção
2. Execute as migrações
3. Configure o servidor web
4. Configure o cache e otimizações

## 📞 Suporte

Para suporte ou dúvidas:
- Abra uma issue no repositório
- Entre em contato com o desenvolvedor

## 📄 Licença

Este projeto está sob a licença MIT.

---

**Desenvolvido com ❤️ usando Laravel 12**
