CREATE DATABASE IF NOT EXISTS cadastro_funcionarios
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cadastro_funcionarios;

CREATE TABLE IF NOT EXISTS funcionarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    prontuario VARCHAR(50) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    idade SMALLINT UNSIGNED NOT NULL,
    data_nascimento DATE NOT NULL,
    data_admissao DATE NOT NULL,
    email VARCHAR(254) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    INDEX idx_funcionarios_nome (nome),
    INDEX idx_funcionarios_prontuario (prontuario)
) ENGINE=InnoDB
  DEFAULT CHARACTER SET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
