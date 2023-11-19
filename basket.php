<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content="width=device-width, initial-scale = 1.0">
    <meta http-equiv="Cache-Control" content="no-cache">
    <title>Корзина</title>
    <link rel = "stylesheet" href = "index.css">
</head>
<body>
    <div class="flexblock">
        <p class="h">Корзина: </p>
        <?php
            $link = mysqli_connect('localhost', 'root', '', 'lab7');
            if (!$link) {
                die("Ошибка: " . mysqli_connect_error());
            }
            if($_COOKIE){
                if($_GET['product_id']){ //Если нажал кнопку купить, то записывается №товара
                    $basket_id = $_COOKIE['basket_id'];
                    $product_id = $_GET['product_id'];
                    $basket_product_operation_type = $_GET['operation_type'];
                    $sql = "SELECT * FROM basket";

                    if($result = mysqli_query($link, $sql)){ //проверка на подключение
                        if($basket_product_operation_type == 1){
                            $query = "UPDATE `basket` SET `count_product` = `count_product`+1  WHERE `id_basket` = '$basket_id' AND `id_product` = '$product_id'";
                        }
                        else if($basket_product_operation_type == 0){
                            $count_product = 0;
                            foreach($result as $row){
                                if($row["id_basket"] == $basket_id && $row["id_product"] == $product_id){
                                    $count_product = $row['count_product'];
                                    break;
                                }
                            }
                            if($count_product - 1 <= 0){
                                $query = "DELETE FROM `basket` WHERE `id_basket` = '$basket_id' AND `id_product` = '$product_id'";
                            }
                            else{
                                $query = "UPDATE `basket` SET `count_product` = `count_product`-1  WHERE `id_basket` = '$basket_id' AND `id_product` = '$product_id'";
                            }
                        }
                        mysqli_query($link, $query) or die(mysqli_error($link));

                        header("Location: basket.php");
                        die();

                    }
                }
                else{
                    $basket_id = $_COOKIE['basket_id'];
                    $sql = "SELECT * FROM basket";

                    if($result = mysqli_query($link, $sql)){
                        $product_name = "";
                        foreach($result as $row){
                            if($row["id_basket"] == $basket_id){
                                echo "<div class = 'flexblock'>";
                                if($row['id_product'] == 1) $product_name = "Кроссовки";
                                else if($row['id_product'] == 2) $product_name = "Берцы";
                                else if($row['id_product'] == 3) $product_name = "Сандалии";
                                else if($row['id_product'] == 4) $product_name = "Тапочки";

                                echo "<span class='text'>".$product_name."</span>";
                                echo "<div class = 'flexr'>";
                                echo "<button class = 'but' onclick = window.location='?product_id=".$row['id_product']."&operation_type=0'>-</button>";
                                echo "<div class = 'text'>".$row['count_product']."</div>";
                                echo "<button class = 'but' onclick = window.location='?product_id=".$row['id_product']."&operation_type=1'>+</button>";
                                echo "</div></div>";
                            }
                        }
                    }
                }
            }
        ?>
    </div>
</body>
</html>