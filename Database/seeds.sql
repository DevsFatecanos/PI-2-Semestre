-- ===========================
-- Clientes (20)
-- ===========================
INSERT INTO `cliente` (`id`, `nome`, `telefone`, `documento`, `email`, `senha`, `role`) VALUES
(1, 'Guilherme Mendes', '11953526954', '12345678901', 'g7000mendes@gmail.com', '$2y$10$AYBi.pgRdcPLXeMqUK40Xe/IksbX6BOPBThFO6t0UMvbgUAqEByd6', 'cliente'),
(2, 'Maria Silva', '11987654321', '98765432100', 'maria.silva@gmail.com', '$2y$10$ExemploHash2xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(3, 'João Pereira', '11988887777', '11122233355', 'joao.pereira@gmail.com', '$2y$10$ExemploHash3xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(4, 'Ana Costa', '11999998888', '22233344466', 'ana.costa@gmail.com', '$2y$10$ExemploHash4xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(5, 'Carlos Lima', '11977776666', '33344455577', 'carlos.lima@gmail.com', '$2y$10$ExemploHash5xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(6, 'Fernanda Souza', '11966665555', '44455566688', 'fernanda.souza@gmail.com', '$2y$10$ExemploHash6xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(7, 'Lucas Rocha', '11955554444', '55566677799', 'lucas.rocha@gmail.com', '$2y$10$ExemploHash7xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(8, 'Patrícia Martins', '11944443333', '66677788800', 'patricia.martins@gmail.com', '$2y$10$ExemploHash8xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(9, 'Rafael Almeida', '11933332222', '77788899911', 'rafael.almeida@gmail.com', '$2y$10$ExemploHash9xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(10, 'Juliana Fernandes', '11922221111', '88899900022', 'juliana.fernandes@gmail.com', '$2y$10$ExemploHash10xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(11, 'Admin Teste', '11900001111', '11122233344', 'admin@ex.com', '$2y$10$ExemploHash11xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'admin'),
(12, 'Marcos Vinicius', '11911223344', '99988877766', 'marcos.vinicius@gmail.com', '$2y$10$ExemploHash12xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(13, 'Beatriz Silva', '11955667788', '11133355577', 'beatriz.silva@gmail.com', '$2y$10$ExemploHash13xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(14, 'Felipe Costa', '11966778899', '22244466688', 'felipe.costa@gmail.com', '$2y$10$ExemploHash14xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(15, 'Carla Oliveira', '11977889900', '33355577799', 'carla.oliveira@gmail.com', '$2y$10$ExemploHash15xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(16, 'Tiago Gomes', '11988990011', '44466688800', 'tiago.gomes@gmail.com', '$2y$10$ExemploHash16xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(17, 'Larissa Nunes', '11999001122', '55577799911', 'larissa.nunes@gmail.com', '$2y$10$ExemploHash17xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(18, 'Eduardo Santos', '11910111213', '66688800022', 'eduardo.santos@gmail.com', '$2y$10$ExemploHash18xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(19, 'Julio Andrade', '11912131415', '77799911133', 'julio.andrade@gmail.com', '$2y$10$ExemploHash19xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(20, 'Camila Ribeiro', '11913141516', '88800022244', 'camila.ribeiro@gmail.com', '$2y$10$ExemploHash20xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'cliente'),
(21, 'Rodrigo moura', '1193526954', '12312312345', 'ghas@gmail.com', '$2y$10$zDcs4kWas2zZYNe8wz61W.8/WnNlKM7vxutF94vmViAxloPATJDLm', 'cliente'),
(22, 'Rodrigo moura', '1193526954', '12312312345', '123456@gmail.com', '$2y$10$GHcXksv7KfscwTFaaMp8lOO/ZLql6qXOSm60oieBG2BUxo7sS/nu2', 'cliente'),
(23, 'Rodrigo moura', '1193526954', '12312312345', '12346@gmail.com', '$2y$10$AYBi.pgRdcPLXeMqUK40Xe/IksbX6BOPBThFO6t0UMvbgUAqEByd6', 'cliente'),
(24, 'guilherme', '1193526954', '12312312345', '1teste@gmail.com', '$2y$10$ytutvPBnBbrdBD1a0l9sUuWAzRgQZH.dE3ZFbXRWPgUPNg7WfP0u2', 'cliente'),
(25, 'admin', '1193526954', '12312312345', 'admin@gmail.com', '$2y$10$pA3szmDbrscXjGxKPxWW6uep80g2yBq1w26z/Pyke6.6ERRbJ/PdG', 'admin');/*adminteste*/
-- ===========================
-- Veículos (5)
-- ===========================
INSERT INTO veiculo (nome, marca, placa, capacidade, ano, status) VALUES
('Caminhão Azul', 'Volvo', 'ABC-1234', 5000, 2020, 'disponivel'),
('Van Amarela', 'Fiat', 'DEF-5678', 1500, 2022, 'em uso'),
('Carreta Vermelha', 'Scania', 'GHI-9012', 8000, 2018, 'manutencao'),
('Furgão Verde', 'Mercedes', 'JKL-3456', 2000, 2021, 'disponivel'),
('Caminhão Branco', 'Volvo', 'MNO-7890', 6000, 2019, 'em uso');

-- ===========================
-- Pedidos (20)
-- ===========================
INSERT INTO pedido (cliente_id, veiculo_id, data_pedido, entregue_em) VALUES
(1, 1, '2025-10-01', '2025-10-03 14:00:00'),
(2, 2, '2025-10-02', NULL),
(3, 3, '2025-10-02', NULL),
(4, 4, '2025-10-03', '2025-10-05 16:30:00'),
(5, 1, '2025-10-04', NULL),
(6, 5, '2025-10-04', NULL),
(7, 2, '2025-10-05', '2025-10-06 12:15:00'),
(8, 3, '2025-10-05', NULL),
(9, 4, '2025-10-06', NULL),
(10, 5, '2025-10-06', '2025-10-08 10:00:00'),
(11, 1, '2025-10-07', NULL),
(12, 2, '2025-10-07', NULL),
(13, 3, '2025-10-08', NULL),
(14, 4, '2025-10-08', NULL),
(15, 5, '2025-10-09', '2025-10-10 17:30:00'),
(16, 1, '2025-10-09', NULL),
(17, 2, '2025-10-10', NULL),
(18, 3, '2025-10-10', NULL),
(19, 4, '2025-10-11', NULL),
(20, 5, '2025-10-11', NULL);

-- ===========================
-- Pedido Status (para cada pedido)
-- ===========================
INSERT INTO pedido_status (pedido_id, status, data_alteracao) VALUES
(1, 'pendente', '2025-10-01 08:00:00'),
(1, 'entregue', '2025-10-03 14:00:00'),
(2, 'pendente', '2025-10-02 09:00:00'),
(3, 'pendente', '2025-10-02 10:00:00'),
(4, 'pendente', '2025-10-03 07:30:00'),
(4, 'em transporte', '2025-10-04 09:00:00'),
(4, 'entregue', '2025-10-05 16:30:00'),
(5, 'pendente', '2025-10-04 08:45:00'),
(6, 'pendente', '2025-10-04 09:15:00'),
(7, 'pendente', '2025-10-05 08:00:00'),
(7, 'entregue', '2025-10-06 12:15:00'),
(8, 'pendente', '2025-10-05 09:30:00'),
(9, 'pendente', '2025-10-06 10:00:00'),
(10, 'pendente', '2025-10-06 08:00:00'),
(10, 'entregue', '2025-10-08 10:00:00'),
(11, 'pendente', '2025-10-07 08:00:00'),
(12, 'pendente', '2025-10-07 09:00:00'),
(13, 'pendente', '2025-10-08 10:00:00'),
(14, 'pendente', '2025-10-08 11:00:00'),
(15, 'pendente', '2025-10-09 08:00:00'),
(15, 'entregue', '2025-10-10 17:30:00'),
(16, 'pendente', '2025-10-09 09:00:00'),
(17, 'pendente', '2025-10-10 08:00:00'),
(18, 'pendente', '2025-10-10 09:30:00'),
(19, 'pendente', '2025-10-11 07:45:00'),
(20, 'pendente', '2025-10-11 08:15:00');

-- ===========================
-- Endereços (20)
-- ===========================
INSERT INTO endereco (cliente_id, logradouro, numero, bairro, cidade, cep) VALUES
(1, 'Rua das Flores', '123', 'Vila Jacuí', 'São Paulo', '08060-160'),
(2, 'Avenida Paulista', '1000', 'Bela Vista', 'São Paulo', '01310-100'),
(3, 'Rua das Laranjeiras', '50', 'Jardim Europa', 'São Paulo', '01440-000'),
(4, 'Rua da Paz', '200', 'Moema', 'São Paulo', '04503-000'),
(5, 'Rua das Acácias', '321', 'Itaim Bibi', 'São Paulo', '04542-001'),
(6, 'Rua das Palmeiras', '77', 'Vila Mariana', 'São Paulo', '04120-001'),
(7, 'Rua do Sol', '88', 'Perdizes', 'São Paulo', '05014-000'),
(8, 'Rua das Hortênsias', '150', 'Morumbi', 'São Paulo', '05614-020'),
(9, 'Rua da Alegria', '25', 'Pinheiros', 'São Paulo', '05422-030'),
(10, 'Avenida Brasil', '900', 'Centro', 'São Paulo', '01010-000'),
(11, 'Rua Admin', '1', 'Centro', 'São Paulo', '01000-000'),
(12, 'Rua Nova', '450', 'Vila Madalena', 'São Paulo', '05433-020'),
(13, 'Rua das Rosas', '78', 'Jardim Paulista', 'São Paulo', '01415-000'),
(14, 'Rua do Carmo', '180', 'Liberdade', 'São Paulo', '01503-020'),
(15, 'Rua da Esperança', '333', 'Vila Prudente', 'São Paulo', '03110-000'),
(16, 'Rua do Comércio', '25', 'Brás', 'São Paulo', '03001-001'),
(17, 'Rua Verde', '77', 'Tatuapé', 'São Paulo', '03064-000'),
(18, 'Rua do Limoeiro', '88', 'Mooca', 'São Paulo', '03165-000'),
(19, 'Rua da Alegria', '199', 'Jardim Anália Franco', 'São Paulo', '03334-000'),
(20, 'Rua Central', '555', 'Vila Formosa', 'São Paulo', '03420-000');
