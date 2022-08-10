<?php
// the connection to database
   $pdo = new PDO("mysql:host=localhost;port=3306;dbname=products","root","");
   $pdo ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

 
   $search = $_GET['search'] ?? '';

   if($search){
       $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC');

       $statement->bindValue(':title',"%$search%");
      
   }else{
     // selecting the list in the table
   $statement = $pdo->prepare('SELECT * FROM products ORDER BY create_date DESC');
   }


   $statement->execute();
   $products = $statement->fetchAll(PDO::FETCH_ASSOC);


 


?>





<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
      body{
        padding: 60px;
      }
    </style>
  </head>
  <body>
    <h1>Products CRUD</h1>
    <p>
      <a href="create.php" class="btn btn-sm btn-success">Create</a>
    </p>

      <form>
              <div class="input-group " style="width: 20rem;">
                 <input type="text" class="form-control" placeholder="Search for products" name="search">
                 <button class="btn btn-outline-secondary" type="submit">Search</button>
              </div>
      </form>

   <table class="table">
  <thead>
    <tr>
     <th>#</th>
        <th>Image</th>
        <th>Title</th>

        <th>price</th>
        <th>create_date</th>
        <th>Action</th>
    </tr>
  </thead>
  <tbody>
     <?php foreach ($products as $i =>  $product){?>
            <tr>
                <th><?php echo $i + 1 ?></th>
                <td>
                  <img src="<?php echo $product['image']?>" alt="" style="width: 4rem;">
                </td>
                <td><?php echo $product['title'] ?></td>
                <td><?php echo $product['price'] ?></td>
                <td><?php echo $product['create_date'] ?></td>
                <td>
                       <a href="update.php?id=<?php echo $product['ID'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="post" action="delete.php" style="display: inline-block">
                    <input  type="hidden" name="id" value="<?php echo $product['ID'] ?>"/>
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
                </td>
            </tr>
        <?php }?>
   
  </tbody>
</table>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


  </body>
</html>
