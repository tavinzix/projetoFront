TODO criar backup e restore

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome_completo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    dt_nasc date,
    cpf VARCHAR(14) UNIQUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status CHARACTER(1) DEFAULT '1' CHECK (status IN ('1', '2', '3')),
    img_user VARCHAR(250) DEFAULT 'avatar.jpg'
);

COMMENT ON COLUMN usuarios.status IS '1 = ativo, 2 = inativo, 3 = banido';

CREATE TABLE enderecos (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    tipo CHARACTER(1) DEFAULT 'Casa' CHECK (tipo IN ('Casa', 'Trabalho')),
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
    email VARCHAR(255),
    telefone VARCHAR(11),
    categoria VARCHAR(255),
    cep VARCHAR(9) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    imagem_logo VARCHAR(255),
    avaliacao_media NUMERIC(3,2) DEFAULT 0.00,
    status CHARACTER(1) DEFAULT '1' CHECK (status IN ('1', '2', '3')),
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE vendedores_entrega(
    id SERIAL PRIMARY KEY,
    vendedor_id INT NOT NULL,
    nome_transporte VARCHAR(255) NOT NULL,
    tipo_envio CHAR(1) CHECK (tipo_envio IN ('1', '2', '3')) NOT NULL,
    preco_base NUMERIC(10,2) DEFAULT 0.00,
    prazo_entrega INT, -- prazo em dias
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(id) ON DELETE CASCADE
);

COMMENT ON COLUMN vendedores_entrega.tipo_envio IS '1 = Normal, 2 = Expresso, 3 = Econômico';

CREATE TABLE administradores (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status CHARACTER(1) DEFAULT '1' CHECK (status IN ('1', '2'))
);

COMMENT ON COLUMN administradores.status IS '1 = ativo, 2 = inativo';

CREATE TABLE solicitacoes_vendedor (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    nome_loja VARCHAR(255) NOT NULL,
    cnpj VARCHAR(18) UNIQUE,
    email VARCHAR(255),
    telefone VARCHAR(11),
    cep VARCHAR(9) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    rua VARCHAR(255) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    categoria VARCHAR(255),
    descricao_loja TEXT,
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status CHAR(1) CHECK (status IN ('1', '2', '3')),
    motivo_rejeicao TEXT,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

COMMENT ON COLUMN solicitacoes_vendedor.status IS '1 = Pendente, 2 = Rejeitado, 3 = Aprovado' ;
COMMENT ON COLUMN solicitacoes_vendedor.motivo_rejeicao IS 'Motivo da rejeição, se aplicável';

CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255),
    url VARCHAR(255) UNIQUE NOT NULL,
    status CHAR(1) CHECK (status IN ('1', '2')),
);

COMMENT ON COLUMN categorias.status IS '1 = Ativo, 2 = Inativo';

CREATE TABLE produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    categoria_id INT NOT NULL,
    marca VARCHAR(255) NOT NULL,
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

CREATE TABLE carrinho (
    id SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE carrinho_itens (
    id SERIAL PRIMARY KEY,
    carrinho_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario NUMERIC(10,2) NOT NULL,
    FOREIGN KEY (carrinho_id) REFERENCES carrinho(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE pedidos (
    id SERIAL PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT '1' CHECK (status IN ('1', '2', '3', '4', '5', '6', '7')),  
    valor_total NUMERIC(10,2),
    endereco_entrega_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (endereco_entrega_id) REFERENCES enderecos(id)
);
COMMENT ON COLUMN pedidos.status IS '1 - aguardando pagamento, 2 - a caminho, 3 - aguardando envio,, 4 - enviado, 5 - entregues, 6 - cancelados, 7 - reembolsado';

CREATE TABLE pedido_itens (
    id SERIAL PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario NUMERIC(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE vendedores_ofertas (
    id SERIAL PRIMARY KEY,
    vendedor_id INT NOT NULL,
    produto_id INT NOT NULL,
    preco_oferta NUMERIC(10,2) NOT NULL,
    data_inicio TIMESTAMP NOT NULL,
    data_fim date NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

CREATE TABLE vendedor_imagens(
    id SERIAL PRIMARY KEY, 
    vendedor_id INT NOT NULL,
    imagem_url character varying(255) NOT NULL  DEFAULT 'semImagem.jpg',
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(id) ON DELETE CASCADE,
)

CREATE TABLE formas_pagamento(
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
	nome_titular varchar(30),
	nome_cartao varchar(30),
	numero_cartao varchar(20) UNIQUE,
    validade varchar(10),
    cvv char(3),
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
)