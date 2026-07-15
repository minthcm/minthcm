<?php

namespace MintHCM\Lib\MintLogic;

use MintHCM\Data\MintBean;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;

class MintLogic
{
    private $bean;
    private $defs;

    public function __construct($bean)
    {
        if (!$bean instanceof \SugarBean  && !$bean instanceof MintBean) {
            throw new \InvalidArgumentException("Bean must be an instance of SugarBean or MintBean");
        }
        $this->bean = clone $bean;
        $this->defs = $this->loadLogicDefs($bean->module_name);
    }

    private function loadLogicDefs($moduleName)
    {
        $mergedDefs = [];
        
        // Load from main module directory
        $moduleDir = __DIR__ . "/Modules/{$moduleName}";
        if (is_dir($moduleDir)) {
            $mergedDefs = $this->loadLogicDefsFromDirectory($moduleDir, $mergedDefs);
        }
        
        // Load from custom directory
        $customDir = __DIR__ . "/../../custom/lib/MintLogic/{$moduleName}";
        if (is_dir($customDir)) {
            $mergedDefs = $this->loadLogicDefsFromDirectory($customDir, $mergedDefs);
        }
        
        return $mergedDefs;
    }

    private function loadLogicDefsFromDirectory($directory, $mergedDefs)
    {
        $files = glob($directory . '/*logicdefs.php');
        
        foreach ($files as $file) {
            $defs = include $file;
            if (is_array($defs)) {
                $mergedDefs = array_merge_recursive($mergedDefs, $defs);
            }
        }
        
        return $mergedDefs;
    }

    public function getInitial()
    {
        return [
            'rules' => $this->getRules(Hook::INIT),
        ];
    }

    public function getChanged($triggerFields)
    {
        return [
            'rules' => $this->getRules(Hook::CHANGE, $triggerFields),
        ];
    }

    public function validateBean()
    {
        $result = [
            'isValid' => true,
            'error' => null,
        ];
        if (!empty($this->defs['bean']['validation'])) {
            foreach ($this->defs['bean']['validation'] as $validator) {
                try {
                    self::calculateExpression($validator, $this->bean);
                } catch (ValidationException $e) {
                    $result['isValid'] = false;
                    $result['error'] = $e->getMessage();
                    break;
                }
            }
        }
        return $result;
    }

    private function getRules(Hook $hook = Hook::ALL, $triggerFields = null)
    {
        // Pre-compute the net (final) visibility for every field by evaluating ALL
        // hook-matched rules, ignoring the request-specific triggerFields filter.
        // This mirrors the Vue's hiddenFields computation (last-wins across activeRules)
        // and lets calculateLogic() avoid nulling a field whose final state is visible —
        // e.g. the core init rule hides `occasional_leave_type`, but show_special_leave_type
        // (triggerFields:['type']) would show it again; without pre-computation that rule is
        // skipped when triggerFields=['occasional_leave_type'], the field is nulled, and
        // subsequent triggers that read it always see null.
        $netVisibility = $this->computeNetVisibility($hook);

        $rules = [];
        foreach ($this->getAllRules() as $key => $rule) {
            if (empty($rule)) {
                continue;
            }
            if (Hook::ALL !== $hook && !in_array(Hook::ALL, $rule['hooks']) && !in_array($hook, $rule['hooks'])) {
                continue;
            }
            if (!empty($triggerFields) && !empty($rule['triggerFields']) && !array_intersect($triggerFields, $rule['triggerFields'])) {
                continue;
            }

            $isTriggered = !isset($rule['trigger']) || self::calculateExpression($rule['trigger'], $this->bean);

            $rules[] = [
                'key' => $key,
                'triggerFields' => $rule['triggerFields'] ?? [],
                'trigger' => $isTriggered,
                'logic' => $isTriggered ? $this->calculateLogic($rule, $netVisibility) : [],
                'hooks' => $rule['hooks'] ?? [],
            ];
        }
        return $rules;
    }

    /**
     * Compute the net visibility for every field by scanning ALL triggered rules that
     * match the given hook — regardless of the request's triggerFields filter.
     *
     * Each rule's visible declaration is applied in order; the last one wins, exactly
     * as the Vue's `hiddenFields` computed property does.  The result is used in
     * calculateLogic() to decide whether nulling the bean for a visible:false field
     * is safe — if another rule (perhaps one filtered out of the main getRules pass)
     * would ultimately make the field visible, the bean value must be preserved.
     */
    private function computeNetVisibility(Hook $hook): array
    {
        $net = [];
        foreach ($this->getAllRules() as $rule) {
            if (empty($rule)) {
                continue;
            }
            if (Hook::ALL !== $hook && !in_array(Hook::ALL, $rule['hooks']) && !in_array($hook, $rule['hooks'])) {
                continue;
            }
            // Evaluate the trigger on the unmodified bean (no calculateLogic() mutations yet).
            // For the rare trigger that depends on a value set by a previous rule's update
            // closure this may differ from the main-loop result, but visibility computation
            // is the only purpose here and that case does not arise in practice.
            $isTriggered = !isset($rule['trigger']) || self::calculateExpression($rule['trigger'], $this->bean);
            if (!$isTriggered) {
                continue;
            }

            $visible = self::calculateExpression($rule['logic']['visible'] ?? null, $this->bean) ?? [];
            foreach ($visible as $field => $isVisible) {
                $net[$field] = $isVisible; // last-wins — same semantics as Vue hiddenFields
            }
        }
        return $net;
    }

