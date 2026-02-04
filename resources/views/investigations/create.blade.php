@extends('layouts.app')

@section('title', 'Gudbi Baaritaanka: ' . $case->case_number)

@section('css')
<style>
    .ck-editor__editable {
        min-height: 300px;
        border-radius: 0 0 12px 12px !important;
    }
    .ck-toolbar {
        border-radius: 12px 12px 0 0 !important;
        border: 2px solid var(--border-soft) !important;
        border-bottom: none !important;
        background: #f8fafc !important;
    }
    .ck-content {
        border: 2px solid var(--border-soft) !important;
        font-size: 1rem !important;
        line-height: 1.6 !important;
    }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
        border-color: var(--border-soft) !important;
    }
</style>
@endsection

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">GUDIBI WARBIXIN BAARITAAN</h1>
    <p style="color: var(--text-sub);">Dhamaystirka baarista kiiska: <strong style="color: var(--sidebar-bg);">{{ $case->case_number }}</strong></p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <div class="glass-card" style="padding: 2.5rem;">
        <form action="{{ route('investigations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="case_id" value="{{ $case->id }}">

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="outcome" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                    <i class="fa-solid fa-gavel" style="color: #e74c3c; margin-right: 0.5rem;"></i> Go'aanka Baarista (Final Outcome)
                </label>
                <select name="outcome" id="outcome" class="form-control" required style="border: 2px solid var(--border-soft); border-radius: 12px; padding: 1rem; width: 100%; font-weight: 700; background: #fff;">
                    <option value="" disabled selected>Dooro go'aanka baaritaanka...</option>
                    <option value="Eedeysane ah">Eedeysane ah (To be prosecuted)</option>
                    <option value="Bari ah">Bari ah (Not Guilty / Dismissed)</option>
                    <option value="Baaris dheeraad ah">Baaris dheeraad ah (Needs further investigation)</option>
                    <option value="Damiin lagu daayay">Damiin lagu daayay (Released on Bail)</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="findings" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                    <i class="fa-solid fa-clipboard-list" style="color: var(--accent-color); margin-right: 0.5rem;"></i> Natiijada Baaritaanka (Findings Summary)
                </label>
                <div style="background: white; border-radius: 12px;">
                    <textarea name="findings" id="findings" class="form-control" rows="10" 
                        placeholder="Sharaxaad waafi ah ka bixi baaritaanka aad samaysay iyo waxaad soo ogaatay..."></textarea>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="evidence_list" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                    <i class="fa-solid fa-box-archive" style="color: #e67e22; margin-right: 0.5rem;"></i> Liiska Caddeymada (Evidence List)
                </label>
                <textarea name="evidence_list" id="evidence_list" class="form-control" rows="4" 
                    style="border: 2px solid var(--border-soft); border-radius: 12px; padding: 1.2rem; font-size: 1rem; line-height: 1.6; transition: all 0.3s ease;" 
                    placeholder="Tusaale: Midida loo adeegsaday dambiga, sawirro goobta dambiga laga soo qaaday, magacyada markhaatiyada..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="files" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                    <i class="fa-solid fa-paperclip" style="color: #3498db; margin-right: 0.5rem;"></i> Lifaaqa Caddeymada (Images/Files)
                </label>
                <div style="border: 2px dashed var(--border-soft); padding: 2rem; border-radius: 12px; text-align: center; background: #fbfbfc;">
                    <i class="fa-solid fa-cloud-arrow-up fa-2x" style="color: var(--text-sub); margin-bottom: 1rem;"></i>
                    <input type="file" name="files[]" id="files" multiple class="form-control" style="border: none; background: transparent;">
                    <p style="margin-top: 1rem; font-size: 0.75rem; color: var(--text-sub);">Waxaad soo gali kartaa sawirro dhowr ah (JPEG, PNG, JPG)</p>
                </div>
            </div>

            <!-- Interrogation Statements Section -->
            <div style="margin-bottom: 3rem; background: #f8fafc; border-radius: 15px; padding: 2rem; border: 1px solid var(--border-soft);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                        <i class="fa-solid fa-comments" style="color: #9b59b6;"></i> HADALLADA LAA QORAY (STATEMENTS)
                    </h3>
                    <button type="button" id="add-statement" class="btn" style="background: #9b59b6; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; cursor: pointer;">
                        <i class="fa-solid fa-plus"></i> Ku dar Hadal
                    </button>
                </div>
                
                <div id="statements-container">
                    <!-- Statements will be added here -->
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary" style="padding: 1rem 3rem; font-weight: 800; border-radius: 10px; border: none; box-shadow: 0 10px 20px rgba(52, 152, 219, 0.2);">
                    <i class="fa-solid fa-paper-plane"></i> GUDBI BAARITAANKA
                </button>
                <a href="{{ route('cases.show', $case) }}" class="btn" style="padding: 1rem 2rem; background: #f1f2f6; color: var(--text-sub); border-radius: 10px; font-weight: 700; text-decoration: none;">
                    Jooji
                </a>
            </div>
        </form>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem; background: #f8fafc; border: 1px dashed var(--border-soft);">
            <h4 style="color: var(--sidebar-bg); margin-bottom: 1rem; font-family: 'Outfit';">Xogta Kiiska</h4>
            <div style="font-size: 0.9rem; color: var(--text-sub); display: flex; flex-direction: column; gap: 0.8rem;">
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Dambiga:</strong> {{ $case->crime->crime_type }}</p>
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Goobta:</strong> {{ $case->crime->location }}</p>
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Taariikhda:</strong> {{ $case->crime->crime_date }}</p>
            </div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; background: var(--sidebar-bg); color: white; border: none;">
            <h4 style="margin-bottom: 1rem; font-family: 'Outfit';">Muhiim!</h4>
            <p style="font-size: 0.85rem; line-height: 1.6; opacity: 0.9;">
                Markaad gudbiso warbixintan, kiisku wuxuu si toos ah ugu gudbi doonaa wejiga <strong style="color: #f1c40f;">Xeer Ilaalinta</strong>. Hubi in dhammaan caddeymaha iyo xogtuba ay sax yihiin.
            </p>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#findings'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
            language: 'so'
        })
        .catch(error => {
            console.error(error);
        });

    Document.getElementById('add-statement').addEventListener('click', function() {
        // Collect existing people from the case (passed from PHP)
        const suspects = @json($case->crime->suspects ?? []);
        // Note: Assuming we might have victims relation later, for now just suspects
        
        let suspectOptions = '';
        if(suspects.length > 0) {
            suspectOptions = `<div style="margin-bottom: 0.5rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">`;
            suspects.forEach(s => {
                suspectOptions += `<button type="button" class="btn-sm btn-outline-secondary" style="font-size: 0.7rem; padding: 2px 6px; border: 1px solid #ddd; background: #eee; border-radius: 4px; cursor: pointer;" onclick="this.closest('.statement-row').querySelector('input[name=\\'statement_names[]\\']').value = '${s.name}'; this.closest('.statement-row').querySelector('select[name=\\'statement_types[]\\']').value = 'Eedeysane';">
                    <i class="fa-solid fa-user-plus"></i> ${s.name} (Eedeysane)
                </button>`;
            });
            suspectOptions += `</div>`;
        }

        const container = document.getElementById('statements-container');
        const statementHtml = `
            <div class="statement-row" style="background: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 1rem; border: 1px solid var(--border-soft); position: relative;">
                <button type="button" class="remove-statement" style="position: absolute; top: 10px; right: 10px; background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 1.2rem;">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
                ${suspectOptions}
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-sub); margin-bottom: 0.5rem;">MAGACA QOFKA</label>
                        <input type="text" name="statement_names[]" required class="form-control" placeholder="Tusaale: Axmed Cali" style="border: 1px solid var(--border-soft); border-radius: 8px; width: 100%; padding: 0.6rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-sub); margin-bottom: 0.5rem;">NOOCA</label>
                        <select name="statement_types[]" class="form-control" style="border: 1px solid var(--border-soft); border-radius: 8px; width: 100%; padding: 0.6rem;">
                            <option value="Eedeysane">Eedeysane</option>
                            <option value="Markhaati">Markhaati</option>
                            <option value="Dhibane">Dhibane</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-sub); margin-bottom: 0.5rem;">TAARIIKHDA</label>
                        <input type="date" name="statement_dates[]" required class="form-control" value="${new Date().toISOString().split('T')[0]}" style="border: 1px solid var(--border-soft); border-radius: 8px; width: 100%; padding: 0.6rem;">
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-sub); margin-bottom: 0.5rem;">HADALKA LAGA QORAY (STATEMENT)</label>
                    <textarea name="statements[]" required class="form-control" rows="4" placeholder="Halkan ku qor hadalka qofka..." style="border: 1px solid var(--border-soft); border-radius: 8px; width: 100%; padding: 0.8rem;"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', statementHtml);
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-statement')) {
            e.target.closest('.statement-row').remove();
        }
    });
</script>
@endsection
