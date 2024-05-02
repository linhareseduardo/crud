<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn-edit, .btn-delete {
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
        }
        .btn-edit {
            background-color: #007bff;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 50px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Products</h1>
        <?php
        try {
            $connection = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=projeto', 'postgres', 'root');
            if ($connection) {
                echo "Database connected";
            }
        } catch (PDOException $e) {
            // report error message
            echo $e->getMessage();
        }

        $result = $connection->query("SELECT * FROM estabelecimento");
        if (!$result) {
            echo "Erro na consulta. <br>";
            exit;
        }
        ?>

        <table id="productTable">
            <tr>
                <th>id</th>
                <th>cnpj</th>
                <th>razaosocial</th>
                <th>nomefantasia</th>
                <th>Actions</th>
            </tr>
       
           <?php 
                foreach($result as $row) {          
                   echo "        
                   <tr>
                        <td>{$row['id']}</td>
                        <td>{$row['cnpj']}</td>
                        <td>{$row['razaosocial']}</td>
                        <td>{$row['nomefantasia']}</td>
                        <td>
                            <button class='btn-edit' onclick='openEditModal({$row['id']}, \"{$row['cnpj']}\", \"{$row['razaosocial']}\", \"{$row['nomefantasia']}\")'>Editar</button>
                            <button class='btn-delete' onclick='deleteProduct({$row['id']})'>Excluir</button>
                        </td>
                    </tr>
                  ";
               }
          ?>
        </table>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Product</h2>
            <form id="editForm" onsubmit="return updateProduct()">
                <label for="editId" style="display:none;">ID:</label>
                <input type="hidden" id="editId" name="editId">
                <label for="editCnpj">CNPJ:</label>
                <input type="text" id="editCnpj" name="editCnpj" required><br><br>
                <label for="editRazaoSocial">Razão Social:</label>
                <input type="text" id="editRazaoSocial" name="editRazaoSocial" required><br><br>
                <label for="editNomeFantasia">Nome Fantasia:</label>
                <input type="text" id="editNomeFantasia" name="editNomeFantasia" required><br><br>
                <input type="submit" value="Atualizar">
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, cnpj, razaoSocial, nomeFantasia) {
            document.getElementById('editId').value = id;
            document.getElementById('editCnpj').value = cnpj;
            document.getElementById('editRazaoSocial').value = razaoSocial;
            document.getElementById('editNomeFantasia').value = nomeFantasia;

            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function updateProduct() {
            var editId = document.getElementById('editId').value;
            var editCnpj = document.getElementById('editCnpj').value;
            var editRazaoSocial = document.getElementById('editRazaoSocial').value;
            var editNomeFantasia = document.getElementById('editNomeFantasia').value;

            // Requisição AJAX para atualizar o produto no banco de dados
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'refazendo.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert('Produto atualizado com sucesso!');
                    closeEditModal();
                    loadProducts();
                } else if (xhr.readyState === 4 && xhr.status !== 200) {
                    alert('Erro ao atualizar o produto.');
                }
            };
            xhr.send('id=' + editId + '&cnpj=' + editCnpj + '&razaoSocial=' + editRazaoSocial + '&nomeFantasia=' + editNomeFantasia);

            return false; // Impede o envio do formulário tradicional
        }

        function deleteProduct(id) {
            var confirmation = confirm('Tem certeza que deseja excluir este produto?');
            if (confirmation) {
                // Requisição AJAX para excluir o produto do banco de dados
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_product.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert('Produto excluído com sucesso!');
                        loadProducts();
                    } else if (xhr.readyState === 4 && xhr.status !== 200) {
                        alert('Erro ao excluir o produto.');
                    }
                };
                xhr.send('id=' + id);
            }
        }

        function loadProducts() {
            // Implementação da função para carregar os produtos novamente após uma exclusão
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'load_products.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('productTable').innerHTML = xhr.responseText;
                } else if (xhr.readyState === 4 && xhr.status !== 200) {
                    alert('Erro ao carregar os produtos.');
                }
            };
            xhr.send();
        }

        // Carregar os produtos ao carregar a página
        window.onload = function () {
            loadProducts();
        };
    </script>
</body>
</html>
