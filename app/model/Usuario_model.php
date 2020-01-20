<?php

namespace Entidad;

use App\Lib\Database;
use App\Lib\Response;
use App\Lib\ResponseBootgrid;

class Usuario_model{
    private $db;
    private $table = 'usuarios';
    private $response;
    private $bootgrid;
    private $method;
    private $key;
    private $iv;

    public function __construct()
    {
        $this->db       = Database::StartUp();
        $this->response = new Response();
        $this->bootgrid = new ResponseBootgrid();
        $this->setMethod();
        $this->setKey('TiNkUy');
        $this->setIv('TiNkUy');
    }

    /**
     * @return mixed
     */
    private function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    private function setMethod($index=23)
    {
        $methods = openssl_get_cipher_methods();
        $this->method = $methods[$index];
    }

    /**
     * @return string
     */
    private function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    private function setKey($key)
    {
        $key = substr(hash('sha256', trim($key)),0,32);
        $this->key = $key;
    }

    /**
     * @return false|string
     */
    private function getIv()
    {
        return $this->iv;
    }

    /**
     * @param false|string $iv
     */
    private function setIv($iv)
    {
        $len = openssl_cipher_iv_length($this->getMethod());
        $iv        = substr(hash('sha256', $iv), 0, $len);
        $this->iv = $iv;
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
                $sql = "SELECT t.*, concat(%s,t.id) link FROM %s t";
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
                            email             = ?,
                            nombre            = ?, 
                            password          = ?,
                            estado            = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                    ->execute(
                        array(
                            $data['email'],
                            $data['nombre'],
                            $this->encodePass($data['password'], $data['email']),
                            $data['estado'],
                            $data['id']
                        )
                    );
            } else {
                $existeMailActivo=$this->existeMailActivo($data['email']);
                if(!$existeMailActivo){
                    $sql = "INSERT INTO $this->table
                            (email, nombre,password, estado)
                            VALUES (?,?, ?,? )";

                    $this->db->prepare($sql)
                        ->execute(
                            array(
                                $data['email'],
                                $data['nombre'],
                                $this->encodePass($data['password'], $data['email']),
                                0
                            )
                        );
                    $this->response->setResponse(true);
                }else{
                    $this->response->setResponse(15);
                };
            }
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

    private function existeMailActivo($email){
        try {
            $result = array();
            $sql = sprintf("SELECT count(1) cantidad FROM $this->table t WHERE  email = '%s' and estado in (0,1)", $email);

            $stm = $this->db->prepare($sql);
            $stm->execute();

            $result = $stm->fetch();
            if($result->cantidad == 0){
                return false;
            }else{
                return true;
            }
        } catch (Exception $e) {
            return true;
        }
    }

    private function encodePass($pass, $user){
        $this->setKey($user);
        $hash = openssl_encrypt($pass, $this->getMethod(), $this->getKey(),0,$this->getIv());
        //echo $hash,'<pre>',$this->getMethod(),'<pre>', $this->getKey(),'<pre>',$this->getIv();
        return $hash;
    }

    public function decodePass($pass, string $user){
        $this->setKey($user);
        $hash=openssl_decrypt($pass, $this->getMethod(), $this->getKey(),0, $this->getIv());
        //echo $hash,'<pre>',$this->getMethod(),'<pre>', $this->getKey(),'<pre>',$this->getIv();die();
        return $hash;
    }

    public function loginUsuario($usuario, $pass){
        try {
            $result = array();
            $sql = sprintf("SELECT id, nombre,email,password,estado FROM $this->table t WHERE email = '%s'", $usuario);
            $stm = $this->db->prepare($sql);
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return$this->response->result;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

    public function Activar($usuario){
        try {
            $result = array();
            $sql = sprintf("update $this->table set estado=1 WHERE id = %d and estado in (0,2) ", $usuario);
            $stm = $this->db->prepare($sql)->execute();
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
    public function Suspender($usuario){
        try {
            $result = array();
            $sql = sprintf("update $this->table set estado=2 WHERE id = %d and estado=1 ", $usuario);
            $stm = $this->db->prepare($sql)->execute();
            $this->response->setResponse(true);
            return $this->response;
        } catch (Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }

}