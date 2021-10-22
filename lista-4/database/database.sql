CREATE DATABASE empresa TEMPLATE template0;

\c empresa;

CREATE OR REPLACE FUNCTION trigger_set_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE produtos (
  id                      uuid                      DEFAULT uuid_generate_v4(),
  codigo                  SERIAL,
  nome                    VARCHAR(255)  NOT NULL,
  descricao               VARCHAR(510)  NOT NULL,
  quantidade              BIGINT        NOT NULL    DEFAULT 0,
  ativo                   BOOLEAN       NOT NULL    DEFAULT true,

  unidade_medida_saida    VARCHAR(5)    NOT NULL,
  unidade_medida_entrada  VARCHAR(5)    NOT NULL,
  quantidade_entrada      BIGINT        NOT NULL,

  created_at              TIMESTAMP                 DEFAULT current_timestamp,
  updated_at              TIMESTAMP                 DEFAULT current_timestamp,

  PRIMARY KEY (id),

  CONSTRAINT cons_produto CHECK (quantidade_entrada > 0 AND nome <> '')
);

CREATE TRIGGER set_timestamp
BEFORE UPDATE ON produtos
FOR EACH ROW
EXECUTE PROCEDURE trigger_set_timestamp();

CREATE TABLE movimentacoes (
  id                      uuid                  DEFAULT uuid_generate_v4(),
  id_produto              uuid        NOT NULL,

  operacao                VARCHAR(5)  NOT NULL,
  quantidade_operacao     BIGINT      NOT NULL,
  data_operacao           DATE        NOT NULL,

  unidade_medida_saida    VARCHAR (5) NOT NULL,
  unidade_medida_entrada  VARCHAR (5) NOT NULL,
  quantidade_entrada      BIGINT      NOT NULL,

  created_at              TIMESTAMP             DEFAULT current_timestamp,
  updated_at              TIMESTAMP             DEFAULT current_timestamp,

  PRIMARY KEY (id),
  FOREIGN KEY (id_produto) REFERENCES produtos (id)
);

CREATE TRIGGER set_timestamp
BEFORE UPDATE ON movimentacoes
FOR EACH ROW
EXECUTE PROCEDURE trigger_set_timestamp();


CREATE OR REPLACE FUNCTION atualiza_estoque() RETURNS TRIGGER AS $trigger_bound$
    BEGIN
        UPDATE produtos SET quantidade = quantidade + NEW.quantidade_operacao
        WHERE id = NEW.id_produto;
        RETURN NULL;
    END;
$trigger_bound$ LANGUAGE plpgsql;

CREATE TRIGGER atualiza_estoque_movimentacao
AFTER UPDATE OR INSERT OR DELETE ON movimentacoes
    FOR EACH ROW EXECUTE PROCEDURE atualiza_estoque();
