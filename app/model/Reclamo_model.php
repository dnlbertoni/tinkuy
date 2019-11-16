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
                $sql = sprintf("SELECT t.*, concat(%s,t.id) link FROM $this->table t", $url);
            }else{
                $sql = sprintf("SELECT t.* FROM $this->table t");
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

}