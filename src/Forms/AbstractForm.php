<?php

namespace src\Forms;

use src\Enums\RouteNames;
use src\Exceptions\ValidationException;
use src\Validation\RuleInterface;

abstract class AbstractForm
{
    protected string $action   = '';
    protected string $method   = 'POST';
    /**
     * @var array<string, array{type: string, label: string, rules: RuleInterface[], attrs: array<string,mixed>}>
     */
    protected array  $fields   = [];
    public array     $errors   = [];
    public array     $data     = [];

    public function __construct(
        RouteNames $route,
    ) {
        $this->action = $route->getPath();
        $this->method = strtoupper($route->getMethod());
    }

    /**
     * Add a field to the form.
     *
     * @param string $name    the input name
     * @param RuleInterface[] $rules   validation rules
     */
    public function addField(
        string $name,
        array $rules = []
    ): self {
        $this->fields[$name] = [
            'rules' => $rules,
        ];
        return $this;
    }

    /**
     * Bind input and validate.
     *
     * @param array $input e.g. Request::body
     * @throws ValidationException
     */
    public function handle(array $input): void
    {
        $this->errors = [];
        foreach ($this->fields as $name => $config) {
            $value = $input[$name] ?? null;
            $fieldErrors = [];

            foreach ($config['rules'] as $rule) {
                try{
                    $rule->validate($value);
                } catch (ValidationException $e) {
                    $fieldErrors[] = $e->getMessage();
                }
            }
            if (!empty($fieldErrors)) {
                $this->errors[$name] = $fieldErrors;
            } else {
                $this->data[$name] = $value;
            }
        }
        if (!empty($this->errors)) {
            throw new ValidationException(ValidationException::FORM_VALIDATION, $this->errors);
        }
    }
}
