CREATE TABLE produtos (
  id serial PRIMARY KEY,
  codigo VARCHAR (50) UNIQUE NOT NULL,
  nome VARCHAR (255) UNIQUE NOT NULL,
  descricao VARCHAR (510) NOT NULL,
  ativo TINYINT NOT NULL DEFAULT 1,
  preco_unitario  NOT NULL,
  ultimo_custo_unitario NOT NULL,
  unidade_medida_saida VARCHAR (5) NOT NULL,
  unidade_medida_entrada VARCHAR (5) NOT NULL,
  quantidade_entrada INT NOT NULL,
  created_at TIMESTAMP NOT NULL,
  updated_at TIMESTAMP NOT NULL,
);

CREATE TABLE movimentacoes (
  id serial PRIMARY KEY,
  operacao NOT NULL,
  quantidade INT NOT NULL,
  custo_unitario
);