<?php

namespace MintHCM\Api\RecordView;

/**
 * Merges the record view actions declared in viewdefs with the default record view actions.
 *
 * Declared actions never silently replace the default ones -- they are appended after them, unless
 * a declaration reuses the identifier of a default action, in which case it takes over that
 * action's position. Panels opt out of the defaults with 'includeDefaultActions' => false.
 */
class RecordViewActionsMerger
{
    public function merge(array $declared_actions, bool $include_defaults, array $default_actions): array
    {
        $actions = $include_defaults ? array_merge($default_actions, $declared_actions) : $declared_actions;

        $merged = [];
        foreach ($actions as $action) {
            $identifier = $this->resolveIdentifier($action);
            if (null === $identifier) {
                continue;
            }
            // Reassigning an existing key keeps its original position, so a declaration overriding
            // a default action replaces it in place instead of moving to the end of the list.
            $merged[$identifier] = $action;
        }

        return array_values($merged);
    }

    /**
     * Returns the identifier an action is deduplicated by, or null when the declaration is unusable.
     *
     * A metadata driven action (GenericApiAction) is recognised by 'api_route' and addressed by its
     * required 'key'. Any other action is a named one and is addressed by its 'name'.
     */
    private function resolveIdentifier(mixed $action): ?string
    {
        if (is_string($action)) {
            return $this->nonEmptyString($action);
        }
        if (!is_array($action)) {
            return null;
        }

        // array_key_exists, not isset: the frontend recognises a generic action with `'api_route' in
        // action`, so a declared but null api_route must be treated as generic on both sides.
        $is_generic = array_key_exists('api_route', $action);
        $identifier = $is_generic ? ($action['key'] ?? null) : ($action['name'] ?? null);

        return is_string($identifier) ? $this->nonEmptyString($identifier) : null;
    }

    private function nonEmptyString(string $value): ?string
    {
        return '' !== $value ? $value : null;
    }
}
