# Record View Definitions

## Overview

`recordviewdefs.php` files define the structure of record views (detail/edit) in the new Vue-based system, replacing the legacy detailviewdefs.php and editviewdefs.php files.

## Location

```
legacy/modules/{Module}/metadata/recordviewdefs.php
```

## Basic Structure

```php
<?php

$viewdefs['{Module}'] = [
    'order' => ['basicInfo', 'subpanels'],  // Panel order
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_PANEL_TITLE',  // Optional panel title
            'data' => [
                'sections' => [
                    'sectionName' => [
                        'title' => 'LBL_SECTION_TITLE',
                        'fields' => [
                            ['field1', 'field2'],  // Row with 2 columns
                            ['field3'],            // Row with 1 column (full width)
                            ['field4', ''],        // Row with 1 field (left) and empty column
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_SUBPANELS',
        ],
    ],
];
```

## Available Components

### MintPanelRecordDetails
Standard form fields panel. Most commonly used component for displaying and editing record fields.

**Usage**:
```php
'basicInfo' => [
    'component' => 'MintPanelRecordDetails',
    'title' => 'LBL_BASIC_INFO',  // Optional
    'data' => [
        'sections' => [
            'basic' => [
                'title' => 'LBL_BASIC',
                'fields' => [
                    ['name', 'status'],
                    ['description'],
                ],
            ],
        ],
    ],
],
```

### MintPanelSubpanels
Displays related records (relationships).

**Usage**:
```php
'subpanels' => [
    'component' => 'MintPanelSubpanels',
    'title' => 'LBL_SUBPANELS',
],
```

#### Subpanel Inline Action Buttons

Each row in a subpanel can display action buttons (Edit, Remove, Delete). These come from the legacy subpanel definition's `_buttons` property (e.g. `subpaneldefs.php`) and are mapped to Vue action classes in `vue/src/business/SubpanelActions/InlineActions/`.

**Available inline actions:**

| `widget_class` (legacy) | Vue class | Behavior |
|---|---|---|
| `SubPanelEditButton` | `Edit` | Redirects to the related record's EditView |
| `SubPanelDeleteButton` | `Delete` | Deletes the related record after confirmation |
| `SubPanelRemoveButton` | `Remove` | Unlinks (removes relationship to) the related record after confirmation |

**ACL requirements:**
- `Edit` — requires `edit` access on the related module
- `Delete` — requires `delete` + `edit` access on the related module
- `Remove` — requires `delete` + `edit` access on **both** the related module and the parent record's module

**Extending with a custom inline action:**

Create a new class in `vue/src/business/SubpanelActions/InlineActions/` extending `SubpanelAction`, then register its `widget_class` mapping in `MintSubpanelsInlineButtons.vue`.

```typescript
// vue/src/business/SubpanelActions/InlineActions/MyAction.ts
import { SubpanelAction } from '../SubpanelAction'

export class MyAction extends SubpanelAction {
    public static readonly TITLE = 'LBL_MY_ACTION'
    public static readonly ICON = 'mdi-star'
    public static readonly ACL = ['edit']

    public async execute(): Promise<boolean> {
        // custom logic here
        return true
    }
}
```

The file is auto-discovered by `index.ts` via `import.meta.glob` — no registration needed beyond adding the `widget_class` mapping in `MintSubpanelsInlineButtons.vue`.

### MintPanelFiles
Displays file attachments.

**Usage**:
```php
'files' => [
    'component' => 'MintPanelFiles',
    'title' => 'LBL_FILES',
],
```

### MintPanelScheduler
Displays calendar/scheduler for time-based records.

**Usage**:
```php
'scheduler' => [
    'component' => 'MintPanelScheduler',
    'title' => 'LBL_SCHEDULER',
],
```

### MintPanelChecklist
Displays checklist functionality.

**Usage**:
```php
'checklist' => [
    'component' => 'MintPanelChecklist',
    'title' => 'LBL_CHECKLIST',
],
```

### MintPanelPositionCard
Displays position card information (HR-specific).

**Usage**:
```php
'position' => [
    'component' => 'MintPanelPositionCard',
    'title' => 'LBL_POSITION',
],
```

### MintPanelRecordPanel
Generic record panel for custom implementations.

**Usage**:
```php
'custom' => [
    'component' => 'MintPanelRecordPanel',
    'title' => 'LBL_CUSTOM',
    'data' => [
        // Custom data structure
    ],
],
```

## Field Layout

