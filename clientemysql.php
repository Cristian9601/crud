<?php
require_once("nusoap.php");
$namespace = "http://localhost/SERVER";
$serverScript = 'servermysql.php';
$metodoALlamar = $_POST['funcion'];
$client = new nusoap_client("$namespace/$serverScript?wsdl", 'wsdl');

if($metodoALlamar == 'creaContacto') {
    $result = $client->call(
        "$metodoALlamar", 
        array('nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'], 
                'direccion' => $_POST['direccion']),
        "uri:$namespace/$serverScript", 
        "uri:$namespace/$serverScript/$metodoALlamar" 
    );
} 
if($metodoALlamar == 'buscarContacto') {
//echo $_POST['nombre'].$metodoALlamar;
    $result = $client->call(
        "$metodoALlamar", 
        array('nombre' => $_POST['nombre']), 
        "uri:$namespace/$serverScript", 
        "uri:$namespace/$serverScript/$metodoALlamar" 
    );
} 
if($metodoALlamar == 'mostrarTodosContactos') {  
    $result = $client->call(
        "$metodoALlamar", 
        array(), 
        "uri:$namespace/$serverScript", 
        "uri:$namespace/$serverScript/$metodoALlamar" 
    );
}
if ($metodoALlamar == 'actualizarContacto') {
    $result = $client->call(
        "$metodoALlamar", 
        array(
            'id' => $_POST['id'],
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'direccion' => $_POST['direccion']
        ),
        "uri:$namespace/$serverScript", 
        "uri:$namespace/$serverScript/$metodoALlamar" 
    );
}
if ($metodoALlamar == 'eliminarContacto') {
    $result = $client->call(
        "$metodoALlamar", 
        array('id' => $_POST['id']), 
        "uri:$namespace/$serverScript", 
        "uri:$namespace/$serverScript/$metodoALlamar" 
    );
}


echo $result."<br><br><a href='crud.html'>Volver a formulario</a>";
?>
