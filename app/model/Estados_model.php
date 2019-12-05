<?php

namespace Entidad;

use App\Lib\Database;
use App\Lib\Response;
use App\Lib\ResponseBootgrid;

class Estados_model{
    private $db;
    private $table = 'estados';
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
                $sql = "SELECT t.id, t.name Nombre, me.name tabla, e.name Evento, case when (t.aplica = 1 ) then 'Si' else 'No' end   Aplica , concat(%s,t.id) link 
                          FROM %s t
                        inner join maquinaestados me on me.id = t.idmaquinaestado
                        inner join eventos e         on e.id  = t.idevento
                        order by 3,4,2
                          ";
                $sql = sprintf($sql, $url, $this->table);
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

    public function InsertOrUpdate($data){
        try {
            if (isset($data['id'])) {
                $sql = "UPDATE $this->table SET 
                            name              = ?, 
                            idmaquinaestado   = ?,
                            idevento          = ?,
                            aplica            = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['name'],
                            $data['idmaquinaestado'],
                            $data['idevento'],
                            $data['aplica'],
                            $data['id']
                        )
                    );
            } else {
                if (!$this->existeRelacion($data['idmaquinaestado'], $data['idevento'])) {
                    $sql = "INSERT INTO $this->table
                            (name, idmaquinaestado, idevento, aplica)
                            VALUES (?,?,?,? )";

                    $this->db->prepare($sql)
                        ->execute(
                            array(
                                $data['name'],
                                $data['idmaquinaestado'],
                                $data['idevento'],
                                $data['aplica']
                            )
                        );
                    $this->response->setResponse(true);
                } else {
                    $this->response->setResponse('11', 'Ya existe esa Relacion');
                };
                return $this->response;
            }
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

    private function existeRelacion($idmaquinaestado,$idevento){
        try {
            $result = array();
            $sql = sprintf("SELECT count(*) cantidad from estados WHERE idmaquinaestado = %s and idevento=%s", $idmaquinaestado, $idevento);
            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();
            $result=$this->response->result->cantidad;
            if($result>0){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return true;
        }
    }

    public function SinRelacion($url){
        try {
            $result = array();
            $sql = sprintf("SELECT me.name Tabla, ev.name Evento, concat(%s,me.id,'/',ev.id) link
                                    from maquinaestados me,  eventos ev
                                    where concat(me.id,ev.id) not in (select concat(idmaquinaestado,idevento) from estados)
                            ", $url);
            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->bootgrid->setResponse($stm->fetchAll(), $stm->rowCount());

            return $this->bootgrid;

        } catch (Exception $e) {
            $this->bootgrid->setResponse(false, $e->getMessage());
            return $this->bootgrid;
        };
    }

    public function defineEstado(string $maquina, string $evento ){
        try {
            $result = array();
            $sql = "        SELECT t.id FROM estados t
          inner join maquinaestados m on t.idmaquinaestado = m.id
          inner join eventos e on t.idevento=e.id
               where m.name = '%s' and e.name='%s'";
            $sql = sprintf($sql, $maquina, $evento);
            $stm = $this->db->prepare($sql);
            $stm->execute();
            $result = $stm->fetch();

            return $result->id;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return false;
        }
    }

    public function flujoEstados(){
        try {
            $result = array();
            $sql = "SELECT e.id,f.idevento_new 
                      FROM flujo_eventos f
                    inner join estados e on e.idmaquinaestado=f.idmaquinaestados and e.idevento=f.idevento_old";
            //$sql = sprintf($sql);
            $stm = $this->db->prepare($sql);
            $stm->execute();
            $result = $stm->fetchAll();

            return $result;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return false;
        }
    }

}