<?php

class Home extends TwigView {
    
    public function show($path, $parameters){
        self::getTwig()->display($path, $parameters);
    }
}