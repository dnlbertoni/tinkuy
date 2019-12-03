DROP TABLE IF EXISTS eventos;
CREATE TABLE IF NOT EXISTS eventos(
                                      id   INTEGER  NOT NULL PRIMARY KEY
    ,name VARCHAR(12) NOT NULL
);
INSERT INTO eventos(id,name) VALUES (1,'creacion');
INSERT INTO eventos(id,name) VALUES (2,'activacion');
INSERT INTO eventos(id,name) VALUES (3,'suspension');
INSERT INTO eventos(id,name) VALUES (4,'diagnostico');
INSERT INTO eventos(id,name) VALUES (5,'envio');
INSERT INTO eventos(id,name) VALUES (6,'recepcion');
INSERT INTO eventos(id,name) VALUES (7,'notificacion');
INSERT INTO eventos(id,name) VALUES (8,'resolucion');
INSERT INTO eventos(id,name) VALUES (9,'anulacion');
