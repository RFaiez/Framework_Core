<?php

namespace Adapter;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Form\FormBuilder;
use ReflectionClass;
use Service\Entity;

class FormBuilderAdapter extends FormBuilder{

    private $className;
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em=$em;        
    }

    public function getBuilder(Entity $entity)
    {
        $this->className=$entity->getClassName();
        $this->set_data($entity->expose());
        return $this;
    }

    public function check_for_custom_validators()
    {
        $reflectionClass = new ReflectionClass($this->className);
        $reader = new AnnotationReader();
        $x=$reader->getClassAnnotation($reflectionClass, Validate::class);
        $result=true;
        foreach (explode(',', $x->props) as $key => $value) {
            $value=trim($value);
            $propertyValue=$this->data[$value];
            $object=$this->em->getRepository($this->className)->findOneBy([
                $value=>$propertyValue
            ]);
            
            if($object){
                $this->add_error($value, $value.' exist');
                $result=false;
            }
        }

        return $result;
    }

    function validate(){
        $result=true;
        if( $this->check_for_required_fields() ){
            $result= false;
        }

        if(  $this->check_for_confirmation_fields() ){
            $result= false;
        }

        if( !$this->check_for_custom_validators() ){
            $result= false;
        }

        if($result)
            $this->data=[];
        return $result;
    }

    public function error_message($field, $type="Champ invalide"){

        if( isset($this->error_messages[$field]) ){
            if( isset($this->error_messages[$field][$type]) ){
                return $this->error_messages[$field][$type];
            }
        }
        return $type;
    }

    public function start()
    {
        return "<form ". $this->serialize_attrs($this->attrs)." >\n";
    }

    public function end()
    {
        return "</form>";
    }

}