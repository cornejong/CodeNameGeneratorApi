<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

class CodeNameGenerator
{
    public $status = true;
    public $givenAtribute = NULL;
    public $givenObject = NULL;
    public $response = array(
        "status" => true
    );

    public function __construct()
    {
        $this->all_atributes = json_decode(file_get_contents('./all_atributes.json'), true);
        
        $this->all_objects = json_decode(file_get_contents('./all_objects.json'), true);

        $this->cat_atributes = json_decode(file_get_contents('./categorized_atributes.json'), true);

        $this->cat_objects = json_decode(file_get_contents('./categorized_objects.json'), true);
    }

    public function randomCodeName()
    {
        return($this->getRandom('atribute') . " " . $this->getRandom('object'));
    }

    public function exists(string $type, string $value)
    {
        if($type == 'atribute') {
            if(array_key_exists($value, $this->cat_atributes)) {
                return true;
            } else {
                return false;
            }
        } elseif($type == 'object') {
            if(array_key_exists($value, $this->cat_objects)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function objectExists(string $object)
    {
        if(array_key_exists($object, $this->cat_objects)) {
            return true;
        } else {
            return false;
        }
    }

    public function getRandom(string $type)
    {
        if($type == 'atribute') {
            $response = $this->all_atributes[rand(0, (count($this->all_atributes) - 1 ))];
        } elseif($type == 'object') {
            $response = $this->all_objects[rand(0, (count($this->all_objects) - 1 ))];
        }

        return $response;
    }

    public function get(string $type, string $value)
    {
        $response = "";
        if($type == 'atribute') {
            if(!$this->exists('atribute', $value)) {
                $this->status = false;
                $this->response['status'] = false;
                $this->response['message'] = "The given atribute group does not exist.";
            } else {
                $response = $this->cat_atributes[$value][rand(0, (count($this->cat_atributes[$value]) - 1 ))];
            }
        } elseif($type == 'object') {
            if(!$this->exists('object', $value)) {
                $this->status = false;
                $this->response['status'] = false;
                $this->response['message'] = "The given object group does not exist.";
            } else {
                $response = $this->cat_objects[$value][rand(0, (count($this->cat_objects[$value]) - 1 ))];
            }
        }

        return $response;
    }

    public function newCodeName()
    {
        if(!empty($this->givenAtribute)) {
            $atribute = $this->get('atribute', $this->givenAtribute);
        } else {
            $atribute = $this->getRandom('atribute');
        }

        if(!empty($this->givenObject)) {
            $object = $this->get('object', $this->givenObject);
        } else {
            $object = $this->getRandom('object');
        }

        /* Compile the codename */
        $this->codename = $atribute . " " . $object;

        /* Check if we're still good */
        if($this->status === true) {
             /* Add the generated codename to the response */
            $this->response['codename'] = $this->codename;
            /* And return the codename */
            return $this->codename;
        } else {
            /* return false */
            return false;
        }

    }

    public function responde($type = "JSON", &$response)
    {
        /* Get the boiler Plate setup */
        $response = $this->response;

        /* Stamp the response */
        $response['uid'] = uniqid();
        $response['timestamp'] = time();

        /* Check the response type */
        switch ($type) {
            case 'JSON':
                /* If its Json, Lets encode it the pretty way ;) */
                $response = json_encode($response, JSON_PRETTY_PRINT);
                break;
            
            default:

                break;
        }
    }

}


/* Get a new CodeName Generator */
$generate = new CodeNameGenerator;
/* Check if there are any specifications given */
if(isset($_GET['atribute']) && !empty($_GET['atribute']))   $generate->givenAtribute = $_GET['atribute'];
if(isset($_GET['object']) && !empty($_GET['object']))       $generate->givenObject = $_GET['object'];

/* Get the codename */
$generate->newCodeName();

$generate->responde("JSON", $response);

print($response);