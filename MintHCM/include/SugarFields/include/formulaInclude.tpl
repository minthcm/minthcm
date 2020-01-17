{{if $vardef.vt_dependency != '' || $vardef.vt_dependency === false || $vardef.vt_dependency === 0}} data-dependency="{literal}{{$vardef.vt_dependency}}{/literal}" {{/if}}
    {{if $vardef.vt_calculated != '' || $vardef.vt_calculated === false || $vardef.vt_calculated === 0}} data-calculated="{literal}{{$vardef.vt_calculated}}{/literal}" {{/if}}
        {{if $vardef.vt_required != '' || $vardef.vt_required === false || $vardef.vt_required === 0}} data-required="{literal}{{$vardef.vt_required}}{/literal}" {{/if}}
            {{if $vardef.vt_readonly != '' || $vardef.vt_readonly === false || $vardef.vt_readonly === 0}} data-readonly="{literal}{{$vardef.vt_readonly}}{/literal}" {{/if}}
                {{if $vardef.vt_validation != '' || $vardef.vt_validation === false || $vardef.vt_validation === 0}} data-validation="{literal}{{$vardef.vt_validation}}{/literal}" {{/if}}
