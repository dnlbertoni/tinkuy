<?php

namespace Entidad;

use App\Lib\Database;
use App\Lib\Response;

class Maquinaestados_model{
    private $db;
    private $table = 'maquinaestados';
    private $response;
    private $bootgrid;

    public function __construct()
    {
        $this->db = Database::StartUp();
        $this->response = new Response();
    }

    public function GetAll()
    {
        try {
            $result = array();
            $sql = sprintf("SELECT t.* FROM $this->table t");
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

    public function Get($id)
    {
        try {
            $result = array();
            $sql = sprintf("SELECT t.* FROM $this->table t WHERE id = %d", $id);
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