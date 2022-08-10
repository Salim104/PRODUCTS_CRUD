
<?php

// require_once "functions.php";

  $pdo = new PDO("mysql:host=localhost;port=3306;dbname=products","root","");
  $pdo ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$errors = [];

$title = '';
$description = '';
$price = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $image = $_FILES['image'] ?? null;
    $imagePath = '';

    if (!is_dir('images')) {
        mkdir('images');
    }

    if ($image && $image['tmp_name']) {
        $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
        mkdir(dirname($imagePath));
        move_uploaded_file($image['tmp_name'], $imagePath);
    }

    if (!$title) {
        $errors[] = 'Product title is required';
    }

    if (!$price) {
        $errors[] = 'Product price is required';
    }

    if (empty($errors)) {
        $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
                VALUES (:title, :image, :description, :price, :date)");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', date('Y-m-d H:i:s'));

        $statement->execute();
        header('Location: index.php');
    }

}

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
     <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
      body{
        padding: 50px;
      }
    </style>
    <title>Document</title>
  </head>
  <body>
    <h1>Create New Products</h1>

 <?php if(!empty($errors)) { ?>

     <div class="alert alert-danger">
        <?php foreach ($errors as $error){ ?>
                <div><?php echo $error ?></div>
       <?php } ?>
    </div>

  <?php }?>

  <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group mb-3">
        <label>Product image</label>
        <br>
        <input type="file" name="image">
      </div>
      <div class="form-group mb-3">
        <label>Product title</label>
        <input type="text" class="form-control"  name="title" value="<?php echo $title ?>">
      </div>
      <div class="form-group mb-3">
        <label>Product description</label>
        <textarea class="form-control"  name="description" value="<?php echo $description ?>"></textarea>
      </div>
      <div class="form-group mb-3">
        <label>Product price</label>
        <input type="number" step=".01"  name="price" class="form-control" value="<?php echo $price ?>">
      </div>
       <button type="submit" class="btn btn-primary">Submit</button>
</form>
  
  </body>
</html>