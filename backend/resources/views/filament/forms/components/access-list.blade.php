@php
    $state_path = $getStatePath();
    $accesses = \App\Models\AdminAccess::orderBy('title')->get();
@endphp

<div
    wire:key="access-list-{{ $accesses->count() }}"
    x-data="{
        selected: $wire.entangle('{{ $state_path }}').live,

        isChecked(access_id, type) {
            return (this.selected ?? []).includes(access_id + ':' + type)
        },

        toggle(access_id, type) {
            const view_key = access_id + ':1'
            const edit_key = access_id + ':2'
            const key = access_id + ':' + type

            if (this.isChecked(access_id, type)) {
                this.selected = this.selected.filter(s => s !== key)
                if (type == 1) {
                    this.selected = this.selected.filter(s => s !== edit_key)
                }
            } else {
                this.selected = [...(this.selected ?? []), key]
                if (type == 2 && !this.isChecked(access_id, 1)) {
                    this.selected = [...this.selected, view_key]
                }
            }
        },
    }"
>
    <div class="fi-ta-ctn fi-ta-ctn-with-header">
        <div class="fi-ta-main">
            <div class="fi-ta-header-ctn">
                <div class="fi-ta-header fi-ta-header-adaptive-actions-position">
                    <div>
                        <h2 class="fi-ta-header-heading">Accesses</h2>
                    </div>
                    <div class="fi-ta-actions fi-align-start fi-wrapped">
                        <button
                            type="button"
                            x-on:click="$wire.mountAction('create_access')"
                            class="fi-color fi-color-primary fi-bg-color-600 hover:fi-bg-color-500 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-0 hover:fi-text-color-0 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md fi-ac-btn-action"
                        >
                            Add Access
                        </button>
                    </div>
                </div>
            </div>

            <div class="fi-ta-content-ctn fi-fixed-positioning-context">
                <table class="fi-ta-table">
                    <thead>
                        <tr>
                            <th class="fi-ta-cell fi-ta-selection-cell" style="text-align:center;">View</th>
                            <th class="fi-ta-cell fi-ta-selection-cell" style="text-align:center;">Edit</th>
                            <th class="fi-ta-header-cell">Resource</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accesses as $access)
                            <tr class="fi-ta-row fi-ta-row-not-reorderable">
                                <td class="fi-ta-cell fi-ta-selection-cell" style="text-align:center;">
                                    <input
                                        type="checkbox"
                                        :checked="isChecked({{ $access->id }}, 1)"
                                        @change="toggle({{ $access->id }}, 1)"
                                        class="fi-checkbox-input"
                                    />
                                </td>
                                <td class="fi-ta-cell fi-ta-selection-cell" style="text-align:center;">
                                    <input
                                        type="checkbox"
                                        :checked="isChecked({{ $access->id }}, 2)"
                                        @change="toggle({{ $access->id }}, 2)"
                                        class="fi-checkbox-input"
                                    />
                                </td>
                                <td class="fi-ta-cell">
                                    <div class="fi-ta-col">
                                        <div class="fi-size-sm fi-font-medium fi-ta-text-item fi-ta-text">
                                            {{ $access->title }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="fi-ta-cell fi-ta-selection-cell" style="text-align:center;">
                                    No accesses defined yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
