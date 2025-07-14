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
    avaliacao_media NUMERIC(3,2) DEFAULT 0.00,
    status CHARACTER(1) DEFAULT '1' CHECK (status IN ('1', '2', '3')),
    img_vendedor character varying(255) NOT NULL  DEFAULT 'semImagem.jpg',
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
    status VARCHAR(20) DEFAULT '1' CHECK (status IN ('1', '2', '3', '4', '5', '6', '7', '8', '9')),  
    valor_total NUMERIC(10,2),
    endereco_entrega_id INT NOT NULL,
    forma_pagamento_id INT NOT NULL, 
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (endereco_entrega_id) REFERENCES enderecos(id),
    FOREIGN KEY (forma_pagamento_id) REFERENCES formas_pagamento(id)
);
COMMENT ON COLUMN pedidos.status IS '1 - aguardando pagamento, 2 - a caminho, 3 - aguardando envio,, 4 - enviado, 5 - entregues, 6 - cancelados, 7 - reembolsado, 8 - estornado, 9 - devolvido';

CREATE TABLE pedidos_itens (
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



-- Ferramentas Manuais
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Ferramentas Manuais', 'Chaves, martelos, serras e outros itens essenciais para construções e reformas.', 'ferramenta_manual.jpg', 'ferramentas-manuais', '1');

-- Ferramentas Elétricas
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Ferramentas Elétricas', 'Parafusadeiras, furadeiras, lixadeiras e mais para facilitar o trabalho.', 'ferramenta_eletrica.jpg', 'ferramentas-eletricas', '1');

-- Materiais de Construção
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Materiais de Construção', 'Cimento, areia, tijolos e materiais básicos para obra.', 'materiais_constucao.jpg', 'materiais-de-construcao', '1');

-- Impermeabilizantes
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Impermeabilizantes', 'Membranas e tintas para evitar infiltrações em obras.', 'impermeabilizante.jpg', 'impermeabilizantes', '1');

-- Revestimentos
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Revestimentos', 'Pisos, azulejos e acabamentos para paredes e chão.', 'revestimentos.jpg', 'revestimentos', '1');

-- Sistemas de Isolamento
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Sistemas de Isolamento', 'Materiais para isolamento térmico e acústico.', 'isolamento.jpg', 'sistemas-de-isolamento', '1');

-- Fios e Cabos Elétricos
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Fios e Cabos Elétricos', 'Cabos de energia e fios para instalações elétricas.', 'fios.jpg', 'fios-e-cabos-eletricos', '1');

-- Interruptores e Tomadas
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Interruptores e Tomadas', 'Peças essenciais para controle e distribuição de energia.', 'interruptor.jpg', 'interruptores-e-tomadas', '1');

-- Lâmpadas e Luminárias
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Lâmpadas e Luminárias', 'Lâmpadas LED, fluorescentes e luminárias para todos os ambientes.', 'lampada.jpg', 'lampadas-e-luminarias', '1');

-- Disjuntores e Proteções
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Disjuntores e Proteções', 'Disjuntores, fusíveis e dispositivos de proteção elétrica.', 'disjuntores.jpg', 'disjuntores-e-protecoes', '1');

-- Extensões e Adaptadores
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Extensões e Adaptadores', 'Extensões elétricas, adaptadores e réguas de energia.', 'extensao.jpg', 'extensoes-e-adaptadores', '1');

-- Instalações de Energia Solar
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Instalações de Energia Solar', 'Placas solares, inversores e baterias para energia limpa.', 'painel-solar.jpg', 'instalacoes-de-energia-solar', '1');

-- Tubos e Conexões
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Tubos e Conexões', 'Tubulações de PVC, cobre e conexões hidráulicas.', 'tuboPvc.jpg', 'tubos-e-conexoes', '1');

-- Acessórios Hidráulicos
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Acessórios Hidráulicos', 'Registros, válvulas, filtros e peças complementares.', 'hidraulico.jpg', 'acessorios-hidraulicos', '1');

-- Bombas d\'Água
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Bombas d\Água', 'Bombas submersíveis e de pressão para diversas aplicações.', 'bomba-agua.jpg', 'bombas-dagua', '1');

-- Caixas D\'Água e Reservatórios
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Caixas d\Água e Reservatórios', 'Soluções para armazenamento de água em obras.', 'caixa-agua.jpg', 'caixas-dagua-e-reservatorios', '1');

-- Sistemas de Irrigação
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Sistemas de Irrigação', 'Aspersores, gotejadores e mangueiras para irrigação.', 'irrigador.jpg', 'sistemas-de-irrigacao', '1');

-- Tijolos e Pedras Ornamentais
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Tijolos e Pedras Ornamentais', 'Pedras naturais, mármores e tijolinhos decorativos.', 'ornamental.jpg', 'tijolos-e-pedras-ornamentais', '1');

-- Rodapés e Forros
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Rodapés e Forros', 'Detalhes de acabamento para paredes e tetos.', 'rodape.jpg', 'rodapes-e-forros', '1');

-- Móveis para Construção e Decoração
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Móveis para Construção e Decoração', 'Móveis planejados e decorativos para áreas construídas.', 'armario-planejado.jpg', 'moveis-para-construcao-e-decoracao', '1');

-- Ferramentas de Jardinagem
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Ferramentas de Jardinagem', 'Tesouras, pás, rastelos e itens para cuidar do jardim.', 'jardinagem.jpg', 'ferramentas-de-jardinagem', '1');

-- Cercas e Alambrados
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Cercas e Alambrados', 'Cercas de madeira, metálicas e telas.', 'cerca.jpg', 'cercas-e-alambrados', '1');

-- Sinalização de Segurança
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Segurança', 'Placas, cones e barreiras para obras e áreas de risco.', 'sinalizacao.jpg', 'sinalizacao-de-seguranca', '1');

-- Câmeras de Segurança
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Câmeras de Segurança', 'Câmeras IP, sistemas de CFTV e kits de monitoramento.', 'camera-seguranca.jpg', 'cameras-de-seguranca', '1');

-- Acessórios para Banheiro
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Acessórios para Banheiro', 'Espelhos, duchas, box e metais sanitários.', 'banheiro.jpg', 'acessorios-para-banheiro', '1');

-- Lixas e Escovas
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Lixas e Escovas', 'Ferramentas para preparação e acabamento de superfícies.', 'lixas.jpg', 'lixas-e-escovas', '1');

-- Rolos e Pincéis
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Rolos e Pincéis', 'Rolos de pintura, pincéis e trinchas para aplicação de tintas.', 'pintura.jpg', 'rolos-e-pinceis', '1');

-- Máquinas de Corte
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Máquinas de Corte', 'Serras elétricas, cortadoras de concreto e similares.', 'ferramenta-corte.jpg', 'maquinas-de-corte', '1');

-- Geradores e Compressores
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Geradores e Compressores', 'Equipamentos de energia e pressão para uso em obras.', 'gerador.jpg', 'geradores-e-compressores', '1');

-- Pinos e Parafusos
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Pinos e Parafusos', 'Parafusos, porcas, pregos e fixadores diversos.', 'pregos.jpg', 'pinos-e-parafusos', '1');

-- Portas
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
('Portas', 'Portas de madeira, alumínio e aço.', 'portas.jpg', 'portas', '1');

-- Janelas
INSERT INTO categorias (nome, descricao, imagem, url, status) VALUES 
(' Janelas', 'Janelas de madeira, alumínio e aço.', 'janelas.jpg', 'janelas', '1');