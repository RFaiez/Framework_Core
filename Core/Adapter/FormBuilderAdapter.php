<?php

namespace rfaiez\framework_core\Adapter;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use ReflectionClass;
use rfaiez\framework_core\Annotation\Validate;
use rfaiez\framework_core\Form\FormBuilder;
use rfaiez\framework_core\Service\Entity;

class FormBuilderAdapter extends FormBuilder
{
    private $className;
    private $em;

    /**
     * Constructor.
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Init builder by an entity object.
     *
     * @param \rfaiez\framework_core\Service\Entity $entity
     *
     * @return FormBuilderAdapter
     */
    public function getBuilder(Entity $entity): FormBuilderAdapter
    {
        $this->className = $entity->getClassName();
        $this->set_data($entity->expose());

        return $this;
    }

    /**
     * Check for duplicated entry from database.
     *
     * @return boolean
     */
    public function check_for_custom_validators(): bool
    {
        $reflectionClass = new ReflectionClass($this->className);
        $reader = new AnnotationReader();
        $x = $reader->getClassAnnotation($reflectionClass, Validate::class);
        $result = true;
        foreach (explode(',', $x->props) as $key => $value) {
            $value = trim($value);
            $propertyValue = $this->data[$value];
            $object = $this->em->getRepository($this->className)->findOneBy([
                $value => $propertyValue,
            ]);

            if ($object) {
                $this->add_error($value, $value.' exist');
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Validate the form.
     *
     * @return boolean
     */
    public function validate(): bool
    {
        $result = true;
        if ($this->check_for_required_fields()) {
            $result = false;
        }

        if ($this->check_for_confirmation_fields()) {
            $result = false;
        }

        if (!$this->check_for_custom_validators()) {
            $result = false;
        }

        if ($result) {
            $this->data = [];
        }

        return $result;
    }

    /**
     * Set message error.
     *
     * @param string $field
     * @param string $type
     *
     * @return string
     */
    public function error_message(string $field, string $type = 'Champ invalide'): string
    {
        if (isset($this->error_messages[$field])) {
            if (isset($this->error_messages[$field][$type])) {
                return $this->error_messages[$field][$type];
            }
        }

        return $type;
    }

    /**
     *  Form start tag.
     *
     * @return string
     */
    public function start(): string
    {
        return '<form '.$this->serialize_attrs($this->attrs)." >\n";
    }

    /**
     * Form end tag.
     *
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }
}
