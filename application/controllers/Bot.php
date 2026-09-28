<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Telegram\Bot\Api;

class Bot extends CI_Controller {
    function __construct(){
        parent::__construct();
    }

    function index(){
        $telegram = new Api('5105846334:AAGuYQHUF-1WdtBeylhexUBtuYY1NE_-qss');
         $response = $telegram->getMe();

        $botId = $response->getId();
        $firstName = $response->getFirstName();
        $username = $response->getUsername();

        echo $botId;
    } 
}

