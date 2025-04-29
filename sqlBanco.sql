CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    dt_nasc date,
    cpf VARCHAR(14) UNIQUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status CHARACTER(1) DEFAULT "1",
    img_user VARCHAR(250) DEFAULT "avatar.jpg"
);

CREATE TABLE enderecos (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    cep VARCHAR(9) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE vendedores (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    nome_loja VARCHAR(255) NOT NULL,
    cnpj VARCHAR(18) UNIQUE,
    descricao_loja TEXT,
    imagem_logo VARCHAR(255),
    avaliacao_media NUMERIC(3,2) DEFAULT 0.00,
    quantidade_vendas INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE administradores (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status CHARACTER(1) DEFAULT "1"
);

CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,                 
    nome VARCHAR(255) NOT NULL,             
    descricao TEXT,                        
    imagem VARCHAR(255),                    
    url VARCHAR(255) UNIQUE NOT NULL,      
);

CREATE TABLE produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    categoria_id INT NOT NULL,
    atributos JSONB,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE vendedores_produtos (
    id SERIAL PRIMARY KEY,
    vendedor_id INT NOT NULL,
    produto_id INT NOT NULL,
    preco NUMERIC(10,2) NOT NULL,
    estoque INT NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    UNIQUE (vendedor_id, produto_id)
);

CREATE TABLE produto_imagens (
    id SERIAL PRIMARY KEY,
    produto_id INT NOT NULL,
    imagem_url VARCHAR(255) NOT NULL,
    ordem INT DEFAULT 1,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE avaliacoes_produtos (
    id SERIAL PRIMARY KEY,
    produto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    nota INT CHECK (nota >= 1 AND nota <= 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
