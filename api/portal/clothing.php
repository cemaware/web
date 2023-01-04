<?php 

if(isset($_POST['submit'])) {
   $item_name = $_POST['name'];
   $item_price = intval($_POST['price']);
   $item_type = $_POST['type'];

   if(empty($item_name) || empty($item_price) || empty($item_type)) {
      die;
   }

   $types = ['shirt', 'pants'];

   echo $types;

   echo "huray";
}