<?php 
class mymodcomments extends Module{

    public function __construct()
    {
        $this->name="mymodcomments";
        $this->displayName="My Module of product comments";
     
        $this->tab="fron_office_features";
        $this->version="0.1";
        $this->author="Alex Apostol";
        $this->description="With this module, your customers will be able
        to grade and comments your products";
        parent::__construct();
    }

}



?>