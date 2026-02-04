@extends('layouts.app')

@section('content')
<div class="dashboard-grid" style="grid-template-columns: 1fr;">
    <div class="edu-card">
        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <h2 style="margin: 0; color: #1f2937;">Diiwaangalinta Dhacdo Cusub (Unified Incident Report)</h2>
            <p style="margin: 0.5rem 0 0; color: var(--text-muted); font-size: 0.9rem;">Fadlan buuxi xogta Eedeysanaha, Dhibanaha, iyo Faahfaahinta Dhacdada.</p>
        </div>

        @if(session('error'))
        <div style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('cases.store-unified') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                
                <!-- 1. XOGTA EEDEYSANAHA (SUSPECT DETAILS) -->
                <div>
                     <h3 style="color: var(--accent-lime); font-size: 1rem; margin-bottom: 1rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid var(--accent-lime); padding-bottom: 5px;">1. XOGTA EEDEYSANAHA (SUSPECT INFO)</h3>

                     <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; font-size: 0.85rem;">Magaca Buuxa (Full Name)</label>
                        <input type="text" name="suspect_name" class="form-control" required style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Naaneysta (Nickname)</label>
                            <input type="text" name="suspect_nickname" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Da'da (Age)</label>
                            <input type="number" name="suspect_age" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; font-size: 0.85rem;">Magaca Hooyada (Mother's Name)</label>
                        <input type="text" name="suspect_mother_name" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Jinsiga (Gender)</label>
                            <select name="suspect_gender" class="form-control" required style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                                <option value="Male">Lab</option>
                                <option value="Female">Dhedig</option>
                            </select>
                        </div>
                         <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Heerka Xariga (Status)</label>
                             <select name="suspect_status" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;" required>
                                <option value="In Custody">In Custody (Xiran)</option>
                                <option value="At Large">At Large (Baxsad)</option>
                                <option value="Released">Released (La daayay)</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Deggan (Residence)</label>
                            <input type="text" name="suspect_residence" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                         <div>
                             <label style="font-weight: 600; font-size: 0.85rem;">Aqoonsi (National ID)</label>
                             <input type="text" name="suspect_national_id" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                    </div>

                     <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; font-size: 0.85rem;">Sawirka (Photo)</label>
                        <input type="file" name="suspect_photo" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #fff;">
                    </div>
                
                    <!-- Crime Details grouped under Suspect section as requested -->
                    <div style="margin-top: 1.5rem; border-top: 1px dashed #e5e7eb; padding-top: 1rem;">
                         <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 600; font-size: 0.85rem; color: #4b5563;">Nuuca Dambiga (Crime Type)</label>
                            <input type="text" name="crime_type" class="form-control" required placeholder="Geli nooca dambiga (e.g. Xatooyo, Dil...)" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                         </div>

                         <!-- Assignment Section (added as requested) -->
                         <div style="margin-bottom: 1rem;">
                            <label style="font-weight: 600; font-size: 0.85rem; color: #4b5563;">Xilsaar Baare (Assign Investigator)</label>
                            <select name="assigned_to" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                                <option value="">-- Dooro Baare (Optional) --</option>
                                @if(isset($officers))
                                    @foreach($officers as $officer)
                                        <option value="{{ $officer->id }}" {{ auth()->id() == $officer->id ? 'selected' : '' }}>{{ $officer->name }} ({{ $officer->station->name ?? 'Headquarters' }})</option>
                                    @endforeach
                                @endif
                            </select>
                         </div>
                         
                         <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                             <div>
                                <label style="font-weight: 600; font-size: 0.85rem; color: #4b5563;">Goobta (Location)</label>
                                <input type="text" name="location" class="form-control" required style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                             </div>
                             <div>
                                <label style="font-weight: 600; font-size: 0.85rem; color: #4b5563;">Waqtiga (Date & Time)</label>
                                <input type="datetime-local" name="crime_date" class="form-control" required style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                             </div>
                         </div>
                    </div>
                </div>

                <!-- 2. XOGTA DHIBANAHA (VICTIM DETAILS) -->
                <div>
                    <h3 style="color: #ef4444; font-size: 1rem; margin-bottom: 1rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #ef4444; padding-bottom: 5px;">2. XOGTA DHIBANAHA (VICTIM INFO)</h3>
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; font-size: 0.85rem;">Magaca Dhibanaha</label>
                        <input type="text" name="victim_name" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;" placeholder="Haddii la yaqaan">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Da'da</label>
                            <input type="number" name="victim_age" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="font-weight: 600; font-size: 0.85rem;">Jinsiga</label>
                            <select name="victim_gender" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                                <option value="">Dooro</option>
                                <option value="Male">Lab</option>
                                <option value="Female">Dhedig</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="font-weight: 600; font-size: 0.85rem;">Dhibka gaaray (Injury/Harm)</label>
                        <textarea name="victim_injury" rows="3" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;" placeholder="Sharaxaad kooban oo dhibka gaaray"></textarea>
                    </div>
                </div>

            </div>

             <!-- 3. FAAHFAAHINTA DHACDADA -->
             <div style="margin-top: 1.5rem;">
                <h3 style="color: #3b82f6; font-size: 1rem; margin-bottom: 1rem; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #3b82f6; padding-bottom: 5px;">3. FAAHFAAHINTA DHACDADA (INCIDENT DESCRIPTION)</h3>
                <textarea name="description" rows="6" placeholder="Qor faahfaahinta sida ay wax u dhaceen..." required style="width: 100%; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb; font-family: inherit; font-size: 0.95rem;"></textarea>
            </div>

            <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('cases.index') }}" style="padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; color: #4b5563; font-weight: 600;">Cancel</a>
                <button type="submit" style="background: var(--accent-lime); color: #1C1E26; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; cursor: pointer;">Save Record</button>
            </div>
        </form>
    </div>
</div>
@endsection
