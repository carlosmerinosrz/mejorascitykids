<?php
require_once __DIR__ . '/../models/conexion.php';

class mCompeticiones extends Conexion{

    public function __construct(){
        parent::__construct();
    }

    public function mListarCompeticiones() {
        $sql = "SELECT * FROM Competicion";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();
        $datos = [];
    
        $result = $consulta->get_result();
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        $consulta->close();
    
        return $datos;
    }
    
    public function mCrearCompeticion($clave, $titulo, $descripcion, $fechaFin) {
        try {
            // Convertir valores vacíos a NULL
            $clave = ($clave === '') ? NULL : $clave;
            $descripcion = ($descripcion === '') ? NULL : $descripcion;
            $titulo = ($titulo === '') ? NULL : $titulo;
            
            $sql = "INSERT INTO Competicion (clave, descripcion, titulo, fecha_hora_fin) VALUES (?, ?, ?, ?)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bind_param('ssss', $clave, $descripcion, $titulo, $fechaFin);
            $consulta->execute();
    
            if ($consulta->affected_rows > 0) {
                $consulta->close();
                return true;
            } else {
                throw new Exception($consulta->error, $consulta->errno);
            }
        } catch (Exception $error) {
            $numeroError = $error->getCode();
            return $numeroError;
        }
    }
    
     public function mBorrarCompeticion($clave){
        $sql = "DELETE FROM Competicion WHERE clave = '$clave'";
        $this->conexion->query($sql); 
    }

    function mModifCompeticion($clave, $titulo, $descripcion, $fechaFin){
        try {
            $descripcion = ($descripcion === '') ? NULL : $descripcion;
            $titulo = ($titulo === '') ? NULL : $titulo;
            $sql = "UPDATE Competicion SET titulo = ?, descripcion = ?, fecha_hora_fin = ? WHERE clave = ?";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bind_param("ssss", $titulo, $descripcion, $fechaFin, $clave);
    
            if ($consulta->execute()) {
                $consulta->close();
                return true;
            } else {
                throw new Exception($consulta->error, $consulta->errno);
            }
        } catch (Exception $error) {
            $numeroError = $error->getCode();
            return $numeroError;
        }
    }
      
    function mObtenerCompeticion($clave){
        $sql = "SELECT * FROM Competicion WHERE clave = ?";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bind_param('s',$clave);
        $consulta->execute();

        $consulta = $consulta->get_result();

        if ($consulta->num_rows > 0) {
            $fila = $consulta->fetch_assoc();
            return $fila;
        } else {
            return null;
        }
    }

}
?>