CREATE DATABASE easy_manager_base;

CREATE TABLE USER
(
    id int auto_increment
    primary key,
    username varchar(50) not null,
    cpf varchar(15) null,
    email varchar(50) not null,
    manager tinyint default 0 null
);

CREATE TABLE project (
    id integer primary key auto_increment,
    title varchar(50) not null,
    end_date datetime not null,
    status tinyint (1),
    user_id integer not null,
    foreign key (user_id) REFERENCES user (id) ON DELETE CASCADE
);

CREATE TABLE task (
    id integer primary key auto_increment,
    title varchar(50) not null,
    description varchar(300) null,
    end_date datetime not null,
    status tinyint (1),
    user_id integer not null,
    project_id integer not null,
    foreign key (user_id) REFERENCES user (id) ON DELETE CASCADE,
    foreign key (project_id) REFERENCES project (id) ON DELETE CASCADE
);

INSERT INTO user (username,cpf,email,manager) values ('admin','443.563.452-04','443.237.098-84',1)