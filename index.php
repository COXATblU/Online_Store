<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content="width=device-width, initial-scale = 1.0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <title>Магазин</title>
    <link rel = "stylesheet" href = "index.css">
</head>
<body>
    <div class="flexblock">
        <p class="text">Нажмите на товар, который хотите приобрести:</p>
        <button class = "button_buy" onclick="window.location='?product_id=1'">Кроссовки</button>
        <button class = "button_buy" onclick="window.location='?product_id=2'">Берцы</button>   
        <button class = "button_buy" onclick="window.location='?product_id=3'">Сандалии</button>    
        <button class = "button_buy" onclick="window.location='?product_id=4'">Тапочки</button>
    </div>
    <div class="flexblock">
        <p class="text">Перейти в корзину:</p>
        <a href="basket.php" class="trash">
            <img src="trash.jpg" alt="Фото корзины" class="trashphoto">
        </a>
    </div>
</body>
<?php
$link = mysqli_connect('localhost', 'root', '', 'lab7');
if (!$link) {
    die("Ошибка:" . mysqli_connect_error());
}
if($_COOKIE){
    if($_GET['product_id']){
    $basket_id = $_COOKIE['basket_id'];
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM basket";

    if($result = mysqli_query($link, $sql)){
        $basket_id_exist = 0;
        foreach($result as $row){
            if($row["id_basket"] == $basket_id && $row["id_product"] == $product_id){
                $basket_id_exist = 1;
                break;
            }
        }
        if($basket_id_exist != 0){
            $query = "UPDATE `basket` SET `count_product` = `count_product`+1  WHERE `id_basket` = '$basket_id' AND `id_product` = '$product_id'";
            mysqli_query($link, $query) or die(mysqli_error($link));
        }
        else{
            $query = "INSERT INTO `basket` (`id_basket`, `id_product`, `count_product`) VALUES ('$basket_id', '$product_id', 1)";
            mysqli_query($link, $query) or die(mysqli_error($link));
        }
        }
    }
    else {
        die("Ошибка:" . mysqli_connect_error());
    }
}
else{
$random = random_int(0, 9999);
$res = mysqli_query($link, "SELECT * FROM basket");
foreach($res as $row){
    if($row["id_basket"] == $random) { 
        $random = random_int(0, 9999);
    }
}
setcookie('basket_id', $random, time() + 86400, '/');
}
?>
</html>