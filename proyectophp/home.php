<?php
// Inicia la sesión
session_start();


if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
// Destruye todas las variables de sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
      h1 {
        display: flex;
        justify-content: center;
        }
        
      p {
        display: flex;
        justify-content: center;
        }
      
    </style>
</head>

<body>
   

  <div class="container">
    <h1>Bienvenido, <?php echo isset($_SESSION["user"]) ? $_SESSION["user"] : "Usuario"; ?></h1>
    <p>Tu correo electrónico registrado es: <?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : "Correo electrónico"; ?></p>
    <br><br>

    <?php
      $cod = null;
      $nom = null;
      $prc = null;
      $can = null;
        
      if(isset($_GET["cod"])){
        $cod = $_GET["cod"];
        $nom = $_GET["nom"];
        $prc = $_GET["prc"];
        $can = $_GET["can"];
        
      }
    
    ?>
    <form action="home.php" method="post">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="user" class="col-form-label">Codigo</label>
            <input type="number" name="cod" id="cod" class="form-control" value="<?php echo $cod; ?>" required>
        </div>
        <div class="col-auto">
            <label for="user" class="col-form-label">Nombre</label>
            <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $nom; ?>" required>
        </div>
        <div class="col-auto">
            <label for="user" class="col-form-label">Precio</label>
            <input type="number" name="prc" id="prc" class="form-control" value="<?php echo $prc; ?>" required>
        </div>
        <div class="col-auto">
            <label for="user" class="col-form-label">Cantidad</label>
            <input type="number" name="can" id="can" class="form-control" value="<?php echo $can; ?>" required>
        </div>

        <div class="col-auto">
            <label for="crear" class="col-form-label"></label><br>
            <button type="submit" class="btn btn-success" name="crear" id="crear"><i class="bi bi-plus-circle"></i> CREAR </button>
        </div>

        <div class="col-auto">
          <input type="hidden" name="cod_org" value="<?php echo $cod; ?>">
            <label for="actualizar" class="col-form-label"></label><br>
            <button type="submit" class="btn btn-primary" name="actualizar" id="actualizar"><i class="bi bi-arrow-clockwise"></i> ACTUALIZAR</button>
        </div>
          
      </div>
    </form>
    <br>

    <?php
        
      include_once "./conexion.php";

        if(isset($_POST["crear"])){
          $sql = "INSERT INTO producto (codigo, nombre, precio, cantidad ) VALUES ('".$_POST["cod"]."', '".$_POST["nom"]."',  '".$_POST["prc"]."','".$_POST["can"]."')";
          $conn->query($sql);
      }

      if(isset($_POST["actualizar"])){

        $sql = "SELECT * FROM producto WHERE codigo !='".$_POST["cod_org"]."' AND nombre= '".$_POST["nom"]."' ";
        $res = $conn->query($sql);


        if($res->num_rows == 0){
          $sql ="UPDATE producto SET codigo= '".$_POST["cod"]."', nombre='".$_POST["nom"]."', precio='".$_POST["prc"]."', cantidad='".$_POST["can"]. "' WHERE codigo = '".$_POST["cod_org"]."' ";
          $conn->query($sql);
        }else{
          echo '
            <script>
              alert("YA EXISTE UN PRODUCTO CON EL MISMO NOMBRE, ERROR...");


            </script>
          
          ';
        }
      }

      $sql = "SELECT * FROM producto ";
      $res = $conn->query($sql);

      echo '
      <table class="table table-striped">
        <tr>
          <th>codigo</th>
          <th>nombre</th>
          <th>precio</th>
          <th>cantidad</th>
          <th class="text-center">Actualizar</th>
          <th class="text-center">Eliminar</th>
        </tr>
      ';

      while($row = $res->fetch_array()){
      echo '
        <tr>
          <td>'.$row[0].'  </td>
          <td>'.$row[1].'  </td>
          <td>$'.$row[2].' </td>
          <td>'.$row[3].'  </td>
          <td class="text-center"><a href="home.php?cod='.$row[0].'&nom='.$row[1].'&prc='.$row[2].'&can='.$row[3].'"><i class="bi bi-arrow-clockwise"></i></a> </td>
          ';
          ?>
          <td class="text-center"><a href="home.php?cod_elm=<?php echo $row[0]; ?>" onclick="return confirm('¿Realmente deseas eliminar este elemento?')"><i class="bi bi-trash-fill"></i></a> </td>
          <?php
          echo '
        </tr>
        ';
      }

        if(isset($_GET["cod_elm"])){
          $sql = "DELETE FROM producto WHERE codigo= '".$_GET["cod_elm"]."'";
          $conn->query($sql);
        }
        echo '</table>';
      ?>
  </div>
  <br>
  <br>
  

      <div class="d-flex justify-content-center">
          <button id="cerrarSesion" class="btn btn-danger me-3">Cerrar sesión</button>
          <div id="confirmacionCerrarSesion" style="display: none;">
              ¿Estás seguro?
              <button id="confirmarCerrarSesion" class="btn btn-danger">Confirmar</button>
              <button id="cancelarCerrarSesion" class="btn btn-primary">Cancelar</button>
          </div>
          
      </div>
  </div>
        
  <script src="https://cdn.jsdelivr.net/npm/react/umd/react.production.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/react-dom/umd/react-dom.production.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.26.0/babel.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script type="text/babel">
      const confirmarCerrarSesion = () => {
          const confirmacion = window.confirm("¿Estás seguro?");
          if (confirmacion) {
              // Aquí puedes redirigir a tu script de cerrar sesión
              window.location.href = "index.php";
          }
      }

      document.getElementById("cerrarSesion").addEventListener("click", () => {
          document.getElementById("confirmacionCerrarSesion").style.display = "block";
      });

      document.getElementById("confirmarCerrarSesion").addEventListener("click", confirmarCerrarSesion);
      document.getElementById("cancelarCerrarSesion").addEventListener("click", () => {
          document.getElementById("confirmacionCerrarSesion").style.display = "none";
      });
  </script>
</body>

</html>
