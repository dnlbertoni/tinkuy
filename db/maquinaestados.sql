DROP TABLE IF EXISTS maquinaestados;
CREATE TABLE IF NOT EXISTS maquinaestados(
                                             id   INTEGER  NOT NULL PRIMARY KEY AUTO_INCREMENT
    ,name VARCHAR(13) NOT NULL
);
INSERT INTO maquinaestados(id,name) VALUES (1,'tiporeclamos');
INSERT INTO maquinaestados(id,name) VALUES (2,'tipoproductos');
INSERT INTO maquinaestados(id,name) VALUES (3,'lugarcompra');
INSERT INTO maquinaestados(id,name) VALUES (4,'roles');
INSERT INTO maquinaestados(id,name) VALUES (5,'usuarios');
INSERT INTO maquinaestados(id,name) VALUES (6,'reclamos');
INSERT INTO maquinaestados(id,name) VALUES (7,'productos');
INSERT INTO maquinaestados(id,name) VALUES (8,'origenes');