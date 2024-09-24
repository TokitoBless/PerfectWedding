<?php
include_once('../Conexion/conexion.php');

if (isset($_GET['idUsuario']) && isset($_GET['idBoda'])) {
    $id = $_GET['idUsuario'];
    $idBoda = $_GET['idBoda'];
    $sqlUsuario = "SELECT * FROM usuarios WHERE id = '$id'";
    $queryUsuario = $Conexion->query($sqlUsuario);
    $row = mysqli_fetch_row($queryUsuario);
    $usuario = $row[2];
    $sqlInvitados = "SELECT id, nombreCompleto, invitacion, estatus, ultimaModificacion, invitadoDe FROM invitados WHERE idEvento = '$idBoda'";
    $queryInvitados = $Conexion->query($sqlInvitados);
} else {
    header('Location: invitados.php?error="No se proporcionó ID de usuario ni de boda"');
    exit();
}

//Agregar invitado
if (isset($_POST['nombreCompleto']) && isset($_POST['invitadoDe'])) {
    function validar($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $NombreCompleto = validar($_POST['nombreCompleto']);
    $invitadoDe = $_POST['invitadoDe'];

    $sqlVerificarInvitado = "SELECT * FROM invitados WHERE nombreCompleto = '$NombreCompleto'";
    $queryVeriInvitado = $Conexion->query($sqlVerificarInvitado);
    
    if(mysqli_num_rows($queryVeriInvitado) > 0){
        echo '<script language="javascript">alert("Ese invitado ya fue ingresado");</script>';
    }else {
        $sqlAgregarInvitado = "INSERT INTO invitados(idEvento, nombreCompleto, ultimaModificacion, invitadoDe) VALUES ('$idBoda', '$NombreCompleto', '$usuario', '$invitadoDe')";
        $queryAgregarInvitado = $Conexion->query($sqlAgregarInvitado);
        if ($queryAgregarInvitado) {
            echo '<script language="javascript">alert("Invitado agregado");</script>';
            header("Refresh:0");
        }else{
            echo "Algo malio sal";
        }
    }
}
//Eliminar invitado
if (isset($_GET['delete'])) {
    $idInvitado = $_GET['delete'];
    $sqlEliminar = "DELETE FROM invitados WHERE id = '$idInvitado'";
    if ($Conexion->query($sqlEliminar)) {
        echo "<script>alert('Invitado eliminado con éxito'); window.location='invitados.php?idUsuario=$id&idBoda=$idBoda';</script>";
    } else {
        echo "<script>alert('Error al eliminar el invitado'); window.location='invitados.php?idUsuario=$id&idBoda=$idBoda';</script>";
    }
}

//Cambier el nombresito
if (isset($_POST['update'])) {
    $idInvitado = $_POST['id'];
    $nuevoNombre = $_POST['nombreCompleto'];
    
    $sqlUpdate = "UPDATE invitados SET nombreCompleto = '$nuevoNombre', ultimaModificacion = '$usuario' WHERE id = '$idInvitado'";
        
    if ($Conexion->query($sqlUpdate)) {
        echo "Nombre actualizado";
    } else {
        echo "malio sal " ;
    }
    
    exit();
}

//Cambia la invitacion y la confirmacion
if (isset($_POST['updateStatus'])) {
    $idInvitado = $_POST['id'];
    $invitacion = $_POST['invitacion']; // 1 o 0
    $estatus = $_POST['estatus']; // 1 o 0

    // Escapar valores para evitar inyecciones SQL
    $invitacion = $Conexion->real_escape_string($invitacion);
    $estatus = $Conexion->real_escape_string($estatus);

    $sqlUpdate = "UPDATE invitados SET invitacion = '$invitacion', estatus = '$estatus', ultimaModificacion = '$usuario' WHERE id = '$idInvitado'";
        
    if ($Conexion->query($sqlUpdate)) {
        echo "Estado actualizado";
    } else {
        echo "Error al actualizar el estado";
    }
    
    exit();
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
                <a class="nav-item nav-link" href="panelServicios.php?id=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>">Calendario</a>
                <a class="nav-item nav-link" href="conversaciones.php?id=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>">Tabla kanban</a>
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
<br>
<h3>Lista de invitados</h3>
<br>

<center>

<form class="form-inline" action="invitados.php?idUsuario=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>" method="post">
    <div class="form-floating input-container">
        <input type="text" name="nombreCompleto" pattern="[a-zA-Z ]{2,254}" title="Solo se permiten letras"  class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Nombre completo del invitado</label>
    </div>    

    <div class="form-floating select-container">
        <select name="invitadoDe" id="floatingSelect" class="form-select" required>
            <option selected disabled></option> 
            <option value="Novia">Novia</option> 
            <option value="Novio">Novio</option> 
            <option value="Ambos">Ambos</option> 
        </select>
        <label for="floatingSelect">¿De qué parte va?</label>
    </div>

    <button type="submit" class="btn btn-lila">Agregar</button>
</form>

</center>

<!-- Tabla de invitados -->
<div class="container mt-4">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre completo del invitado</th>
                <th>Invitación enviada</th>
                <th>Estatus de confirmación</th>
                <th>Última modificación por</th>
                <th>Invitado de</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($queryInvitados->num_rows > 0) {
                while ($row = $queryInvitados->fetch_assoc()) {
                    echo "<tr>";
                    //Nombre
                    echo "<td>{$row['nombreCompleto']}
                            <br>
                            <input type='text' id='editInput_{$row['id']}' style='display:none;' placeholder='Nuevo nombre' />
                            <button onclick='confirmEdit({$row['id']})' id='confirmBtn_{$row['id']}' class='btn btn-ch-marilla' style='display:none;'>Confirmar</button>
                    </td>";
                    // Invitación enviada
                    echo "<td>
                    <select onchange='updateStatus({$row['id']}, this.value, null)' class='form-select'>";
                    echo $row['invitacion'] ? "<option value='1' selected>Sí</option><option value='0'>No</option>" : "<option value='1'>Sí</option><option value='0' selected>No</option>";
                    echo "</select></td>";

                    // Estatus de confirmación
                    echo "<td>
                        <select onchange='updateStatus({$row['id']}, null, this.value)' class='form-select'>";
                    echo $row['estatus'] ? "<option value='1' selected>Sí</option><option value='0'>No</option>" : "<option value='1'>Sí</option><option value='0' selected>No</option>";
                    echo "</select></td>";
                    //Estatus e invitado de
                    echo "<td>{$row['ultimaModificacion']}</td>";
                    echo "<td>{$row['invitadoDe']}</td>";
                    //Opciones de eliminar y editar
                    echo "<td>
                            <a href='#' onclick='confirmDelete({$row['id']})'><img src='../Imagenes/eliminar.png' alt='Eliminar' width='20' height='20'></a>
                            <a href='#' onclick='showEditInput({$row['id']})'><img src='../Imagenes/editar.png' alt='Editar' width='20' height='20'></a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay invitados registrados</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(id) {
    if (confirm('¿Estás seguro de que quieres eliminar a este invitado?')) {
        window.location.href = 'invitados.php?idUsuario=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>&delete=' + id;
    }
}

function showEditInput(id) {
    const input = document.getElementById(`editInput_${id}`);
    const confirmBtn = document.getElementById(`confirmBtn_${id}`);
    input.style.display = 'inline';
    confirmBtn.style.display = 'inline';
}

function confirmEdit(id) {
    if (confirm('¿Estás seguro de que quieres editar el nombre de este invitado?')) 
    {
        const newName = document.getElementById(`editInput_${id}`).value;
        if (newName) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'invitados.php?idUsuario=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // Muestra la respuesta
                    setTimeout(() => {
                        location.reload(); // Recarga la página después de la alerta
                    }, 100);
                } else {
                    alert('Error al actualizar el nombre');
                }
            };
            xhr.send(`update=true&id=${id}&nombreCompleto=${encodeURIComponent(newName)}`);
        } else {
            alert('Por favor ingrese un nuevo nombre');
        }
    }
}

function updateStatus(id) {
    const invitacion = document.querySelector(`select[onchange="updateStatus(${id}, this.value, null)"]`).value;
    const estatus = document.querySelector(`select[onchange="updateStatus(${id}, null, this.value)"]`).value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'invitados.php?idUsuario=<?php echo $id; ?>&idBoda=<?php echo $idBoda; ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert(xhr.responseText); // Muestra la respuesta del servidor
            location.reload(); // Recarga la página después de actualizar
        } else {
            alert('Error al actualizar el estado');
        }
    };

    xhr.send(`updateStatus=true&id=${id}&invitacion=${invitacion}&estatus=${estatus}`);
}





</script>

</body>
</html>
