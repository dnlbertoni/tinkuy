<?php

namespace Entidad;

use App\Lib\Database;
use App\Lib\Response;
use App\Lib\ResponseBootgrid;

class Producto_model{
    private $db;
    private $table = 'productos';
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
                $sql = "SELECT  t.codigo, t.name as Nombre ,e.name Estado, t2.name as Tipo , e2.name \"Estado Tipo\", concat(%s,t.id) as link 
                          FROM  productos t 
                     inner join tipoproductos t2 on t.idtipoproducto = t2.id
                     inner join estados e on t.estado=e.id
                     inner join estados e2 on t2.estado=e2.id ";
                $sql = sprintf($sql, $url);
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

    public function GetByTipo($idtipoproducto, $url=false)
    {
        try {
            $result = array();
            if($url){
                $sql = sprintf("SELECT t.*, '%s' as  home FROM %s t WHERE idtipoproducto = %d", $url, $this->table ,$idtipoproducto);
            }else{
                $sql = sprintf("SELECT t.* FROM $this->table t WHERE idtipoproducto = %d", $idtipoproducto);
            }
            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

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
                            codigo            = ?,
                            idtipoproducto    = ?,
                            estado            = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['name'],
                            $data['codigo'],
                            $data['idtipoproducto'],
                            $data['estado'],
                            $data['id']
                        )
                    );
            } else {
                $estado= new Estados_model();
                $idestado = $estado->defineEstado($this->table, 'creacion');
                if ($idestado) {
                    $sql = "INSERT INTO $this->table
                                (name, codigo, idtipoproducto, estado)
                                VALUES (?,?,?,? )";

                    $this->db->prepare($sql)
                        ->execute(
                            array(
                                $data['name'],
                                $data['codigo'],
                                $data['idtipoproducto'],
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