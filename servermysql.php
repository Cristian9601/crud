<?php

    require_once("nusoap.php");
    $namespace = "http://localhost/SERVER";
    $server = new soap_server();
    $server->configureWSDL("WSDLTST", $namespace);
    $server->soap_defencoding = 'UTF-8';
    $server->wsdl->schemaTargetNamespace = $namespace;  
    
      function creaContacto($nombre, $apellido, $direccion){

                require_once("conexion.php");
                $conn = mysqli_connect($servername, $username, $password, $dbname)or die("Error de conexión con la base de datos");
                $sql = "INSERT INTO clientes (nombre,apellido,direccion) VALUES ('".$nombre."', '".$apellido."', '".$direccion."')";
                if (mysqli_query($conn, $sql)) {
                    $msg =  "Se introdujo un nuevo registro en la BD; ".$nombre;
                } else {
                    $msg = "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                mysqli_close($conn);
                return new soapval('return', 'xsd:string', $msg);
        }



       function buscarContacto($nombre) {

        require_once("conexion.php");
        $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM clientes where nombre='".$nombre."'";

                $resultado = mysqli_query($conn, $sql);
               
                $listado = "<table border='1'><tr><td>ID</td><td>nombre</td><td>apellido</td><td>direccion</td></tr>";
                while ($fila = mysqli_fetch_array($resultado)){
                        $listado = $listado."<tr><td>".$fila['id']."</td><td>".$fila['nombre']
                        ."</td><td>".$fila['apellido']."</td><td>".$fila['direccion']."</td></tr>";
                }
                $listado = $listado."</table>";
                mysqli_close($conn);

                
                return new soapval('return', 'xsd:string', $listado);

        }



       function mostrarTodosContactos() {

        require_once("conexion.php");

                $conn = mysqli_connect($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM clientes";

                $resultado = mysqli_query($conn, $sql);
                $listado = "<table border='1'><tr><td>ID</td><td>Nombre</td><td>Apellidos</td><td>Direcci&oacute;n</td></tr>";
                while ($fila = mysqli_fetch_array($resultado)){
                        $listado = $listado."<tr><td>".$fila['id']."</td><td>".$fila['nombre']
                        ."</td><td>".$fila['apellido']."</td><td>".$fila['direccion']."</td></td></tr>";
                }
                $listado = $listado."</table>";
                mysqli_close($conn);

                return  new soapval('return', 'xsd:string', $listado);

        }
        function actualizarContacto($id, $nombre, $apellido, $direccion) {
            require_once("conexion.php");
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            // Check if the contact with the given ID exists
            $checkSql = "SELECT * FROM clientes WHERE id = " . (int)$id;
            $checkResult = mysqli_query($conn, $checkSql);
            
            if (mysqli_num_rows($checkResult) > 0) {
                $updateSql = "UPDATE clientes SET nombre = '$nombre', apellido = '$apellido', direccion = '$direccion' WHERE id = " . (int)$id;
                if (mysqli_query($conn, $updateSql)) {
                    $msg = "Se actualizo el registro en la BD; ID: $id";
                } else {
                    $msg = "Error: " . $updateSql . "<br>" . mysqli_error($conn);
                }
            } else {
                $msg = "No se encontro ningún contacto con el ID: $id";
            }
            
            mysqli_close($conn);
            return new soapval('return', 'xsd:string', $msg);
        }
        function eliminarContacto($id) {
            require_once("conexion.php");
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            // Check if the contact with the given ID exists
            $checkSql = "SELECT * FROM clientes WHERE id = " . (int)$id;
            $checkResult = mysqli_query($conn, $checkSql);
            
            if (mysqli_num_rows($checkResult) > 0) {
                $deleteSql = "DELETE FROM clientes WHERE id = " . (int)$id;
                if (mysqli_query($conn, $deleteSql)) {
                    $msg = "Se elimino el registro de la BD; ID: $id";
                } else {
                    $msg = "Error: " . $deleteSql . "<br>" . mysqli_error($conn);
                }
            } else {
                $msg = "No se encontro ningún contacto con el ID: $id";
            }
            
            mysqli_close($conn);
            return new soapval('return', 'xsd:string', $msg);
        }
        


    $server->register('creaContacto',
        array('nombre'=>'xsd:string','apellido'=>'xsd:string',
            'direccion'=>'xsd:string'),
        array('return'=> 'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'funcion que crea contacto'
        );


    $server->register
    ('mostrarTodosContactos',
        array(),
        array('return' => 'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'funcion que crea muestra los contactos'
        );
   


     $server->register
     ('buscarContacto',
        array('nombre' => 'xsd:string'),
        array('return' => 'xsd:string'),
         $namespace,
        false,
        'rpc',
        'encoded',
        'funcion que crea muestra los contactos'
        );
        $server->register('actualizarContacto',
        array('id' => 'xsd:int', 'nombre' => 'xsd:string', 'apellido' => 'xsd:string', 'direccion' => 'xsd:string'),
        array('return' => 'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'Funcion que actualiza un contacto'
        );

        $server->register('eliminarContacto',
        array('id' => 'xsd:int'),
        array('return' => 'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'Funcion que elimina un contacto'
        );
       

    if ( !isset( $HTTP_RAW_POST_DATA ) ) {
         $HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
    }

    $server->service($HTTP_RAW_POST_DATA);
?>

 

