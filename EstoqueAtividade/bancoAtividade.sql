CREATE DATABASE bancoEstoque;

USE bancoEstoque;


create table produtos(
	cd_produto int not null primary key auto_increment unique,
    nm_produto varchar(80) not null,
    ds_quantidade_produto int not null,
    ds_descricao_produto varchar(100),
    ds_valor_produto float not null,
    ds_valor_venda_produto float not null
);

create table usuarios(
	cd_usuario int not null primary key auto_increment unique,
    nm_usuario varchar(50) not null,
    ds_senha_usuario int not null,
    ds_data_nascimento_usuario date not null,
    ds_email_usuario varchar(50) null
);

create table compras(
	cd_compra int not null primary key auto_increment,
    cd_produto INT NOT NULL,
    ds_quantidade_compra INT NOT NULL,
    ds_valor_compra FLOAT NOT NULL,
    ds_data_compra DATE NOT NULL,
    
    foreign key (cd_produto) references produtos(cd_produto)
);

create table vendas(
	cd_venda int not null primary key auto_increment,
    cd_produto int not null,
    ds_quantidade_venda int not null,
    ds_valor_venda float not null,
    ds_data_venda date not null,
    
    foreign key (cd_produto) references produtos(cd_produto)
);

-- produtos padrões do stock --------------------
insert into produtos(nm_produto, ds_quantidade_produto, ds_descricao_produto, ds_valor_produto, ds_valor_venda_produto) values ("banana", 67, "banana nana", 123.13, 67.13);
insert into produtos(nm_produto, ds_quantidade_produto, ds_descricao_produto, ds_valor_produto, ds_valor_venda_produto) values ("PC Gamer", 23, "PC Gamer Positivo, 4Gb Ram, 100Gb memoria interna, 1 nucleo", 1215.99, 1421.99);
insert into produtos(nm_produto, ds_quantidade_produto, ds_descricao_produto, ds_valor_produto, ds_valor_venda_produto) values ("Iphone 18 Pro Max", 42, "Novo Iphone, novo lixo, dinheiro jogado fora", 18265.50, 23150.99);
insert into produtos(nm_produto, ds_quantidade_produto, ds_descricao_produto, ds_valor_produto, ds_valor_venda_produto) values ("GTA 6", 100, "Novo GTA com Novos bug's que a comunidade não esperava ter", 600.99, 450.99);
-- ---------------------------------------------



