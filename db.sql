CREATE DATABASE WIDGETS_DB;

USE WIDGETS_DB;


CREATE TABLE WIDGET_TYPE(
ID_WIDGET_TYPE INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
NAME_WIDGET_TYPE VARCHAR(50) NOT NULL
);

CREATE TABLE WIDGET(
ID_WIDGET INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
WIDGET_NAME VARCHAR(50) NOT NULL,
TYPE_WIDGET INT NOT NULL,
WIDGET_DATA JSON,
FOREIGN KEY (TYPE_WIDGET) REFERENCES WIDGET_TYPE(ID_WIDGET_TYPE));

CREATE TABLE IFRAME(
ID_IFRAME_PROPERTIES INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
IFRAME_PROPERTIES JSON,
WIDGET_ASSIGNATED INT NOT NULL,
FOREIGN KEY (WIDGET_ASSIGNATED) REFERENCES WIDGET(ID_WIDGET)
);

insert into WIDGET_TYPE VALUES(ID_WIDGET_TYPE,'youtube');
insert into WIDGET_TYPE VALUES(ID_WIDGET_TYPE,'slider');