Fields are organized in rows and columns:

```php
'fields' => [
    ['field1', 'field2'],      // Row 1: 2 columns (50% width each)
    ['field3'],                // Row 2: 1 column (100% width)
    ['field4', ''],            // Row 3: Left column only (empty right)
    ['', 'field5'],            // Row 4: Right column only (empty left)
    ['field6', 'field7', 'field8'],  // Row 5: 3 columns (33% width each)
]
```

**Tips**:
- Empty string (`''`) creates empty column
- Single field in array spans full width
- Multiple fields divide width equally

## Multiple Sections

Organize fields into multiple sections within a panel:

```php
'data' => [
    'sections' => [
        'basic' => [
            'title' => 'LBL_BASIC_INFO',
            'fields' => [
                ['name', 'status'],
            ],
        ],
        'additional' => [
            'title' => 'LBL_ADDITIONAL_INFO',
            'fields' => [
                ['category', 'type'],
            ],
        ],
        'dates' => [
            'title' => 'LBL_DATES',
            'fields' => [
                ['start_date', 'end_date'],
            ],
        ],
    ],
],
```

## Panel Order

Control the order panels appear:

```php
'order' => ['basicInfo', 'customPanel', 'subpanels', 'files'],
```

## Complete Example

```php
<?php

$viewdefs['Employees'] = [
    'order' => ['basicInfo', 'employment', 'subpanels', 'files'],
    'panels' => [
        'basicInfo' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_BASIC_INFO',
            'data' => [
                'sections' => [
                    'personal' => [
                        'title' => 'LBL_PERSONAL_INFO',
                        'fields' => [
                            ['first_name', 'last_name'],
                            ['email', 'phone'],
                            ['date_of_birth', 'gender'],
                        ],
                    ],
                    'contact' => [
                        'title' => 'LBL_CONTACT_INFO',
                        'fields' => [
                            ['address'],
                            ['city', 'postal_code'],
                            ['country'],
                        ],
                    ],
                ],
            ],
        ],
        'employment' => [
            'component' => 'MintPanelRecordDetails',
            'title' => 'LBL_EMPLOYMENT',
            'data' => [
                'sections' => [
                    'employment' => [
                        'title' => 'LBL_EMPLOYMENT_INFO',
                        'fields' => [
                            ['department', 'position'],
                            ['employment_status', 'hire_date'],
                            ['reports_to_name'],
                        ],
                    ],
                ],
            ],
        ],
        'subpanels' => [
            'component' => 'MintPanelSubpanels',
            'title' => 'LBL_RELATED',
        ],
        'files' => [
            'component' => 'MintPanelFiles',
            'title' => 'LBL_DOCUMENTS',
        ],
    ],
];
```

## Dynamic Field Behavior

Field behavior (visibility, required, readonly) is controlled by MintLogic on the backend, not in recordviewdefs. The recordviewdefs only defines structure and layout.

For dynamic field behavior, see backend documentation: `api/documentation/13-mintlogic.md`

## Migration from Legacy Views

When migrating from legacy views:
1. Analyze existing detailviewdefs.php and editviewdefs.php
2. Create recordviewdefs.php with equivalent structure
3. Migrate vt_* View Tools to MintLogic logicdefs.php (see: `api/documentation/13-mintlogic.md`)
4. Remove module from `api/constants/legacy_views.php`
5. Test thoroughly

## Best Practices

**DO**:
- ✅ Group related fields in sections
- ✅ Use meaningful section titles (LBL_* constants)
- ✅ Keep field layout consistent across modules
- ✅ Use appropriate panel components for functionality
- ✅ Test in both detail and edit modes

**DON'T**:
- ❌ Define field logic in recordviewdefs (use MintLogic)
- ❌ Create too many sections (3-5 is optimal)
- ❌ Mix different types of fields without clear organization
- ❌ Forget to add subpanels panel if module has relationships

## Troubleshooting

**Panel not showing**:
- Check component name spelling
- Verify panel key exists in 'order' array
- Check panel structure matches component requirements

**Fields not displaying**:
- Verify field names match vardefs
- Check if fields are hidden by MintLogic
- Ensure fields exist in module vardefs

**Layout broken**:
- Check field array structure (nested arrays for rows)
- Verify empty strings for empty columns
- Test with different screen sizes

---

**Related Documentation**:
- [MintLogic System](../../api/documentation/13-mintlogic.md)
- [Working with Beans](10-working-with-beans.md)
- [Field System](09-fields.md)
