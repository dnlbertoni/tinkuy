<?php

namespace Entidad;

use App\Lib\Database;
use App\Lib\Response;
use App\Lib\ResponseBootgrid;

class Reclamo_model{
    private $db;
    private $table = 'reclamos';
    private $response;
    private $bootgrid;

    public function __construct()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
        $this->bootgrid = new ResponseBootgrid();
    }

    public function GetAll($url=false)
    {
        try {
            $result = array();
            if($url){
                $sql = sprintf("SELECT t.*, concat(%s,t.id) link FROM $this->table t", $url);
            }else{
                $sql = sprintf("SELECT t.* FROM $this->table t");
            }

            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;

        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        };
    }

    public function GetAllBootgrid($url=false)
    {
        try {
            $result = array();
            if($url){
                $sql = sprintf("SELECT t.fechaini, t.apelnom, t.updated, t.estado, 
                                               concat('<a href=\"/reclamo/',t.id,'/diag\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Analizar Reclamo\" class=\"btn btn-xs help\"><i class=\"fa fa-bug\"></i></a>',
                                                      '<a href=\"/reclamo/',t.id,'/notif\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Cliente Notificado\" class=\"btn btn-xs help\" ><i class=\"fa fa-send\"></i></a>',                                                   
                                                      '<a href=\"/reclamo/',t.id,'/envio\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Enviar Caja\" class=\"btn btn-xs help\"><i class=\"fa fa-truck\"></i></a>',                                                   
                                                      '<a href=\"/reclamo/',t.id,'/recep\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Caja Recibida \" class=\"btn btn-xs help\"><i class=\"fa fa-shopping-basket\"></i></a>',                                                   
                                                      '<a href=\"/reclamo/',t.id,'/resol\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Resolver Reclamo\" class=\"btn btn-xs help\"><i class=\"fa fa-check-square\"></i></a>',
                                                      '<a href=\"/reclamo/',t.id,'/anul\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Anular Reclamo\" class=\"btn btn-xs help\"><i class=\"fa fa-ban\"></i></a>') as acciones                                                   
                                          FROM $this->table t");
            }else{
                $sql = sprintf("SELECT t.fechaini, t.apelnom, t.updated, t.estado FROM $this->table t");
            }
            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->bootgrid->setResponse($stm->fetchAll(), $stm->rowCount());

            return $this->bootgrid;

        } catch (Exception $e) {
            $this->bootgrid->setResponse(false, $e->getMessage());
            return $this->bootgrid;
        };
    }

    public function Get($id)
    {
        try {
            $result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
            $stm->execute(array($id));

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function InsertOrUpdate($data){
        $fechoy = new \DateTime();
        try {
            if (isset($data['id'])) {
                $sql = "UPDATE $this->table SET 
                            fechaini           = ?,
                            apelnom            = ?, 
                            correoelectronico  = ?,
                            telefono           = ?,
                            tipoprod           = ?,
                            idproducto         = ?,
                            lote               = ?, 
                            fechavto           = ?,
                            tiporeclamo        = ?,
                            provincia          = ?,
                            idlugarcompra      = ?,
                            comentario         = ?,
                            fechafin           = ?,
                            idusuario          = ?,
                            estado             = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['fechaini'],
                            $data['apelnom'],
                            $data['correoelectronico'],
                            $data['telefono'],
                            $data['tipoprod'],
                            $data['idproducto'],
                            $data['lote'],
                            $data['fechavto'],
                            $data['tiporeclamo'],
                            $data['provincia'],
                            $data['idlugarcompra'],
                            $data['comentario'],
                            $data['fechafin'],
                            $data['idusuario'],
                            $data['estado'],
                            $data['id']
                        )
                    );
            } else {
                $sql = "INSERT INTO $this->table (
                            fechaini           ,
                            apelnom            , 
                            correoelectronico  ,
                            telefono           ,
                            tipoprod           ,
                            idproducto         ,
                            lote               , 
                            fechavto           ,
                            tiporeclamo        ,
                            provincia          ,
                            idlugarcompra      ,
                            comentario         ,
                            fechafin           ,
                            idusuario          ,
                            estado             
                                )
                            VALUES (
                                    ?,?,?, ?, ?,
                                    ?,?,?, ?, ?,
                                    ?,?,?, ?, ?
                            )";
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $fechoy->format('Y-m-d H:i:s'),
                            $data['apelnom'],
                            $data['correoelectronico'],
                            $data['telefono'],
                            $data['tipoprod'],
                            $data['idproducto'],
                            $data['lote'],
                            $data['fechavto'],
                            $data['tiporeclamo'],
                            $data['provincia'],
                            $data['idlugarcompra'],
                            $data['comentario'],
                            $data['fechafin'],
                            $data['usuario'],
                            1
                        )
                    );
            }
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function Delete($id)
    {
        try {
            $stm = $this->db
                ->prepare("DELETE FROM $this->table WHERE id = ?");

            $stm->execute(array($id));

            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function CrearReclamo($data){
        $fechoy = new \DateTime();
        $estado= new Estados_model();
        $origen = new Origenes_model();
        $idestado = $estado->defineEstado($this->table, 'creacion');
        $idorigen = $origen->GetByHash($data['origen']);
        $error = '';
        $error .= ($idorigen>0)?'0':'1';
        $error .= ($idestado>0)?'0':'1';
        //echo $error;die();
        if(bindec($error)==0){
            try {
                $sql = "INSERT INTO $this->table (
                            fechaini           ,
                            apelnom            , 
                            correoelectronico  ,
                            telefono           ,
                            tipoprod           ,
                            idproducto         ,
                            lote               , 
                            fechavto           ,
                            tiporeclamo        ,
                            provincia          ,
                            idlugarcompra      ,
                            comentario         ,
                            idorigen           ,
                            updated            ,
                            idusuario          ,
                            estado             
                                )
                            VALUES (
                                    ?,?,?, ?, ?,
                                    ?,?,?, ?, ?,
                                    ?,?,?, ?, ?,?
                            )";
                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $fechoy->format('Y-m-d H:i:s'),
                            $data['apelnom'],
                            $data['correoelectronico'],
                            $data['telefono'],
                            $data['tipoprod'],
                            $data['idproducto'],
                            $data['lote'],
                            $data['fechavto'],
                            $data['tiporeclamo'],
                            $data['provincia'],
                            $data['idlugarcompra'],
                            $data['comentario'],
                            $idorigen,
                            $fechoy->format('Y-m-d H:i:s'),
                            $data['usuario'],
                            $idestado
                        )
                    );
                $this->response->setResponse(true);
                return $this->response;
            } catch (Exception $e) {
                $this->response->setResponse(false, $e->getMessage());
            }
        }else{
            $this->response->setResponse($error, $error);

        }
    }


}