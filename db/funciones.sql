DROP FUNCTION IF EXISTS botonera
CREATE FUNCTION botonera(id integer) RETURNS VARCHAR(2000)
BEGIN
    DECLARE salida VARCHAR(2000);
    SET salida =concat('<a href=\"/reclamo/',id,'/diag\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Analizar Reclamo\" class=\"btn btn-xs help\"><i class=\"fa fa-bug\"></i></a>',
                       '<a href=\"/reclamo/',id,'/notif\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Cliente Notificado\" class=\"btn btn-xs help\" ><i class=\"fa fa-send\"></i></a>',
                       '<a href=\"/reclamo/',id,'/envio\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Enviar Caja\" class=\"btn btn-xs help\"><i class=\"fa fa-truck\"></i></a>',
                       '<a href=\"/reclamo/',id,'/recep\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Caja Recibida \" class=\"btn btn-xs help\"><i class=\"fa fa-shopping-basket\"></i></a>',
                       '<a href=\"/reclamo/',id,'/resol\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Resolver Reclamo\" class=\"btn btn-xs help\"><i class=\"fa fa-check-square\"></i></a>',
                       '<a href=\"/reclamo/',id,'/anul\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Anular Reclamo\" class=\"btn btn-xs help\"><i class=\"fa fa-ban\"></i></a>');
    RETURN salida;
ENd;