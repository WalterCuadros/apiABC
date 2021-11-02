CREATE DATABASE IF NOT EXISTS ABC;
USE ABC;

CREATE TABLE constants(
id 	int(255) auto_increment not null,
name_constant varchar(255),
content varchar(255),
CONSTRAINT pk_roles PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE programs(
id 	int(255) auto_increment not null,
name_program varchar(255),
CONSTRAINT pk_programs PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE roles(
id 	int(255) auto_increment not null,
name_rol varchar(255),
CONSTRAINT pk_roles PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE users(
id 		int(255) auto_increment not null,
email varchar(255),
name	varchar(255),
surname	varchar(255),
nit varchar(255),
id_program int(255),
id_rol int(255),
password varchar(255),
created_at datetime DEFAULT NULL,
updated_at datetime DEFAULT NULL,
remember_token varchar(255),
CONSTRAINT pk_users PRIMARY KEY(id),
CONSTRAINT fk_users_programs FOREIGN KEY(id_program) REFERENCES programs(id),
CONSTRAINT fk_users_roles FOREIGN KEY(id_rol) REFERENCES roles(id)
)ENGINE=InnoDb;

INSERT INTO roles (name_rol) VALUES ('Estudiante'),('Docente');
INSERT INTO programs (name_program) VALUES ('Ingeniería'),('Licenciatura');
INSERT INTO constants (name_constant,content) VALUES ('keyToken','MISIONTIC-S&ND6n!jZ52k');