    private function getAllRules(): array
    {
        $rules = array_merge(
            $this->getBasicSets(),
            $this->defs['rules'] ?? []
        );
        return $rules;
    }

    private function getBasicSets()
    {
        $basicSets = [];
        $basicSetsDir = __DIR__ . '/BasicSets';
        if (is_dir($basicSetsDir)) {
            $files = scandir($basicSetsDir);
            foreach ($files as $file) {
                if (in_array($file, ['.', '..']) || pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
                    continue;
                }
                $defs = include $basicSetsDir . '/' . $file;
                foreach ($defs['rules'] as $key => $rule) {
                    $ruleName = is_string($key) ? $key : pathinfo($file, PATHINFO_FILENAME);
                    if (isset($this->defs['rules'][$ruleName])) {
                        continue;
                    }
                    if (is_int($key)) {
                        $basicSets[] = $rule;
                    } else {
                        $basicSets[$key] = $rule;
                    }
                }
            }
        }
        return $basicSets;
    }

    private function calculateLogic($rule, array $netVisibility = [])
    {
        $logic = [
            'errors' => [],
            'visible' => [],
            'readonly' => [],
            'required' => [],
            'update' => [],
            'options' => [],
        ];

        // Update
        $update = self::calculateExpression($rule['logic']['update'], $this->bean) ?? [];
        foreach ($update as $field => $value) {
            $this->bean->{$field} = $value;
            $logic['update'][$field] = $this->bean->{$field};
        }

        // Visible
        $logic['visible'] = self::calculateExpression($rule['logic']['visible'], $this->bean) ?? [];
        foreach ($logic['visible'] as $field => $isVisible) {
            if (!$isVisible) {
                $logic['update'][$field] = null;
                // Only null the bean when the field is ultimately not visible across all
                // triggered rules (pre-computed by computeNetVisibility).  If another rule
                // — even one filtered out of this pass by triggerFields — makes the field
                // visible in the end, the bean value must be preserved so that subsequent
                // trigger expressions in this same getRules() loop can read the real data.
                if (!($netVisibility[$field] ?? false)) {
                    $this->bean->{$field} = null;
                }
            }
        }

        // Readonly
        $logic['readonly'] = self::calculateExpression($rule['logic']['readonly'], $this->bean) ?? [];
        foreach ($logic['readonly'] as $field => $isReadonly) {
            if ($isReadonly) {
                $this->bean->{$field} = $this->bean->{$field} ?? $this->bean->fetched_row[$field] ?? null;
                $logic['update'][$field] = $this->bean->{$field};
            }
        }

        // Required
        $logic['required'] = self::calculateExpression($rule['logic']['required'], $this->bean) ?? [];

        // Validation
        $validation = self::calculateExpression($rule['logic']['validation'], $this->bean) ?? [];
        foreach ($validation as $field => $validator) {
            if (!empty($logic['errors'][$field])) {
                continue;
            }
            try {
                self::calculateExpression($validator, $this->bean, $field);
            } catch (ValidationException $e) {
                $logic['errors'][$field] = $e->getMessage();
            }
        }

        // Options
        $options = self::calculateExpression($rule['logic']['options'], $this->bean) ?? [];
        foreach ($options as $field => $option) {
            $logic['options'][$field] = self::calculateExpression($option, $this->bean);
        }

        return $logic;
    }

    private static function calculateExpression($expr, $bean, $field = null)
    {
        if (empty($expr)) {
            return null;
        }
        if (is_array($expr) && array_is_list($expr)) {
            foreach ($expr as $item) {
                $result = self::calculateExpression($item, $bean, $field);
                if (false === $result) {
                    return $result;
                }
            }
        } else if (is_string($expr) && class_exists($expr)) {
            $object = new $expr($bean);
            if ($object instanceof Validator) {
                !empty($field) ? $object->validate($bean, $field) : $object->validate($bean);
            }
        } else if (is_callable($expr)) {
            return $expr($bean);
        } else if (!empty($expr['op'])) {
            return Formula::executeOperator($expr['op'], $bean, ...$expr['args']);
        }
        return $expr;
    }

}
