<?php
if(isset($_GET['token']) && !empty($_GET['token']) && $_GET['token'] == 'e22603333e69c0df46b2b9d347bf4b05'):


$db = new PDO('mysql:host=localhost;dbname=DB_NAME', 'DB_USER', 'DB_PASS');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$st = $db->prepare("SELECT * FROM be_rfq_matrix WHERE part_no = :part_no ORDER BY ts DESC LIMIT 3");
$st->bindParam(':part_no', $_GET['part_no']);
$st->execute();

$results = $st->fetchAll(PDO::FETCH_ASSOC);

$return = array();

foreach ($results as $v):
 
    $st = $db->prepare("SELECT * FROM be_customer 
        LEFT JOIN be_ticket 
        ON be_customer.customer_id = be_ticket.customer_id 
        WHERE be_ticket.ticket_id = :id");
    $st->bindParam(':id', $v['ticket_id']);
    $st->execute();
 
    $customer = $st->fetch(PDO::FETCH_ASSOC);
    
    $return[] = array('results'=>$v, 'customer'=>$customer);    
    
    //echo $res;

endforeach;

echo json_encode($return);

else:
    echo 'INVLID ACCESS';
endif;

?>
