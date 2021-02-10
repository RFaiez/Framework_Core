<?php

namespace rfaiez\framework_core\Form;

class FormBuilder
{
    protected $errors = [];
    protected $inputs = [];
    protected $attrs = [];
    protected $validators = [];

    protected $html_before = '<div class="%s">';
    protected $html_after = '</div>';

    protected $data = [];

    protected $error_messages = [];

    protected $default_attrs = [
        'method' => 'post',
        'accept-charset' => 'utf-8',
        'enctype' => 'application/x-www-form-urlencoded',
    ];

    public function __construct($action = '', $params = [])
    {
        if (!empty($action) && empty($params) && is_array($action)) {
            $params = $action;
            $action = '';
        }

        if (!empty($action)) {
            $this->set_attr('action', $action);
        }

        $args = array_merge($this->default_attrs, $params);
        foreach ($args as $attr_name => $attr_value) {
            $this->set_attr($attr_name, $attr_value);
        }
    }

    public function set_error_messages($m)
    {
        $this->error_messages = $m;
    }

    public function attr($key)
    {
        if (isset($this->attrs[$key])) {
            return $this->attrs[$key];
        }

        return null;
    }

    public function set_attr($key, $value)
    {
        $this->attrs[$key] = $value;

        return $this;
    }

    public function set_data($data)
    {
        $this->data = $data;
    }

    public function data()
    {
        return $this->data;
    }

    public function render()
    {
        $html = sprintf("<form %s>\n", $this->serialize_attrs($this->attrs));
        foreach (array_keys($this->inputs) as $input_name) {
            $html .= $this->input_html($input_name);
        }
        $html .= '</form>';

        return $html;
    }

    public function inputs()
    {
        return $this->inputs;
    }

    public function input($name)
    {
        return isset($this->inputs[$name]) ? $this->inputs[$name] : null;
    }

    public function validate()
    {
        if ($this->check_for_required_fields()) {
            return false;
        }

        if ($this->check_for_confirmation_fields()) {
            return false;
        }

        if (!$this->check_for_custom_validators()) {
            return false;
        }

        return true;
    }

    public function input_html($name)
    {
        $input = isset($this->inputs[$name]) ? $this->inputs[$name] : null;

        if (empty($input)) {
            return '';
        }

        $html = '';

        if (!empty($this->html_before)) {
            if (!isset($this->errors[$name])) {
                $html .= $this->html_before."\n";
            } else {
                $html .= sprintf($this->html_before, 'field-with-errors '.join($this->errors[$name]))."\n";
            }
        }

        if (false != $input['label']) {
            $html .= sprintf('<label for="%s">%s</label>', $name, $input['label']);
            $html .= "\n";
        }

        if ('textarea' == $input['type']) {
            $html .= sprintf(
                '<textarea %s>%s</textarea>',
                $this->serialize_attrs($input['attrs']),
                isset($this->data[$name]) ? $this->data[$name] : ''
            );
        } elseif ('select' == $input['type']) {
            $html .= sprintf('<select %s>', $this->serialize_attrs($input['attrs']));
            $html .= '</select>';
        } elseif ('radio' == $input['type'] || 'checkbox' == $input['type']) {
        } else {
            $html .= sprintf(
                '<input type="%s" %s %s />',
                $input['type'],
                isset($this->data[$name]) ? 'value="'.$this->data[$name].'"' : '',
                $this->serialize_attrs($input['attrs'])
            );
        }

        if (isset($this->errors[$name])) {
            $html .= sprintf(
                '<span class="error-message %s">%s</span>',
                join(' ', $this->errors[$name]),
                $this->error_message($name, $this->errors[$name][0])
            );
        }

        if (!empty($this->html_after)) {
            $html .= $this->html_after."\n";
        }

        return $html;
    }

    public function add_input($name, $type = '', $params = [])
    {
        $input = [
            'attrs' => [
                'name' => $name,
            ],
            'type' => (!empty($type) ? $type : 'text'),
            'before_html' => '<div>',
            'after_html' => '</div>',
            'required' => false,
            'confirm' => false,
            'label' => false,
        ];
        $not_attrs = ['before_html', 'after_html', 'label', 'required', 'label', 'confirm'];
        foreach ($params as $attr => $value) {
            if (in_array($attr, $not_attrs)) {
                $input[$attr] = $value;
            } else {
                $input['attrs'][$attr] = $value;
            }
        }
        $this->inputs[$name] = $input;

        return $this;
    }

    public function serialize_attrs($attrs = [])
    {
        $html_attrs = '';

        foreach (\array_merge($this->default_attrs, $attrs) as $attr => $value) {
            if (is_array($value)) {
                $html_attrs .= sprintf('%s="%s" ', $attr, implode(' ', $value));
            } else {
                $html_attrs .= sprintf('%s="%s" ', $attr, $value);
            }
        }

        return $html_attrs;
    }

    public function check_for_required_fields()
    {
        $has_errors = false;
        foreach ($this->inputs as $input) {
            if (false == $input['required']) {
                continue;
            }

            $name = $input['attrs']['name'];
            if (!isset($this->data[$name]) || ('' == $this->data[$name])) {
                $this->add_error($name, 'required');
                $has_errors = true;
            }
        }

        return $has_errors;
    }

    public function check_for_confirmation_fields()
    {
        $has_errors = false;
        foreach ($this->inputs as $input) {
            if (false == $input['confirm']) {
                continue;
            }
            $name = $input['attrs']['name'];
            $confirm_field = $name.'_confirmation';

            if (!isset($_REQUEST[$confirm_field]) || ($this->data[$name] != $_REQUEST[$confirm_field])) {
                $this->add_error($confirm_field, $confirm_field);
                $has_errors = true;
            }
        }

        return $has_errors;
    }

    public function check_for_custom_validators()
    {
        return true;
    }

    public function add_error($field, $code)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        if (!in_array($code, $this->errors[$field])) {
            $this->errors[$field][] = $code;
        }
        $this->error_messages[$field] = $code;
    }

    public function error_message(string $field, string $type)
    {
        $error = 'Champ invalide';
        if (isset($this->error_messages[$field])) {
            if (isset($this->error_messages[$field][$type])) {
                return $this->error_messages[$field][$type];
            }
        }

        return $error;
    }
}
