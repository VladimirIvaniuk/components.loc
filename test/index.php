<?php
require_once "Database.php";

//$users=Database::GetInstance()->query('select * from comments where name IN (?, ?)', ['Petya', 'Vasya']);
//$users=Database::GetInstance()->get('comments', ['name','=','Petya']);
//Database::GetInstance()->delete('comments', ['name','=','Vasya']);
Database::GetInstance()->insert('comments', [
    'name'=>'Vasya',
    'comment'=>'bla-bla',
    'date'=>'ssdcsdcsd'
]);
//if($users->error()){
//    echo "Error";
//}
//else{
//    foreach ($users->result() as $user){
//        echo $user->name. "</br>";
//    }
//}