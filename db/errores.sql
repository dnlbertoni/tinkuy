DROP TABLE IF EXISTS errores;
CREATE TABLE IF NOT EXISTS errores(
                                      id   INTEGER  NOT NULL PRIMARY KEY
    ,name VARCHAR(100) NOT NULL
);
INSERT INTO errores(id,name) VALUES (1,'No hay Provincias definidas');
INSERT INTO errores(id,name) VALUES (2,'No hay Tipos de Productos definidos y Productos Asociados');
INSERT INTO errores(id,name) VALUES (3,'No hay Productos definidos');
INSERT INTO errores(id,name) VALUES (4,'No hay Tipos de Reclamos definidos');
INSERT INTO errores(id,name) VALUES (5,'No hay Lugares de Compras definidos');
INSERT INTO errores(id,name) VALUES (6,'No hay Estado para este evento definido');
INSERT INTO errores(id,name) VALUES (7,'No hay Origen definido');
INSERT INTO errores(id,name) VALUES (8,'Nombre de Usuario Activo Existente');
INSERT INTO errores(id,name) VALUES (9,'Usuario Pendiente de Aprobacion');
INSERT INTO errores(id,name) VALUES (10,'Usuario y Contraseña Invalidos');
INSERT INTO errores(id,name) VALUES (11,'Accion no definida');