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




/*insert into TABLE admin (nome, email, senha)
values
(teste, g321mendes@gmail.com, $2y$10$v9mAca1CELYzURTaKY9.SOEkyGEKQu9SiBpmcj3pYOZaMt07pqJ.m);
*/