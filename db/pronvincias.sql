DROP TABLE IF EXISTS pronvincias;
CREATE TABLE IF NOT EXISTS pronvincias(
   nombre_completo VARCHAR(66) NOT NULL PRIMARY KEY
  ,fuente          VARCHAR(3) NOT NULL
  ,iso_id          VARCHAR(4) NOT NULL
  ,nombre          VARCHAR(53) NOT NULL
  ,id              INTEGER  NOT NULL
  ,categoria       VARCHAR(15) NOT NULL
  ,iso_nombre      VARCHAR(31) NOT NULL
  ,centroidelat    NUMERIC(17,13) NOT NULL
  ,centroidelon    NUMERIC(17,13) NOT NULL
);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Misiones','IGN','AR-N','Misiones',54,'Provincia','Misiones',-26.8753965086829,-54.6516966230371);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de San Luis','IGN','AR-D','San Luis',74,'Provincia','San Luis',-33.7577257449137,-66.0281298195836);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de San Juan','IGN','AR-J','San Juan',70,'Provincia','San Juan',-30.8653679979618,-68.8894908486844);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Entre Ríos','IGN','AR-E','Entre Ríos',30,'Provincia','Entre Ríos',-32.0588735436448,-59.2014475514635);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Santa Cruz','IGN','AR-Z','Santa Cruz',78,'Provincia','Santa Cruz',-48.8154851827063,-69.9557621671973);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Río Negro','IGN','AR-R','Río Negro',62,'Provincia','Río Negro',-40.4057957178801,-67.229329893694);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia del Chubut','IGN','AR-U','Chubut',26,'Provincia','Chubut',-43.7886233529878,-68.5267593943345);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Córdoba','IGN','AR-X','Córdoba',14,'Provincia','Córdoba',-32.142932663607,-63.8017532741662);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Mendoza','IGN','AR-M','Mendoza',50,'Provincia','Mendoza',-34.6298873058957,-68.5831228183798);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de La Rioja','IGN','AR-F','La Rioja',46,'Provincia','La Rioja',-29.685776298315,-67.1817359694432);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Catamarca','IGN','AR-K','Catamarca',10,'Provincia','Catamarca',-27.3358332810217,-66.9476824299928);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de La Pampa','IGN','AR-L','La Pampa',42,'Provincia','La Pampa',-37.1315537735949,-65.4466546606951);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Santiago del Estero','IGN','AR-G','Santiago del Estero',86,'Provincia','Santiago del Estero',-27.7824116550944,-63.2523866568588);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Corrientes','IGN','AR-W','Corrientes',18,'Provincia','Corrientes',-28.7743047046407,-57.8012191977913);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Santa Fe','IGN','AR-S','Santa Fe',82,'Provincia','Santa Fe',-30.7069271588117,-60.9498369430241);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Tucumán','IGN','AR-T','Tucumán',90,'Provincia','Tucumán',-26.9478001830786,-65.3647579441481);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia del Neuquén','IGN','AR-Q','Neuquén',58,'Provincia','Neuquén',-38.6417575824599,-70.1185705180601);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Salta','IGN','AR-A','Salta',66,'Provincia','Salta',-24.2991344492002,-64.8144629600627);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia del Chaco','IGN','AR-H','Chaco',22,'Provincia','Chaco',-26.3864309061226,-60.7658307438603);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Formosa','IGN','AR-P','Formosa',34,'Provincia','Formosa',-24.894972594871,-59.9324405800872);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Jujuy','IGN','AR-Y','Jujuy',38,'Provincia','Jujuy',-23.3200784211351,-65.7642522180337);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Ciudad Autónoma de Buenos Aires','IGN','AR-C','Ciudad Autónoma de Buenos Aires',02,'Ciudad Autónoma','Ciudad Autónoma de Buenos Aires',-34.6144934119689,-58.4458563545429);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Buenos Aires','IGN','AR-B','Buenos Aires',06,'Provincia','Buenos Aires',-36.6769415180527,-60.5588319815719);
INSERT INTO pronvincias(nombre_completo,fuente,iso_id,nombre,id,categoria,iso_nombre,centroidelat,centroidelon) VALUES ('Provincia de Tierra del Fuego, Antártida e Islas del Atlántico Sur','IGN','AR-V','Tierra del Fuego, Antártida e Islas del Atlántico Sur',94,'Provincia','Tierra del Fuego',-82.52151781221,-50.7427486049785);
