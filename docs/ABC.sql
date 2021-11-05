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

CREATE TABLE estados(
    id 	int(255) auto_increment not null,
    name_estado varchar(255),
    CONSTRAINT pk_estados PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE users(
id 		int(255) auto_increment not null,
email varchar(255),
name	varchar(255),
surname	varchar(255),
nit varchar(255),
image_url	varchar(255),
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

CREATE TABLE user_program(
    id 		int(255) auto_increment not null,
    id_user int(255),
    id_program int(255),
    CONSTRAINT pk_user_programm PRIMARY KEY(id),
    CONSTRAINT fk_user_program_users FOREIGN KEY(id_program) REFERENCES users(id),
    CONSTRAINT fk_user_program_programs FOREIGN KEY(id_user) REFERENCES programs(id)
    )ENGINE=InnoDb;


CREATE TABLE autodiagnostico( 
    id 		int(255) auto_increment not null,
    id_user int(255),
    id_estado int(255),
    pregunta_1 varchar(255),
    pregunta_2 varchar(255),
    fecha varchar(255),
    CONSTRAINT pk_autodiagnostico PRIMARY KEY(id),
    CONSTRAINT fk_autodiagnostico_users FOREIGN KEY(id_user) REFERENCES users(id),
    CONSTRAINT fk_autodiagnostico_estados FOREIGN KEY(id_estado) REFERENCES estados(id)
    )ENGINE=InnoDb;

INSERT INTO roles (name_rol) VALUES ('Estudiante'),('Docente');
INSERT INTO programs (name_program) VALUES ('Ingeniería'),('Licenciatura');
INSERT INTO constants (name_constant,content) VALUES ('keyToken','MISIONTIC-S&ND6n!jZ52k');
INSERT INTO users(email,name,surname,nit,image_url,id_program,id_rol,password) VALUES ('usuario@email.com','usuario','test','12345','imagen_test.png','1','1','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3');
INSERT INTO user_program (id_user,id_program) VALUES ('1','1');
