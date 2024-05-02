<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2> Products</h2>

    <?php 




	try{
		$connection = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=projeto', 'postgres','root');
		if($connection){
			echo "database conectado";
		}
	}catch (PDOException $e){
		// report error message
		echo $e->getMessage();
	}



    $result = $connection->query("SELECT * FROM produto");
    	//echo $result;
    if(!$result){
        echo "Erro na consulta. <br>";
        exit;
    }
    ?>

    <table>
        <tr>
            <th>id</th>
            <th>nome</th>
            <th>descrição</th>
            <th>estado</th>
        </tr>
   

       <?php 
            foreach($result as $row){          
               echo "        
               <tr>
                    <th>$row[id]</th>
                     <th>$row[nome]</th>
                    <th>$row[descricao]</th>
                   <th>$row[estado]</th>
                </tr>
              ";
           }
      ?>
    </table>
</body>
</html>