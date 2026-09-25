-- arquivo para guardar apenas o codigo do banco

create database bancoLab;

use bancoLab;

create table laboratorios (
cd_laboratorio int primary key not null auto_increment,
nm_laboratorio varchar(50) not null,
ds_quantidade_computadores_laboratorio int not null,
ds_disponibilidade_laboratorio bool not null
);

create table professores (
cd_professor int not null auto_increment primary key,
nm_professor varchar(80) not null,
ds_email_professor varchar(80) not null
);

create table materias (
cd_materia int not null auto_increment primary key,
nm_materia varchar(60) not null
);

create table turmas (
cd_turma int not null auto_increment primary key,
nm_turma varchar(50) not null,
ds_quantidade_alunos_turma int not null,
ds_curso_turma varchar(80) not null
);

create table reservas (
cd_reserva int not null auto_increment primary key,
ds_quantidade_reserva int not null,
ds_tempo_inicial_reserva time not null,
ds_tempo_final_reserva time not null,

id_laboratorio int not null,
id_professor int not null,
id_turma int not null,

foreign key (id_laboratorio) references laboratorios (cd_laboratorio),
foreign key (id_professor) references professores (cd_professor),
foreign key (id_turma) references turmas (cd_turma)
);

create table professores_materias (
id_professor int not null,
id_materia int not null,

primary key(id_professor, id_materia),

foreign key (id_professor) references professores(cd_professor),
foreign key (id_materia) references materias(cd_materia)
);


