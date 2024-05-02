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



    $result = $connection->query("SELECT * FROM estabelecimento");
    	//echo $result;
    if(!$result){
        echo "Erro na consulta. <br>";
        exit;
    }
    ?>

    <table>
        <tr>
            <th>id</th>
            <th>cnpj</th>
            <th>razaosocial</th>
            <th>nomefantasia</th>
        </tr>
   

       <?php 
            foreach($result as $row){          
               echo "        
               <tr>
                    <th>$row[id]</th>
                     <th>$row[cnpj]</th>
                    <th>$row[razaosocial]</th>
                   <th>$row[nomefantasia]</th>
                </tr>
              ";
           }
      ?>
    </table>
</body>
</html>

	<?php
//		try{
//		$conn = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres','root');
//			echo "database conectado";
//			}
//			// report error message
//		}
//	?>
