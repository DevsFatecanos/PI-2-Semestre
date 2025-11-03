CREATE DATABASE IF NOT EXISTS banco;
USE banco;

CREATE TABLE cliente (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    telefone VARCHAR(20),
    documento VARCHAR(20),
    email VARCHAR(50),
    senha VARCHAR(255),
    role ENUM('cliente', 'admin') DEFAULT 'cliente'
);

CREATE TABLE veiculo (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100), 
    marca VARCHAR(50),
    placa VARCHAR(20) NOT NULL UNIQUE,
    capacidade INT,
    ano YEAR,
    status ENUM('disponivel', 'em uso', 'manutencao') DEFAULT 'disponivel'
);

CREATE TABLE pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    veiculo_id INT DEFAULT NULL,
    data_pedido DATE NOT NULL,
    entregue_em DATETIME DEFAULT NULL, 
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES cliente(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (veiculo_id) REFERENCES veiculo(id) 
);


CREATE TABLE pedido_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    status ENUM('pendente', 'em transporte', 'entregue', 'cancelado'),
    data_alteracao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedido(id) ON DELETE CASCADE
);


CREATE TABLE endereco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    logradouro VARCHAR(100),
    numero VARCHAR(10),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    cep VARCHAR(10),
    FOREIGN KEY (cliente_id) REFERENCES cliente(id) ON DELETE CASCADE
);

CREATE TABLE pagamento (
    id SERIAL PRIMARY KEY,
    pedido_id INT REFERENCES pedido(id) ON DELETE CASCADE,
    valor NUMERIC(10,2) NOT NULL,
    metodo VARCHAR(30) CHECK (metodo IN ('pix', 'boleto', 'cartao', 'transferencia')),
    status VARCHAR(20) DEFAULT 'pendente',
    data_pagamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE avaliacao (
    id SERIAL PRIMARY KEY,
    pedido_id INT REFERENCES pedido(id) ON DELETE CASCADE,
    cliente_id INT REFERENCES cliente(id) ON DELETE CASCADE,
    nota INT CHECK (nota BETWEEN 1 AND 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE usuario_log (
    id SERIAL PRIMARY KEY,
    usuario_id INT REFERENCES cliente(id) ON DELETE CASCADE,
    acao VARCHAR(255),
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45)
);

CREATE TABLE motorista (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    cnh VARCHAR(20) UNIQUE,
    status VARCHAR(20) DEFAULT 'ativo',
    veiculo_id INT REFERENCES veiculo(id) ON DELETE SET NULL
);

CREATE TABLE pagamento (
    id SERIAL PRIMARY KEY,
    pedido_id INT REFERENCES pedido(id) ON DELETE CASCADE,
    valor NUMERIC(10,2) NOT NULL,
    metodo VARCHAR(30) CHECK (metodo IN ('pix', 'boleto', 'cartao', 'transferencia')),
    status VARCHAR(20) DEFAULT 'pendente',
    data_pagamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
