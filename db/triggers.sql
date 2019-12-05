DELIMITER $$
CREATE TRIGGER insertarReclamos
    BEFORE INSERT ON reclamos
    FOR EACH ROW
BEGIN
    insert into historico_reclamos (idreclamo,fecha, estado_actual, idusuario) values (new.id,now(), new.estado, new.idusuario);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER updateReclamos
    BEFORE UPDATE ON reclamos
    FOR EACH ROW
BEGIN
    insert into historico_reclamos (idreclamo,fecha, estado_anterior, estado_actual, idusuario) values (OLD.id,NOW(), OLD.estado,NEW.estado, new.idusuario);
END$$
DELIMITER ;