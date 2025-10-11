<?php
class Admin {
    public $nome;
    public $email;
    public $senha;

public function __construct($nome,$email,$senha){
    $this->nome = "admin";
    $this->email = "123@email.com";
    $this->senha = "";
}


}