create database bancoEtec;

-- drop database bancoetec;

use bancoEtec;

create table tb_alunos(
cd_aluno int not null auto_increment primary key,
nm_aluno varchar(80) not null,
ds_matricula_aluno varchar(5) not null,
ds_email_aluno varchar(80) not null,
ds_serie_aluno varchar(8) not null
);

create table tb_diciplinas(
cd_diciplina int not null auto_increment primary key,
nm_diciplina varchar(50) not null
);

create table tb_professores(
cd_professor int not null auto_increment primary key,
nm_professor varchar(80) not null,
ds_email_professor varchar(60) not null,
ds_telefone_professor varchar(13)
);

create table tb_professores_diciplinas(
cd_professor int not null,
cd_diciplina int not null,

primary key (cd_professor, cd_diciplina),

foreign key (cd_professor) references tb_professores(cd_professor),
foreign key (cd_diciplina) references tb_diciplinas(cd_diciplina)
);

-- insert's dos alunos
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("marco antonio", "25105", "marco@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("brenno dantas", "25200", "brenno17@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("matheus rossi", "25055", "mahigga@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("ricard", "25005", "ricardo@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("matheus roxa", "25145", "mateo@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("matheus ovelha", "25205", "AchaQueSabeFarmar@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("izaqui", "25355", "zagueiro@gmail.com", "2° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("joão aura", "25067", "auraSigmaDaBahia@gmail.com", "67° ano");
insert into tb_alunos(nm_aluno, ds_matricula_aluno, ds_email_aluno, ds_serie_aluno) values ("luccas com dois C", "25275", "NaoSabeDeCSHARP@gmail.com", "2° ano");

-- insert's das diciplinas
insert into tb_diciplinas(nm_diciplina) values ("desenvolvimento de sistemas");
insert into tb_diciplinas(nm_diciplina) values ("programação web");
insert into tb_diciplinas(nm_diciplina) values ("programação mobile");
insert into tb_diciplinas(nm_diciplina) values ("banco de dados");
insert into tb_diciplinas(nm_diciplina) values ("química");
insert into tb_diciplinas(nm_diciplina) values ("matemática");
insert into tb_diciplinas(nm_diciplina) values ("português");

-- insert's dos professores
insert into tb_professores(nm_professor, ds_email_professor, ds_telefone_professor) values ("oswaldo", "oswaldo@gmail.com", "13 12332-3233");
insert into tb_professores(nm_professor, ds_email_professor, ds_telefone_professor) values ("matheus", "calixtom@gmail.com", "13 46521-3268");
insert into tb_professores(nm_professor, ds_email_professor, ds_telefone_professor) values ("bananinha", "bananudo@gmail.com", "13 32154-6598");
insert into tb_professores(nm_professor, ds_email_professor, ds_telefone_professor) values ("JA", "joseAdriano@gmail.com", "13 24568-6587");
insert into tb_professores(nm_professor, ds_email_professor, ds_telefone_professor) values ("Amauri", "maureco@gmail.com", "13 74812-3958");
insert into tb_professores(nm_professor, ds_email_professor, ds_telefone_professor) values ("meire", "meire67@gmail.com", "13 12157-8495");     

-- insert's da tabela associativa
-- desenvolvimento de sistemas-------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (1, 1);
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (1, 2);
-- ------------------------------------------------------------------

-- programação web--------------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (2, 2);
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (2, 3);
-- ------------------------------------------------------------------

-- programação mobile----------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (3, 2);
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (3, 3);
-- --------------------------------------------------------------------

-- banco de dados-------------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (4, 1);
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (4, 2);
-- -----------------------------------------------------------------------

-- quimica-----------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (5, 4);

-- matematica----------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (6, 5);

-- portugues---------
insert into tb_professores_diciplinas (cd_diciplina, cd_professor) value (7, 6);



