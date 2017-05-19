<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 4/25/2017
 * Time: 11:00 AM
 */
include_once $_SERVER['DOCUMENT_ROOT'] . "/Sql/Sql.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/Session.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/MasterView.php";


class MasterController{
    private $Path = [];
    private $controllers = [];

    function __construct($path) {
        $this->GetControllers();
        foreach ($this->controllers as $file){
            include_once $file;
        }
        $this->Path = $path;
        $this->ReadParams();
        RouteTable::GenerateRouteTable($this->controllers);
        $this->BuildView();
    }

    private function BuildView(){

        RouteTable::ValidatePath($this->Path);
        $this->CallController();
        MasterView::GenerateView($this->Path);
    }

    private function GetControllers(){
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/Controllers/";
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $path){
            if (strpos($path, ".php")) array_push($this->controllers, $path);
        }
    }

    private function CallController(){
        $count = count($this->Path);
        $controller = $this->Path[$count-2] . "Controller";
        call_user_func($controller."::".Helper::GetRequestMethod().$this->Path[$count-1]);

    }

    private function ReadParams() {
        Helper::PrintArray($this->Path);
        if (strpos(end($this->Path), "?" ) != false){
            $exploded = explode("?", end($this->Path));
            Session::SetParams(end($exploded));
            $count = count($this->Path);
            $this->Path[$count-1] = $exploded[0];
            array_filter($this->Path);
        }
    }
}