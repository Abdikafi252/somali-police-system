@props(['currentStatus'])

@php
    $stages = [
        ['id' => 'Diiwaangelin', 'label' => 'Warbixinta', 'icon' => 'fa-file-lines'],
        ['id' => 'Baaris', 'label' => 'Baarista CID', 'icon' => 'fa-magnifying-glass'],
        ['id' => 'Xeer-Ilaalinta', 'label' => 'Xeer Ilaalinta', 'icon' => 'fa-scale-balanced'],
        ['id' => 'Maxkamadda', 'label' => 'Maxkamadda', 'icon' => 'fa-gavel'],
        ['id' => 'Xiran', 'label' => 'Xiran', 'icon' => 'fa-check-double'],
    ];

    $currentIndex = 0;
    foreach ($stages as $index => $stage) {
        if ($stage['id'] === $currentStatus) {
            $currentIndex = $index;
            break;
        }
    }
    
    // Fallback for sub-statuses or English ones
    if ($currentStatus === 'Baarista-CID') $currentIndex = 1;
@endphp

<div class="stepper-container" style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0; position: relative; padding: 0 1rem;">
    <!-- Background Line -->
    <div style="position: absolute; top: 25px; left: 50px; right: 50px; height: 4px; background: var(--border-soft); z-index: 1;"></div>
    <!-- Active Line -->
    <div style="position: absolute; top: 25px; left: 50px; width: calc({{ ($currentIndex / (count($stages) - 1)) * 100 }}% - 20px); height: 4px; background: var(--accent-color, #3498db); z-index: 1; transition: width 0.5s ease;"></div>

    @foreach($stages as $index => $stage)
        <div class="step" style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 0.8rem; flex: 1;">
            <div style="
                width: 50px; 
                height: 50px; 
                border-radius: 50%; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                background: {{ $index <= $currentIndex ? 'var(--accent-color, #3498db)' : 'white' }}; 
                color: {{ $index <= $currentIndex ? 'white' : 'var(--text-sub)' }};
                border: 3px solid {{ $index <= $currentIndex ? 'var(--accent-color, #3498db)' : 'var(--border-soft)' }};
                transition: all 0.3s ease;
                box-shadow: {{ $index == $currentIndex ? '0 0 15px rgba(52, 152, 219, 0.4)' : 'none' }};
            ">
                <i class="fa-solid {{ $stage['icon'] }}"></i>
            </div>
            <span style="
                font-size: 0.75rem; 
                font-weight: {{ $index <= $currentIndex ? '700' : '500' }};
                color: {{ $index <= $currentIndex ? 'var(--sidebar-bg)' : 'var(--text-sub)' }};
                text-align: center;
                max-width: 80px;
            ">
                {{ $stage['label'] }}
            </span>
        </div>
    @endforeach
</div>
