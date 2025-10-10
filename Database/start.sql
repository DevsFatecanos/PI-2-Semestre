create database IF not exists banco;
use banco;
CREATE TABLE cliente (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    telefone varchar (20),
    documento varchar(20),
    email VARCHAR(30),
    senha varchar(255)
);
CREATE TABLE admin (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(30),
    senha varchar(255)
);



/*
INSERT INTO cliente (nome, email, telefone, documento, senha) VALUES
("Ana Souza", "ana.souza@gmail.com", "11987654321", "12345678900", "senha123"),
("Carlos Silva", "carlos.silva@yahoo.com", "11912345678", "98765432100", "carl123"),
("Fernanda Lima", "fernanda.lima@outlook.com", "21987651234", "45678912300", "fern@2025"),
("João Mendes", "joao.mendes@gmail.com", "31965473210", "32165498700", "joao@321"),
("Mariana Costa", "mariana.costa@hotmail.com", "41987650011", "74125896300", "mari@456"),
("Pedro Alves", "pedro.alves@gmail.com", "51911112222", "85236974100", "pedro_789"),
("Beatriz Rocha", "beatriz.rocha@icloud.com", "61999998888", "96385274100", "bia@000"),
("Rafael Torres", "rafael.torres@gmail.com", "71933334444", "15975348600", "rafa@abc"),
("Juliana Martins", "juliana.martins@yahoo.com", "81955556666", "75395145600", "ju_321"),
("Lucas Pereira", "lucas.pereira@gmail.com", "91977778888", "45612378900", "lucas@777");


*/