<style>
    .filament-tree .dd-placeholder {
        border: 2px dashed var(--color-primary-500);
        border-radius: 0.5rem;
    }

    .filament-tree .dd-item:has(> .dd-list > .dd-placeholder) > .dd-handle {
        border-color: var(--color-primary-500);
        box-shadow: inset 0 0 0 2px var(--color-primary-500);
        background-color: color-mix(in oklab, var(--color-primary-500) 8%, transparent);
    }

    .filament-tree-page .fi-section-content {
        padding-bottom: 4rem;
    }
</style>
