<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $id = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    
} else {
    header('Location: textoLibre.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}
$text = "";
if(isset($_POST['descripcion']))
{
    
// API Key y URL de Watson NLU
$api_key = 'VrU_gmTm5vhKYUYhFmPDEAFWH7o9__gY5tZJBe772vlN';
$url = 'https://api.us-south.natural-language-understanding.watson.cloud.ibm.com/instances/b778e756-dbcd-4b40-9a35-dafe168bc665/v1/analyze?version=2021-08-01';

// Texto que deseas analizar
$text = $_POST['descripcion'];

// Configurar los datos de la solicitud
$data = array(
    'text' => $text,
    'features' => array(
        'keywords' => array(
            'emotion' => false,
            'sentiment' => false,
            'limit' => 20
        )
    )
);


// Convertir los datos a JSON
$data_json = json_encode($data);

// Iniciar CURL
$ch = curl_init();

// Función para obtener el token IAM
function getIamToken($api_key) {
    $iam_url = 'https://iam.cloud.ibm.com/identity/token';
    $iam_data = 'grant_type=urn:ibm:params:oauth:grant-type:apikey&apikey=' . $api_key;

    // Iniciar CURL para obtener el token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $iam_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $iam_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded'
    ));

    // Ejecutar la solicitud para obtener el token IAM
    $iam_response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error al obtener el token: ' . curl_error($ch);
        return null;
    }

    // Decodificar la respuesta para obtener el token
    $iam_response_data = json_decode($iam_response, true);

    // Cerrar CURL
    curl_close($ch);

    // Retornar el token de acceso
    return $iam_response_data['access_token'];
}

// Configurar las opciones de CURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . getIamToken($api_key)
));

// Ejecutar la solicitud
$response = curl_exec($ch);

// Verificar si hubo errores
if (curl_errno($ch)) {
    echo 'Error en la solicitud: ' . curl_error($ch);
} else {

    function quitarAcentos($texto) {
        // Convierte caracteres acentuados a su equivalente sin acento
        $sinAcentos = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
        return str_replace("'", "", $sinAcentos);
    }

    // Crear un array para almacenar las categorías y palabras
    $categorias = array();

    // Consulta para obtener todas las palabras clave
    $sqlBoda = "SELECT categoria, palabra FROM palabrasclaves where categoria in (
        SELECT elemento FROM elementosboda where evento = $idBoda
        )";
    $queryBoda = $Conexion->query($sqlBoda);

    // Recorrer los resultados y agrupar por categoría
    while ($row = $queryBoda->fetch_assoc()) {
        // Agregar la palabra a la categoría correspondiente
        $categorias[$row['categoria']][] = $row['palabra'];
    }

    // Decodificar la respuesta
    $response_data = json_decode($response, true);
    $palabrasEncontradas = [];


    if (isset($response_data['keywords'])) {
        if (isset($response_data['keywords'])) {
            foreach ($response_data['keywords'] as $keyword) {
                // Convertir a minúsculas para la comparación
                $keywordText = $keyword['text']; 
                // Verificar coincidencias en las categorías
                foreach ($categorias as $categoria => $subcategorias) {
                    foreach ($subcategorias as $subcategoria) {
                        // Comprobar si la subcategoría está en la palabra clave encontrada
                        if (stripos(strtolower(quitarAcentos($keywordText)), strtolower(quitarAcentos($subcategoria))) !== false) {
                            // Guardar la coincidencia en el array asociativo
                            $palabrasEncontradas[$categoria] = $subcategoria;
                        }
                    }
                }
            }
        }
    }
    

    /* Mostrar resultados
    if (!empty($palabrasEncontradas)) {
        foreach ($palabrasEncontradas as $categoria => $subcategoria) {
            echo "<br>$categoria = $subcategoria<br>";
        }
    } else {
        echo "No se encontraron palabras clave.";
    }
    */
    // Comparar categorías
    $faltanCategorias = array_diff_key($categorias, $palabrasEncontradas);

    // Mostrar categorías que faltan
    if (!empty($faltanCategorias)) {
        $alerta = "Te falto describir algunas categorias, al pensar en tu boda como vizualizas:\n";
        foreach ($faltanCategorias as $categoria => $opciones) {
            $alerta = $alerta . "Categoria: " . $categoria . "\npuedes elegir entre: ";  // Imprime el nombre de la categoría
            foreach ($opciones as $palabra) {
                $alerta = $alerta . $palabra . ", "; // Imprime cada palabra clave de la categoría
            }
            $alerta = $alerta . "\n";
        }
        echo "<script type='text/javascript'>alert(" . json_encode($alerta) . ");</script>";
    }else{
        foreach ($palabrasEncontradas as $categoria => $subcategoria) {
            $error = false;
            // Actualiza la descripción del elemento en la base de datos
            $sqlUpdate = "UPDATE elementosboda SET descripcion = '$subcategoria' 
            WHERE usuario = '$id' AND evento = '$idBoda' AND elemento = '$categoria'";
            $queryUpdate = $Conexion->query($sqlUpdate);
            if ($queryUpdate) {
                $error = false;
            }
            else {
                $error = true;
            }
        }
        if (!$error) {
            header("Location: panelGeneral.php?idUsuario=$id&idBoda=$idBoda");
            exit();
        } else {
            echo "Error al actualizar el elemento: $categoria - " . $Conexion->error . "<br>";
        }
    }


    
}

// Cerrar CURL
curl_close($ch);



}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Nuevo evento</title>
    
</head>
<body>

<nav class="navbar navbar-complex navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="title_nav">
            <img src="../Imagenes/Wedding planner.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
            <span>Perfect Wedding</span>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $id; ?>">Calendario</a>
                <a class="nav-item nav-link" href="conversaciones.php?id=<?php echo $id; ?>">Tabla kanban</a>
                <a class="nav-item nav-link" href="invitados.php?idUsuario=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>">Lista invitados</a>
                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Tableros
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Tablero general</a></li>
                            <li><a class="dropdown-item" href="#">Tableros favoritos</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                        </li>
                    </ul>
                </div>
                <a class="navbar-brand" href="infoPerfil.php?id=<?php echo $id; ?>">
                    <img src="../Imagenes/Perfil.png" alt="Perfil" width="30" height="30">
                </a>
            </div>
        </div>
    </div>
</nav>
<br><h3>Describe tu boda</h3>
<form action="textoLibre.php?idUsuario=<?php echo $id;?>&idBoda=<?php echo $idBoda; ?>" method="post">

    <div style="text-align: right; margin-top: 20px; padding-right: 10px;">
        <button type="submit" class="btn btn-lila">Guardar</button>
        <a type="button" class="btn btn-rosa" href="descripcionElementos.php?idUsuario=<?php echo $id;?>&idBoda=<?php echo $idBoda; ?>">Formulario</a>
    </div>
    <br>
    <div style="padding: 30px">

        <div class="form-floating">
        <textarea class="form-control" name="descripcion" placeholder="Describe tu boda" id="floatingTextarea2" style="height: 290px" required><?php echo htmlspecialchars($text); ?></textarea>
        <label for="floatingTextarea2">Como te imaginas tu boda</label>
        </div>

    </div>
    
</form>
</body>
</html>