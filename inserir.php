<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insert Products</title>
</head>
<body>
  <h2>Insert New Product</h2>

  <?php
  $connection = pg_connect("host = localhost dbname = projeto user = postgres password = root");

  if (!$connection) {
    echo "Erro ao conectar ao banco de dados. <br>";
    exit;
  }

  // Form to capture product data
  echo '<form method="post" action="">';
  echo '<label for="cnpj">CNPJ:</label>';
  echo '<input type="text" id="cnpj" name="cnpj" required><br><br>';
  echo '<label for="razaosocial">Razão Social:</label>';
  echo '<input type="text" id="razaosocial" name="razaosocial" required><br><br>';
  echo '<label for="nomefantasia">Nome Fantasia:</label>';
  echo '<input type="text" id="nomefantasia" name="nomefantasia" required><br><br>';
  echo '<input type="submit" value="Cadastrar">';
  echo '</form>';

  // Handle form submission and insert data
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnpj = pg_escape_string($connection, $_POST['cnpj']); // Escape for security
    $razaosocial = pg_escape_string($connection, $_POST['razaosocial']); // Escape for security
    $nomefantasia = pg_escape_string($connection, $_POST['nomefantasia']); // Escape for security

    $query = "INSERT INTO estabelecimento (cnpj, razaosocial, nomefantasia) VALUES ('$cnpj', '$razaosocial', '$nomefantasia')";

    $result = pg_query($connection, $query);

    if ($result) {
      echo "<p style='color: green;'>Produto inserido com sucesso!</p>";
    } else {
      echo "<p style='color: red;'>Erro ao inserir produto. Verifique os dados.</p>";
      // Consider logging the error for debugging
    }
  }

  pg_close($connection); // Close the connection after use
  ?>
</body>
</html>
