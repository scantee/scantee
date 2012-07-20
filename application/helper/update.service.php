<?php
/**
 * Post Filter - remove any bad stuff from post var
 * Additional filtering done with prepared statements 
 * 
 */
function _INPUT($value) // filter all input
{
$value = strip_tags($value);
$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
$value = str_replace(array("!", "#", "$", "%", "^", "&", "*", "<", ">", "?", ',' , "'"), '', $value);
$value = str_replace(array("\r\n", "\r", "\n", "\t", " "), '', $value);

return $value;
}

$_POST = array_map('_INPUT', $_POST); // filter all input



if(isset($_POST['token']) && !empty($_POST['token']) && $_POST['token'] == 'e22603333e69c0df46b2b9d347bf4b05'):

$ary = array('display'=>$_POST['display'], 'new'=>$_POST['new'],'green'=>$_POST['green'],'part_no'=>$_POST['part_no']);

$db = new PDO('mysql:host=localhost;dbname=DB_NAME', 'DB_USER', 'DB_PASS');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$st = $db->prepare("UPDATE store 
    SET display = :display, new_price = :new, green_price = :green 
    WHERE part_no = :part_no");
$st->bindParam(':display', $ary['display']);
$st->bindParam(':new', $ary['new']);
$st->bindParam(':green', $ary['green']);
$st->bindParam(':part_no', $ary['part_no']);
$results = $st->execute();
$count = $st->rowCount();

if($results && $count >=1)echo 1;
    else 0;

else:
    echo 'INVLID ACCESS';
endif;


?>
