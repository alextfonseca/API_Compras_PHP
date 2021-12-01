create database compras;

use compras;

CREATE TABLE usuarios(
    id_usuario INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(15) NOT NULL,
    senha VARCHAR(32) NOT NULL,
    dtcria DATETIME DEFAULT NOW(),
    estatus CHAR(01) DEFAULT ''
);

INSERT INTO usuarios(usuario, senha)
VALUES('admin', md5('admin123'));

SELECT * FROM usuarios WHERE senha = md5('admin123');

select * from usuarios;

alter table usuarios
add column nome varchar(30) default '' after senha,
add column tipo varchar(20) default '' after estatus;


-- mudando a estrutura da tabela usuário --
alter table usuarios drop column id_usuario;
alter table usuarios modify usuario varchar(15) not null primary key;

-- estrutura da tabela de unidade de medidas --
create table unid_medida (
cod_unidade integer auto_increment primary key,
sigla varchar(03) default '',
descricao varchar(30) default '',
dtcria datetime default now(),
usucria varchar(15) default '',
estatus char(01) default '',

constraint foreign key fk_unidmed_prod (usucria) references usuarios(usuario)
); 

Select * from unid_medida;

-- estrutura da tabela de produtos --
 create table produtos (
	cod_produto integer auto_increment primary key,
    descricao varchar(30) default '',
    unid_medida integer default 0,
    estoq_minimo integer default 0,
    estoq_maximo integer default 0,
    dtcria datetime default now(),
    usucria varchar(15) default '',
    estatus char(01) default '',
    
    constraint foreign key fk_prod_unidmed (unid_medida) references unid_medida(cod_unidade),
    constraint foreign key fk_prod_usuarios (usucria) references usuarios(usuario)
 );
 
Select * from produtos;


-- estrutura da tabela de pedidos de compras - cabeçalho --
create table pedido_cab (
	num_pedido integer primary key,
    usucria varchar(15) default '',
    prazo date,
    observacao varchar(150) default '',
    dtcria datetime default now(),
    estatus char(01) default '',
    
    constraint foreign key fk_ped_usuarios (usucria) references usuarios(usuario)
); 


-- estrutura da tabela de pedidios de compras - detalhes --
create table pedido_det (
	num_pedido integer,
    cod_produto integer,
    qtde integer,
    dtcria datetime default now(),
    estatus char(01) default '',
    usucria varchar(15) default '',
    constraint foreign key fk_det_usuario (usucria) references usuarios(usuario),
    constraint foreign key fk_det_produto (cod_produto) references produtos(cod_produto),
    constraint foreign key fk_det_pedido (num_pedido) references pedido_cab(num_pedido)
);


























