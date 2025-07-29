# 🛒 Sistema de Vendas - Laravel 12

Um sistema completo de gerenciamento de vendas desenvolvido em **Laravel 12** seguindo arquitetura **MVC**, com interface moderna e responsiva usando **Bootstrap 5** e **jQuery**.

## 🚀 Tecnologias Utilizadas

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Bootstrap 5, jQuery, Blade Templates
- **Banco de Dados:** PostgreSQL
- **Containerização:** Docker & Docker Compose
- **Autenticação:** Laravel Auth
- **Validação:** Form Requests
- **Exportação:** jsPDF, SheetJS (XLSX)

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🐳 Instalação com Docker

### 1. Clone o Repositório
```bash
git clone <url-do-repositorio>
cd freelas_dc
```

### 2. Configure o Ambiente
```bash
# Copie o arquivo de ambiente
cp .env.example .env
```

### 3. Suba os Containers
```bash
# Construa e inicie os containers
docker-compose up -d --build
```

### 4. Instale as Dependências
```bash
# Acesse o container da aplicação
docker-compose exec freelas_dev bash

# Instale as dependências PHP
composer install
```

### 5. Configure a Aplicação
```bash
# Gere a chave da aplicação
php artisan key:generate

# Execute as migrações
php artisan migrate

# Limpe os caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 6. Acesse a Aplicação
```
http://localhost:8080
```

**Models Disponíveis:**
- `User` - Usuários do sistema
- `Client` - Clientes
- `Product` - Produtos
- `Sell` - Vendas
- `SellItem` - Itens de venda
- `Installment` - Parcelas

## ✨ Features Principais

### 🔐 Sistema de Autenticação
- ✅ Login seguro com Laravel Auth
- ✅ Proteção de rotas com middleware
- ✅ Validação de formulários
- ✅ Mensagens de erro personalizadas

### 🎨 Interface Moderna
- ✅ **Bootstrap 5** - Framework CSS moderno
- ✅ **jQuery** - Interações dinâmicas
- ✅ **SweetAlert2** - Alertas e confirmações
- ✅ **Select2** - Dropdowns avançados
- ✅ **Font Awesome** - Ícones profissionais
- ✅ **Design Responsivo** - Mobile-first

## 🐳 Docker Compose

### Estrutura dos Containers
```yaml
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
  
  db:
    image: postgres:15
    environment:
      POSTGRES_DB: freelas_dc
      POSTGRES_USER: postgres
      POSTGRES_PASSWORD: password
    ports:
      - "5432:5432"
    volumes:
      - postgres_data:/var/lib/postgresql/data
```

### Comandos Docker Úteis
```bash
# Subir containers
docker-compose up -d

# Parar containers
docker-compose down

# Acessar container
docker-compose exec app bash

# Reconstruir containers
docker-compose up -d --build

# Limpar volumes
docker-compose down -v
```

## 📊 Banco de Dados

### Tabelas Principais
- `users` - Usuários do sistema
- `clients` - Clientes
- `products` - Produtos
- `sells` - Vendas
- `sell_items` - Itens de venda
- `installments` - Parcelas

## 🔒 Segurança

- ✅ **CSRF Protection** - Tokens em formulários
- ✅ **SQL Injection** - Eloquent ORM
- ✅ **XSS Protection** - Blade escaping
- ✅ **Authentication** - Laravel Auth
- ✅ **Authorization** - Middleware de autenticação
- ✅ **Input Validation** - Form Requests

## 📞 Suporte

Para suporte ou dúvidas:
- 📧 Abra uma issue no repositório
- 📧 Entre em contato com o desenvolvedor
gustavohsantos2009@hotmail.com