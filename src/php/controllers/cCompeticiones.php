<?php
    class cCompeticiones {
        public $nombrePagina;
        public $view;
        public $mensaje;
        public $objCompeticiones;

        public function __construct() {
            require_once __DIR__ . '/../models/mCompeticiones.php';

            $this->view = 'vListarCompeticiones';
            $this->nombrePagina ='';
            $this->objCompeticiones = new mCompeticiones();
        }

        public function mostrarAdmin(){
            $this->nombrePagina = 'Menu Administrador';
            $this->view = 'vMostrarMenuAdmin';
        }

        public function listarCompeticiones(){
            $this->mVistaCompeticiones();
            $datos =  $this->objCompeticiones->mListarCompeticiones();
            return $datos;
        }

        public function crearCompeticiones(){
            $this->view = 'vCrearCompeti';
 
            if(isset($_POST["clave"]) && isset($_POST["titulo"]) && isset($_POST["descripcion"]) && isset($_POST["fechaFin"])){
                $clave = $_POST["clave"];
                $titulo = $_POST["titulo"];
                $descripcion = $_POST["descripcion"];
                $fechaFin = $_POST["fechaFin"];

                $resultado = $this->objCompeticiones->mCrearCompeticion($clave, $titulo, $descripcion, $fechaFin);
                if($resultado === true){
                    $this->mensaje = "Se ha creado";
                }else{
                    $this->mensaje = $this->obtenerMensajeError($resultado);
                } 
            }
        }

        public function borrarCompeticiones(){
            $clave = $_GET['clave'];
            $resultado = $this->objCompeticiones->mBorrarCompeticion($clave);
            header("Location: index.php?c=cCompeticiones&m=listarCompeticiones");
            exit();
        }

        function mVistaCompeticiones(){
            $this->view = 'vListarCompeticiones';
            $this->nombrePagina = 'Listar Competiciones';
        }

        function cObtenerDatosCompeticiones(){
            $clave = $_GET["clave"];

            $this->view = 'vModifCompeti';
            $this->nombrePagina = 'Modificar Competiciones';

            $datos = $this->objCompeticiones->mObtenerCompeticion($clave);
            return $datos;
        }
        
        public function cModifCompeticiones(){
            if (isset($_POST['clave']) && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['fechaFin'])) {
                $clave = $_POST['clave'];
                $titulo = $_POST['titulo'];
                $descripcion = $_POST['descripcion'];
                $fechaFin = $_POST['fechaFin'];
        
                $resultado = $this->objCompeticiones->mModifCompeticion($clave, $titulo, $descripcion, $fechaFin);
                
                if ($resultado === true) {
                    header("Location: index.php?c=cCompeticiones&m=listarCompeticiones");
                    exit();
                } else {
                    $this->mensaje = $this->obtenerMensajeError($resultado);
                    $this->mostrarErrorDeModificacion();
                }
            }
        }
        
        private function obtenerMensajeError($codigoError) {
            switch ($codigoError) {
                case '1292':
                    return "Valor de fecha y hora incorrecto";
                case '1406':
                    return "Algun dato excede la longitud máxima permitida";
                case '1062':
                    return "La clave ya existe, intentalo con otra";
                case '1048':
                    return "La clave o el título no puede estar vacía";
                default:
                    if (is_numeric($codigoError)) {
                        return "Error al modificar competición. Código de error: $codigoError";
                    } else {
                        return $codigoError;
                    }
                    break;
            }
        }

        public function mostrarErrorDeModificacion() {
            $this->view = 'vError'; 
            $mensajeError = $this->mensaje; 
            return $mensajeError;
        }
    }
?>
