<?php

namespace Generator;

class codeName
{
    private $status = true;
    private $atribute = NULL;
    private $object = NULL;
    private $response = array(
        "status" => true
    );

    public function __construct()
    {
        $this->all_atributes = json_decode(file_get_contents('./data/all_atributes.json'), true);
        
        $this->all_objects = json_decode(file_get_contents('./data/all_objects.json'), true);

        $this->cat_atributes = json_decode(file_get_contents('./data/categorized_atributes.json'), true);

        $this->cat_objects = json_decode(file_get_contents('./data/categorized_objects.json'), true);
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
        if(!empty($this->atribute)) {
            $atribute = $this->get('atribute', $this->atribute);
        } else {
            $atribute = $this->getRandom('atribute');
        }

        if(!empty($this->object)) {
            $object = $this->get('object', $this->object);
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
                /*  If its Json, */
                /* Set the header for the Json content */
                header('Content-Type: application/json');
                /* And now encode it the pretty way ;) */
                $response = json_encode($response, JSON_PRETTY_PRINT);
                break;
            
            default:

                break;
        }
    }
    
    public function setAtribute($atribute)
    {
        if(!is_string($atribute) return false;
        $this->atribute = $atribute;
        return true;
    }
       
    public function setObject($object)
    {
        if(!is_string($object) return false;
        $this->object = $object;
        return true;
    }
