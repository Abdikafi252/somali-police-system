@extends('layouts.app')

@section('title', 'Go\'aanka Maxkamadda - ' . $courtCase->case_number)

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-bottom: 4rem;">
    <!-- Print Header (Hidden on Screen) -->
    <div class="print-only" style="display: none; text-align: center; margin-bottom: 2rem;">
        <img src="{{ asset('logo.png') }}" style="width: 100px; margin-bottom: 1rem;">
        <h1 style="font-size: 1.5rem; text-transform: uppercase; margin: 0;">Jamhuuriyadda Federaalka Soomaaliya</h1>
        <h2 style="font-size: 1.2rem; margin: 0.5rem 0;">Ciidanka Booliska Soomaaliyeed</h2>
        <h3 style="font-size: 1rem; text-decoration: underline;">Maxkamadda Darajada Koowaad</h3>
    </div>

    <!-- Screen Header -->
    <div class="screen-only" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-family: 'Outfit'; font-weight: 800; color: var(--sidebar-bg);">Xukunka Maxkamadda</h1>
        <div style="display: flex; gap: 1rem;">
            <button onclick="window.print()" class="btn-primary" style="background: var(--sidebar-bg); color: white; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-print"></i> Daabac (Print)
            </button>
            <a href="{{ route('court-cases.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Dib u noqo
            </a>
        </div>
    </div>

    <!-- Judgment Document -->
    <div class="glass-card" style="padding: 3rem; position: relative;">
        <!-- Watermark -->
        <i class="fa-solid fa-scale-balanced" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20rem; opacity: 0.03; color: var(--sidebar-bg); pointer-events: none;"></i>

        <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 1rem; margin-bottom: 2rem;">
            <h2 style="margin: 0; font-family: serif; font-size: 1.8rem; text-transform: uppercase;">Waraaqda Xukunka</h2>
            <p style="margin: 0.5rem 0 0; color: #666; font-family: monospace;">Ref: {{ $courtCase->case_number ?? 'UN-'.rand(1000,9999) }}</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <span style="display: block; font-size: 0.8rem; text-transform: uppercase; color: #666;">Taariikhda Dhageysiga:</span>
                <span style="font-weight: 700; font-size: 1.1rem; color: #000;">{{ \Carbon\Carbon::parse($courtCase->hearing_date)->format('d/m/Y') }}</span>
            </div>
            <div style="text-align: right;">
                <span style="display: block; font-size: 0.8rem; text-transform: uppercase; color: #666;">Garsooraha:</span>
                <span style="font-weight: 700; font-size: 1.1rem; color: #000;">{{ $courtCase->judge->name ?? 'Unknown' }}</span>
            </div>
        </div>

        <div style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="margin-top: 0; font-size: 1rem; text-transform: uppercase; border-bottom: 1px solid #dee2e6; padding-bottom: 0.5rem;">Eedeysanayaasha & Dambiga</h3>
            <div style="margin-top: 1rem;">
                <strong>Dambiga:</strong> {{ $courtCase->prosecution->policeCase->crime->crime_type ?? 'N/A' }}<br>
                <strong>Goobta:</strong> {{ $courtCase->prosecution->policeCase->crime->location ?? 'N/A' }}<br>
                <div style="margin-top: 0.5rem;">
                    <strong>Eedeysanayaasha:</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem;">
                        @foreach($courtCase->prosecution->policeCase->crime->suspects as $suspect)
                        <li>{{ $suspect->name }} ({{ $suspect->age }} sano)</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 3rem;">
            <h3 style="font-family: serif; font-size: 1.4rem; text-decoration: underline; margin-bottom: 1rem;">Go'aanka Maxkamadda</h3>
            <div style="font-family: serif; font-size: 1.1rem; line-height: 1.8; text-align: justify;">
                {{ $courtCase->verdict }}
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 4rem;">
            <div style="text-align: center;">
                <p style="margin-bottom: 3rem;">Saxiixa Garsooraha</p>
                <div style="border-bottom: 1px solid #000; width: 200px; margin: 0 auto;"></div>
                <p style="margin-top: 0.5rem; font-weight: 700;">{{ $courtCase->judge->name }}</p>
            </div>
            <div style="text-align: center;">
                <div style="width: 100px; height: 100px; border: 2px solid #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Shaabadda<br>Maxkamadda</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .print-only {
            display: block !important;
        }

        .screen-only,
        header,
        aside {
            display: none !important;
        }

        .glass-card {
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
        }

        .glass-card * {
            visibility: visible;
        }
    }
</style>
@endsection