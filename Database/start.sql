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



/*insert into TABLE admin (nome, email, senha)
values
(teste, g321mendes@gmail.com, $2y$10$v9mAca1CELYzURTaKY9.SOEkyGEKQu9SiBpmcj3pYOZaMt07pqJ.m);
*/