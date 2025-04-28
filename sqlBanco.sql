CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,                 
    nome VARCHAR(255) NOT NULL,             
    descricao TEXT,                        
    imagem VARCHAR(255),                    
    url VARCHAR(255) UNIQUE NOT NULL,      
);
