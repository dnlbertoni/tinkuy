<?php

namespace Entidad;

use App\Lib\Database;
use App\Lib\Response;
use App\Lib\ResponseBootgrid;

class Tiporeclamo_model{
    private $db;
    private $table = 'tiporeclamos';
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
                $sql = "SELECT t.id id, t.name Nombre, e.name Estado, concat(%s,t.id) link FROM %s t inner join estados e on e.id=t.estado";
                $sql = sprintf($sql, $url, $this->table);
            }else{
                $sql = sprintf("SELECT t.id id, t.name Nombre, e.name Estado FROM $this->table t inner join estados e on e.id=t.estado");
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

    public function Get($id, $url=false)
    {
        try {
            $result = array();
            if($url){
                $sql = sprintf("SELECT t.*, '%s' as  home FROM %s t WHERE id = %d", $url, $this->table ,$id);
            }else{
                $sql = sprintf("SELECT t.* FROM $this->table t WHERE id = %d", $id);
            }
            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function InsertOrUpdate($data){
        try {
            if (isset($data['id'])) {
                $sql = "UPDATE $this->table SET 
                            name              = ?, 
                            estado            = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['name'],
                            $data['estado'],
                            $data['id']
                        )
                    );
            } else {
                $estado= new Estados_model();
                $idestado = $estado->defineEstado($this->table, 'creacion');
                if ($idestado) {
                    $sql = "INSERT INTO $this->table
                                (name, estado)
                                VALUES (?,? )";

                    $this->db->prepare($sql)
                        ->execute(
                            array(
                                $data['name'],
                                $idestado
                            )
                        );
                }else{
                    $this->response->setResponse('-1','No se pudo Determinar el Estado');
                }
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