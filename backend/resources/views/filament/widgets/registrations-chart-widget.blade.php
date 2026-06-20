@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
@endassets

<x-filament-widgets::widget>
    <x-filament::section>
        <div
            x-data="{
                is_visible: false,
                chart: null,
                toggle() {
                    this.is_visible = !this.is_visible
                    if (this.is_visible) {
                        this.$nextTick(() => {
                            if (!this.chart) {
                                this.chart = new Chart(this.$refs.canvas, {
                                    type: 'line',
                                    data: {
                                        labels: {{ Js::from($chart_data['labels']) }},
                                        datasets: [{
                                            label: 'Registrations',
                                            data: {{ Js::from($chart_data['values']) }},
                                            borderColor: 'rgb(99, 102, 241)',
                                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                            fill: true,
                                            tension: 0.4,
                                            pointRadius: 4,
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            legend: { display: false },
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: { stepSize: 1, precision: 0 },
                                            },
                                        },
                                    },
                                })
                            } else {
                                this.chart.resize()
                            }
                        })
                    }
                },
            }"
        >
            <div class="flex items-center justify-between">
                <span class="font-semibold text-gray-950 dark:text-white">
                    User Registrations — last 30 days
                </span>
                <x-filament::button
                    @click="toggle()"
                    size="sm"
                    color="gray"
                    x-text="is_visible ? 'Hide Chart' : 'Show Chart'"
                ></x-filament::button>
            </div>

            <div x-show="is_visible" class="mt-4">
                <canvas x-ref="canvas" style="max-height: 300px"></canvas>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
