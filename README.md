# Exercício: Sistema de Gerenciamento de Livros Pessoais

## Objetivo
Desenvolver um sistema web onde usuários autenticados possam catalogar seus livros lidos, atribuindo avaliações e categorizando-os.

## Requisitos Funcionais

### 1. Autenticação de Usuário
- Deve existir uma tela de login para nome de usuário e senha.
- Opção de "Registrar" novos usuários (nome de usuário único, senha com confirmação, e-mail).
- Autenticação baseada em sessões, garantindo acesso apenas a usuários logados.
- Funcionalidade de "Logout".

### 2. Gerenciamento de Livros
- Após o login, o usuário será redirecionado para uma página onde poderá visualizar a sua coleção de livros.
- Cada livro deve ter os seguintes atributos:
  - **Título**: (Obrigatório)
  - **Autor**: (Obrigatório)
  - **Gênero**: (Obrigatório, com opções predefinidas como 'Ficção', 'Fantasia', 'Aventura', 'Técnico', 'Biografia', etc.)
  - **Ano de Publicação**: (Opcional)
  - **Número de Páginas**: (Opcional)
  - **Avaliação**: (De 1 a 5 estrelas ou equivalente numérico, obrigatório ao adicionar/editar).
  - **Data de Leitura**: (Opcional, data em que o livro foi finalizado).
  - **Sinopse/Notas Pessoais**: (Opcional, área de texto livre).
  - **Usuário**: O livro deve estar associado ao usuário que o catalogou.
- **Adicionar Livro**: O usuário deve poder adicionar novos livros à sua coleção.
- **Visualizar Coleção**: Os livros devem ser listados, com opções de ordenação (ex: por título, autor, avaliação) e filtragem (ex: por gênero, avaliação mínima).
- **Editar Livro**: O usuário deve poder editar os detalhes de um livro existente.
- **Excluir Livro**: O usuário deve poder remover um livro da sua coleção.

## Requisitos Não Funcionais

### 1. Segurança
- Senhas armazenadas de forma segura (hash).
- Proteção contra fixação e sequestro de sessão.
- Validação de entrada em todos os formulários para prevenir injeção de SQL e XSS.

### 2. Tecnologia
- **Frontend**: HTML e CSS para estrutura e estilização.
- **Backend**: PHP 8+ com Orientação a Objetos (POO).
- **Banco de Dados**: Utilização de PDO para todas as interações. Escolha um banco de dados relacional (MySQL, PostgreSQL, SQLite).
- **Gerenciamento de Sessões**: Funções nativas de sessão do PHP.

## Regras de Negócio
- Um usuário só pode visualizar, editar ou excluir os livros que ele mesmo catalogou.
- O nome de usuário deve ser único no registro.
- Ao adicionar ou editar um livro, o **Título** e o **Autor** são campos obrigatórios.
- A **Avaliação** de um livro deve estar entre 1 e 5.
- A **Data de Leitura**, se informada, não pode ser uma data futura.
- O **Ano de Publicação**, se informado, deve ser um ano válido (não pode ser no futuro).
- O nome de usuário deve ter no mínimo 3 caracteres e no máximo 50.
- A senha deve ter no mínimo 8 caracteres, contendo pelo menos uma letra maiúscula, uma letra minúscula e um número.

## Dicas para a Abstração
- Pense em como as classes **User** e **Book** se relacionam no banco de dados (relação 1:N).
- Como você vai encapsular as operações de banco de dados em suas classes **DAO**?
- Qual a melhor forma de validar os dados de entrada para cada atributo de um livro?
- Como você pode reutilizar código para a renderização de formulários ou tabelas?
