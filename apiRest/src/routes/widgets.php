<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
$app->add(new Tuupola\Middleware\CorsMiddleware);
$app = new \Slim\App;
ini_set('memory_limit', '128M');


// GET Todos los IFRAMES Y WIDGETS ASIGNADOS
$app->get('/api/iframe', function(Request $request, Response $response){
  $sql = "SELECT *  FROM IFRAME INNER JOIN WIDGET INNER JOIN WIDGET_TYPE ON IFRAME.ID_IFRAME_PROPERTIES = WIDGET.ID_WIDGET AND WIDGET.TYPE_WIDGET = WIDGET_TYPE.ID_WIDGET_TYPE;";
  try{
    $db = new db();
    $db = $db->connectDB();
    $resultado = $db->query($sql);
  
    if ($resultado->rowCount() > 0){
      
      $iframe = $resultado->fetchAll(PDO::FETCH_OBJ);
     
      $max = count($iframe)-1;
        
      foreach($iframe as $row){
        $str = $row->IFRAME_PROPERTIES;
        $str =preg_replace('/\\\"/',"\"", $str);
        $iframe_prop[] = Array("PROPERTIES" => $str,
                                "ID" => $row->ID_IFRAME_PROPERTIES                          
      );
          
      }
      echo json_encode($iframe);
      
 
    }else {
      echo json_encode("No existen widgets en la DB.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
  return $iframe;
}); 


// GET recuperar un Iframe por ID y la información de su Widget asignado

$app->get('/api/widget/{id}', function(Request $request, Response $response){
  $id_widget = $request->getAttribute('id');
  $sql = "SELECT * FROM IFRAME INNER JOIN WIDGET ON IFRAME.ID_IFRAME_PROPERTIES = WIDGET.ID_WIDGET WHERE IFRAME.ID_IFRAME_PROPERTIES = $id_widget";
  try{
    $db = new db();
    $db = $db->connectDB();
    $result = $db->query($sql);

    if ($result->rowCount() > 0){
      $widget = $result->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($widget);
    }else {
      echo json_encode("No existe el widget en la DB.");
    }
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

//GET Recuperar un widget por su ID
$app->get('/api/slider/{id}', function(Request $request, Response $response){
  $id_widget = $request->getAttribute('id');
  $sql = "SELECT WIDGET_DATA  FROM widget WHERE ID_WIDGET = $id_widget";
  try{
    $db = new db();
    $db = $db->connectDB();
    $result = $db->query($sql);

    if ($result->rowCount() > 0){
      $widget = $result->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($widget);
    }else {
      echo json_encode("No existe el widget en la DB.");
    }
    $result = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});



// CREAR NUEVO WIDGET

$app->post('/api/widget/nuevo', function(Request $request, Response $response){
  $Name = $request->getParam('Name');
  $Type = $request->getParam('Type');
  $data = $request->getParam('src');

  $properties=json_encode( ['data'=> $data ]);
  
  
 
 $sql = "INSERT INTO widget (WIDGET_NAME, TYPE_WIDGET, WIDGET_DATA) VALUES (:name_widget, :type_widget, :properties)";
 try{
   $db = new db();
   $db = $db->connectDB();
   $result = $db->prepare($sql);


   $result->bindParam(':type_widget', $Type);
   $result->bindParam(':name_widget', $Name);
   $result->bindParam(':properties', $properties);
   $db->beginTransaction(); 
   $result->execute();
   $id_widget = print $db->lastInsertId();
   $db->commit(); 
   $result = null;
   $db = null;
 }catch(PDOException $e){
   echo '{"error" : {"text":'.$e->getMessage().'}';
 }
}); 


//POST, CREAR UN NUEVO IFRAME


$app->post('/api/iframe/nuevo/{id}', function(Request $request, Response $response){
  $id_widget = $request->getAttribute('id');
  $Height = $request->getParam('Height');
  $Width = $request->getParam('Width');
  $Border = $request->getParam('Border');
  $Scrolling = $request->getParam('Scrolling');
  $src = $request->getParam('src');

  $properties=json_encode( ['Height'=> $Height, 'Width' => $Width,'Border'=> $Border,
  'Scrolling'=> $Scrolling,'src'=> $src ]);
  

 
 $sql = "INSERT INTO IFRAME (IFRAME_PROPERTIES,  WIDGET_ASSIGNATED) VALUES (:properties, :id_widget)";
 try{
      $db = new db();
      $db = $db->connectDB();
      $result = $db->prepare($sql);

      $result->bindParam(':properties', $properties);
      $result->bindParam(':id_widget', $id_widget);
      $result->execute();
      echo json_encode("Nuevo widget guardado.");  

      $result = null;
      $db = null;
 }catch(PDOException $e){
   echo '{"error" : {"text":'.$e->getMessage().'}';
 }
}); 


// MODIFICAR Widget

$app->put('/api/widget/modificar/{id}', function(Request $request, Response $response){
  $id_widget = $request->getAttribute('id');
  $nombre = $request->getParam('Name');
  
 $sql = "UPDATE WIDGET  SET WIDGET_NAME = :nombre
  WHERE ID_WIDGET = $id_widget";
 try{
      $db = new db();
      $db = $db->connectDB();
      $result = $db->prepare($sql);

      $result->bindParam(':nombre', $nombre);
    

      $result->execute();
      echo json_encode("widget editado y guardado.");  

      $result = null;
      $db = null;
 }catch(PDOException $e){
   echo '{"error" : {"text":'.$e->getMessage().'}';
 }
}); 

// MODIFICAR PROPIEDADES DEL IFRAME 

$app ->put('/api/iframe/modificar/{id}', function(Request $request, Response $response){
  $id_iframe = $request->getAttribute('id');
  $Height = $request->getParam('Height');
  $Width = $request->getParam('Width');
  $Border = $request->getParam('Border');
  $Scrolling = $request->getParam('Scrolling');

  $properties=json_encode( ['Height'=> $Height, 'Width' => $Width,'Border'=> $Border,
  'Scrolling'=> $Scrolling]);

   
 $sql = "UPDATE IFRAME SET IFRAME_PROPERTIES =:properties WHERE ID_IFRAME_PROPERTIES = :id_iframe";
 try{
      $db = new db();
      $db = $db->connectDB();
      $result = $db->prepare($sql);

      $result->bindParam(':properties', $properties);
      $result->bindParam(':id_iframe', $id_iframe);
      $result->execute();
      echo json_encode(" Iframe modificado.");  

      $result = null;
      $db = null;
 }catch(PDOException $e){
   echo '{"error" : {"text":'.$e->getMessage().'}';
 }
});

//ELIMINAR WIDGET

$app->delete('/api/widget/eliminar/{id}', function(Request $request, Response $response){
  $id_widget = $request->getAttribute('id');

  $sql = "DELETE FROM IFRAME, WIDGET USING IFRAME INNER JOIN WIDGET ON IFRAME.ID_IFRAME_PROPERTIES = WIDGET.ID_WIDGET WHERE IFRAME.ID_IFRAME_PROPERTIES = :id;
  ";

  try{  
    $db = new db();
    $db = $db -> connectDB();
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id_widget);
    $result->execute();
    if($result->rowCount() > 0){
      echo json_encode("Widget eliminado.");  
    }else {
      echo json_encode("No existe el widget con este ID.");
    }

    $result = null;
    $db = null;
  }catch(PDOException $e){
        echo '{"error": {"text":'.$e->getMessage().'}';
  }
});

//subir imagen

$app->post('/api/widget/subir-imagen/{id}',function(Request $request, Response $response){
      $id_widget = $request->getAttribute('id');
          
      $directory = '../src/uploads';
     
      $Files = $request->getUploadedFiles();
      $uploadedFiles = $Files['file0'];
      $i = 0;


  foreach($uploadedFiles as $uploadedFile){
      if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
          $uploadedFileName = $uploadedFile->getClientFilename();
          $type = $uploadedFile->getClientMediaType();
          $File_img[$i] = $uploadedFileName;
          $uploadedFile->moveTo($directory.'/'.$uploadedFileName);
          $i++;
      
  }
 

}
 
 $sql = "UPDATE widget SET WIDGET_DATA= :img WHERE ID_WIDGET =:id";
 try{
    $db = new db();
    $db = $db->connectDB();
    $result = $db->prepare($sql);
    $img_files=json_encode($File_img);
    $result->bindParam(':img', $img_files);
    $result->bindParam(':id', $id_widget);


   $result->execute();
   echo json_encode("Imagen de widget guardada.");  

   $result = null;
   $db = null;
 }catch(PDOException $e){
   echo '{"error" : {"text":'.$e->getMessage().'}';
 }


});
