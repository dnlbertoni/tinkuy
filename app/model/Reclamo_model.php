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
                $sql = sprintf("SELECT  t.id id,
                                                t.fechaini fecha , 
                                                t.apelnom cliente,
                                                p.codigo codigo,
                                                p.name producto, 
                                                t.updated actualizacion,
                                                o.name origen,    
                                                t.estado idestado,
                                                t.dictamen,
                                                e.name estado 
                                        FROM reclamos t
                                        inner join productos p on p.id=t.idproducto
                                        inner join estados   e on e.id=t.estado
                                        inner join origenes o on t.idorigen = o.id
                                        ");
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

    public function analizar($idreclamo, $dictamen, $idusuario=null){
        $fechoy = new \DateTime();
        $estado= new Estados_model();
        $idestado = $estado->defineEstado($this->table, 'diagnostico');
        try {
            if($idestado>0 ){
                $sql = "UPDATE $this->table SET 
                            idusuario          = ?,
                            dictamen           = ?,
                            updated            = ?,
                            estado             = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $idusuario,
                            $dictamen,
                            $fechoy->format('Y-m-d H:i:s'),
                            $idestado,
                            $idreclamo
                        )
                    );
                $this->response->setResponse(0);
            }else{
                $this->response->setResponse(6);
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function notificar($idreclamo, $medio,$idusuario=null){
        $fechoy = new \DateTime();
        $estado= new Estados_model();
        $idestado = $estado->defineEstado($this->table, 'notificacion');
        try {
            if($idestado>0 ){
                $sql = "UPDATE $this->table SET
                            contacto_cliente    = ?,
                            idusuario          = ?,
                            updated            = ?,
                            estado             = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $medio,
                            $idusuario,
                            $fechoy->format('Y-m-d H:i:s'),
                            $idestado,
                            $idreclamo
                        )
                    );
                $this->response->setResponse(0);
            }else{
                $this->response->setResponse(6);
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function enviar($idreclamo, $nrotrack,$idusuario=null){
        $fechoy = new \DateTime();
        $estado= new Estados_model();
        $idestado = $estado->defineEstado($this->table, 'envio');
        try {
            if($idestado>0 ){
                $sql = "UPDATE $this->table SET
                            nrotrack    = ?,
                            idusuario          = ?,
                            updated            = ?,
                            estado             = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $nrotrack,
                            $idusuario,
                            $fechoy->format('Y-m-d H:i:s'),
                            $idestado,
                            $idreclamo
                        )
                    );
                $this->response->setResponse(0);
            }else{
                $this->response->setResponse(6);
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function recibir($idreclamo, $idusuario=null){
        $fechoy = new \DateTime();
        $estado= new Estados_model();
        $idestado = $estado->defineEstado($this->table, 'recepcion');
        try {
            if($idestado>0 ){
                $sql = "UPDATE $this->table SET
                            idusuario          = ?,
                            updated            = ?,
                            estado             = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $idusuario,
                            $fechoy->format('Y-m-d H:i:s'),
                            $idestado,
                            $idreclamo
                        )
                    );
                $this->response->setResponse(0);
            }else{
                $this->response->setResponse(6);
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

    public function resolver($idreclamo, $dictamen, $idusuario=null){
        $fechoy = new \DateTime();
        $estado= new Estados_model();
        $idestado = $estado->defineEstado($this->table, 'resolucion');
        try {
            if($idestado>0 ){
                $sql = "UPDATE $this->table SET
                            dictamen_final     = ?,
                            idusuario          = ?,
                            updated            = ?,
                            estado             = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $dictamen,
                            $idusuario,
                            $fechoy->format('Y-m-d H:i:s'),
                            $idestado,
                            $idreclamo
                        )
                    );
                $this->response->setResponse(0);
            }else{
                $this->response->setResponse(6);
            }
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
        }
    }

}