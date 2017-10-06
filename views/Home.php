<?php

class Home extends TwigView {
    
    public function show($path){
        self::getTwig()->display($path);
    }
}