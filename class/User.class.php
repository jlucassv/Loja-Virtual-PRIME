<?php

 class User{
    private $pdo;

    private $user_id = NULL;

 public function __construct($user_id = NULL){

      $this->user_id   = $user_id;

      $this->pdo = DB::getInstance();

  }

  public function addBalance($vl){
    $query = $this->pdo->prepare("UPDATE `user` SET balance = balance + :balance WHERE id= :id ");
    $query->bindValue(':balance', $vl);
    $query->bindValue(':id', $this->user_id);

    if($query->execute()){
       return true;
    }else{
        return false;
    }
  }

  public function get(){
    $query = $this->pdo->prepare("SELECT id, username, balance FROM `user` WHERE id= :id");
    $query->bindValue(':id', $this->user_id);

    if($query->execute()){

       $row = $query->fetchAll(PDO::FETCH_OBJ);

        if(count($row)>0){
            return $row[0];
        }else{
            return false;
        }

    }else{
        return false;
    }
  }

 }

 ?>
