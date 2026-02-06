@extends('layouts.app')

@section('title', 'Go\'aanka Maxkamadda - ' . ($courtCase->prosecution->policeCase->case_number ?? ''))

@section('content')
<div class="header-section no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; margin-bottom: 0.5rem;">
            Go'aanka Maxkamadda (Court Judgment)
        </h1>
        <p style="color: var(--text-sub);">
            Waa nuqulka rasmiga ah ee xukunka maxkamadda.
        </p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <button onclick="window.print()" class="btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.9rem; background: var(--sidebar-bg); color: #fff;">
            <i class="fa-solid fa-print"></i> Print Judgment
        </button>
        <a href="{{ route('court-cases.index') }}" class="btn-secondary" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
            Back to List
        </a>
    </div>
</div>

<div class="glass-card judgment-paper" style="max-width: 800px; margin: 0 auto; padding: 4rem; background: white; color: #000; position: relative;">
    <!-- Watermark -->
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.03; pointer-events: none; z-index: 0;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Coat_of_arms_of_Somalia.svg/1200px-Coat_of_arms_of_Somalia.svg.png" style="width: 500px;">
    </div>

    <!-- Header -->
    <div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 2rem; margin-bottom: 2rem; position: relative; z-index: 1;">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Coat_of_arms_of_Somalia.svg/1200px-Coat_of_arms_of_Somalia.svg.png" style="width: 100px; margin-bottom: 1rem;">
        <h2 style="font-family: 'Times New Roman', serif; text-transform: uppercase; margin: 0; font-weight: 900; letter-spacing: 1px;">Jamhuuriyadda Federaalka Soomaaliya</h2>
        <h3 style="font-family: 'Times New Roman', serif; text-transform: uppercase; margin: 0.5rem 0; font-weight: 700; font-size: 1.2rem;">Maxkamadda Gobolka Banaadir</h3>
        <h4 style="font-family: 'Times New Roman', serif; text-transform: uppercase; margin: 0; font-weight: 600; font-size: 1rem; text-decoration: underline;">Qaybta Ciqaabta</h4>
    </div>

    <!-- Case Info -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-family: 'Times New Roman', serif; font-size: 1.1rem; position: relative; z-index: 1;">
        <div>
            <div><strong>Case Ref:</strong> {{ $courtCase->prosecution->policeCase->case_number }}</div>
            <div><strong>Date:</strong> {{ $courtCase->created_at->format('d/m/Y') }}</div>
        </div>
        <div style="text-align: right;">
            <div><strong>Judge:</strong> {{ $courtCase->judge->name ?? 'N/A' }}</div>
            <div><strong>Hearing:</strong> Room B2, Mogadishu</div>
        </div>
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 2rem; position: relative; z-index: 1;">
        <h1 style="font-family: 'Times New Roman', serif; text-transform: uppercase; font-weight: 900; letter-spacing: 2px; border: 2px solid #000; padding: 0.5rem 1rem; display: inline-block;">WARQAD XUKUN</h1>
    </div>

    <!-- Body -->
    <div style="font-family: 'Times New Roman', serif; font-size: 1.1rem; line-height: 1.8; text-align: justify; margin-bottom: 3rem; position: relative; z-index: 1;">
        <p>
            Maxkamadda oo tixraacaysa dacwadda tirsigeedu yahay <strong>{{ $courtCase->prosecution->policeCase->case_number }}</strong>,
            oo lagu eedeeyay dambiga ah <strong>{{ $courtCase->prosecution->policeCase->crime->crime_type ?? 'N/A' }}</strong>.
        </p>

        <p>
            Kadib markii maxkamaddu dhageysatay doodda Xeer-ilaalinta iyo qareenada difaaca, isla markaana ay baartay caddeymaha la horkeenay
            oo iskugu jira qoraalo iyo marqaatiyaal.
        </p>

        <p>
            Maxkamaddu waxay soo saartay xukunka hoos ku xusan:
        </p>

        <div style="background: #f8f9fa; border: 1px solid #000; padding: 2rem; margin: 2rem 0; font-weight: bold; font-style: italic;">
            "{{ $courtCase->verdict }}"
        </div>

        <p>
            Xukunkan waa mid kama dambays ah oo waafaqsan sharciga dalka. Ciddii aan ku qanacsanayn waxay xaq u leedahay racfaan muddo 30 cisho gudahood ah.
        </p>
    </div>

    <!-- Signatures -->
    <div style="display: flex; justify-content: space-between; margin-top: 4rem; font-family: 'Times New Roman', serif; position: relative; z-index: 1;">
        <div style="text-align: center;">
            <div style="margin-bottom: 4rem;">_______________________</div>
            <div><strong>Xoghayaha Maxkamadda</strong></div>
        </div>
        <div style="text-align: center;">
            <div style="margin-bottom: 4rem;">_______________________</div>
            <div><strong>Garsooraha Maxkamadda</strong></div>
            <div>{{ $courtCase->judge->name ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 4rem; font-size: 0.8rem; color: #666; border-top: 1px solid #ccc; padding-top: 1rem; font-family: 'Times New Roman', serif;">
        <p>Maxkamadda Gobolka Banaadir • Degmada Xamar Weyne, Muqdisho • Somalia</p>
    </div>
</div>

<style>
    @font-face {
        font-family: 'Times New Roman';
        src: local('Times New Roman');
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .judgment-paper,
        .judgment-paper * {
            visibility: visible;
        }

        .judgment-paper {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0 !important;
            padding: 2rem !important;
            box-shadow: none !important;
            border: none;
        }

        .no-print {
            display: none !important;
        }

        /* Ensure background graphics (watermark) print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection