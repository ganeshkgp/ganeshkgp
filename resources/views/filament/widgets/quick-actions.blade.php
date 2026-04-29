<x-filament-widgets::widget>
    <x-filament::section heading="Quick Actions">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:0.75rem;">
            @foreach ($links as $link)
                <a
                    href="{{ $link['url'] }}"
                    @if(str_starts_with($link['url'], 'http')) target="_blank" rel="noopener" @endif
                    style="
                        display:flex;
                        flex-direction:column;
                        align-items:center;
                        gap:0.625rem;
                        padding:1rem 0.75rem;
                        border-radius:0.75rem;
                        border:1px solid rgba(var(--gray-200),0.1);
                        background:rgba(var(--gray-50),0.03);
                        text-decoration:none;
                        transition:background 150ms,border-color 150ms;
                    "
                    onmouseover="this.style.background='rgba(var(--primary-500),0.08)';this.style.borderColor='rgba(var(--primary-400),0.3)'"
                    onmouseout="this.style.background='rgba(var(--gray-50),0.03)';this.style.borderColor='rgba(var(--gray-200),0.1)'"
                >
                    <span style="
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        width:2.5rem;
                        height:2.5rem;
                        border-radius:0.625rem;
                        background:rgba(var(--primary-500),0.12);
                    ">
                        <x-filament::icon
                            :icon="$link['icon']"
                            style="width:1.25rem;height:1.25rem;color:rgb(var(--primary-400));"
                        />
                    </span>
                    <span style="
                        font-size:0.75rem;
                        font-weight:500;
                        line-height:1.2;
                        text-align:center;
                        color:rgb(var(--gray-300));
                    ">
                        {{ $link['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
