
<?php

    $errors = [];
    $db = new PDO('mysql:host=localhost;dbname=form; charset=utf8', 'root', '');

    if(!empty($_GET['param']) && ($_GET['param'] =='delete') && !empty($_GET['id']) ){
    
        $id = intval($_GET['id']);
        $sql = 'DELETE FROM strings WHERE id=:id';
        $result = $db->prepare($sql);
        $result->bindParam('id',$id,PDO::PARAM_INT);
        $result->execute();
    }

    if ($_POST) {
        $form_string = $_POST['string'];
        $form_symbol = $_POST['symbol'];

        if (empty($form_string)){
            $errors[] = 'Enter the text!';

        }
        if (empty($form_symbol)){
            $errors[] = 'Enter symbol!';

        }

        if (!$errors) {
            $pattern = '/(['.$form_symbol.'])/';
            $replacement = "";
            $res_string =preg_replace($pattern, $replacement, $form_string);
            if ($res_string) {
                $sql = 'INSERT INTO strings(text) VALUES (:value)';
                $set_string = $db->prepare($sql);
                $set_string->bindParam(':value', $res_string, PDO::PARAM_STR_CHAR);
                $set_string->execute();
            }
        }
        if ($errors){
            foreach ($errors as $error){
                echo $error."<br>";
            }
        }

    }
    $sql = 'Select * From strings';
    $result = $db -> query($sql);
    $i = 0;
    while ($row = $result->fetch()){
        $strings[$i]['id'] = $row['id'];
        $strings[$i]['text'] = $row['text'];
        $strings[$i]['date'] = $row['date'];
        $i++;
    }




?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
      xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <title>Form</title>
</head>
<body>
    <? @$errors; ?>
    <div class="form">
        <form method="post" action="#">

            <textarea name="string" placeholder="Enter string"></textarea>
            <textarea type="text" name="symbol" placeholder="Enter symbols"></textarea>
            <input type="submit" id="submit">
        </form>
    </div>

    <div class="result">
        <table>
            <div class="th">
                <tr>
                        <th class="id"> Id </th>
                        <th class="text"> Result strings </th>
                        <th class="date"> Date </th>
                        <th class="delete"> Delete  </th>
                </tr>
            </div>

        <? if (isset($strings)){
        foreach ($strings as $string):?>
        <tr>
            <td class="id"><? echo $string['id'];?></td>
            <td class="text"><? echo $string['text']?></td>
            <td class="date"><? echo $string['date']?></td>
            <td class="delete"><a href="?param=delete&id=<? echo $string['id']?>">Delete</a></td>
        </tr>
        <?endforeach; }?>
        </table>
    </div>
</body>
</html>
