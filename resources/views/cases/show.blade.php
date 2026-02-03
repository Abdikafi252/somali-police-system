@extends('layouts.app')

@section('title', 'Faahfaahinta Kiiska: ' . $case->case_number)

@section('content')
@php
    $isAssignedHero = auth()->user()->id == $case->assigned_to;
    $canSubmit = (auth()->user()->role->slug == 'cid' || auth()->user()->role->slug == 'askari' || auth()->user()->role->slug == 'admin') && $isAssignedHero;
    $isAdminOrCommander = in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol']);
    $inInvestigation = in_array($case->status, ['assigned', 'Baaris', 'Baarista-CID']);
@endphp

<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">{{ $case->case_number }}</h1>
        <p style="color: var(--text-sub);">Faahfaahinta baaritaanka iyo heerarka dacwad qaadista.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('cases.index') }}" class="btn" style="background: white; border: 1px solid var(--border-soft); color: var(--text-sub); text-decoration: none; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
            <i class="fa-solid fa-arrow-left"></i> Ku laabo liiska
        </a>
    </div>
</div>

<!-- Case Progress Stepper -->
<div class="glass-card" style="margin-bottom: 2rem; padding: 1.5rem 0;">
    <x-case-stepper :currentStatus="$case->status" />
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Crime Details -->
        <div class="glass-card" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <h3 style="color: var(--sidebar-bg); font-family: 'Outfit'; font-weight: 700; margin: 0;">
                    <i class="fa-solid fa-file-invoice" style="color: var(--accent-color); margin-right: 0.5rem;"></i> WARBIXINTA DAMBIGA
                </h3>
                <span style="background: rgba(52, 152, 219, 0.1); color: var(--accent-color); padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem;">
                    {{ $case->crime->crime_type }}
                </span>
            </div>
            
            <p style="line-height: 1.8; color: #4b6584; font-size: 1.05rem; background: #fbfbfc; padding: 1.5rem; border-radius: 12px; border: 1px solid #f1f2f6; margin-bottom: 1.5rem;">
                {{ $case->crime->description }}
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.8rem; color: var(--text-sub);">
                    <div style="width: 35px; height: 35px; border-radius: 8px; background: #f1f2f6; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg);">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; margin: 0; font-weight: 600;">GOOBTA</p>
                        <p style="font-size: 0.9rem; margin: 0; color: var(--sidebar-bg); font-weight: 700;">{{ $case->crime->location }}</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.8rem; color: var(--text-sub);">
                    <div style="width: 35px; height: 35px; border-radius: 8px; background: #f1f2f6; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg);">
                        <i class="fa-solid fa-calendar"></i>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; margin: 0; font-weight: 600;">TAARIIKHDA</p>
                        <p style="font-size: 0.9rem; margin: 0; color: var(--sidebar-bg); font-weight: 700;">{{ \Carbon\Carbon::parse($case->crime->crime_date)->format('d M Y | h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suspects -->
        <div class="glass-card" style="padding: 2rem;">
            <h3 style="color: var(--sidebar-bg); font-family: 'Outfit'; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.6rem;">
                <i class="fa-solid fa-users-viewfinder" style="color: #e67e22;"></i> DAMBIILAYAASHA LA TUHUMAYO
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @forelse($case->crime->suspects as $suspect)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: white; border: 1px solid var(--border-soft); border-radius: 12px; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    @if($suspect->photo)
                        <img src="{{ asset('storage/' . $suspect->photo) }}" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($suspect->name) }}&size=60&background=FDF2F2&color=E67E22&font-size=0.4" style="border-radius: 12px;">
                    @endif
                    <div style="flex: 1;">
                        <h4 style="margin: 0; font-size: 1rem; color: var(--sidebar-bg);">{{ $suspect->name }}</h4>
                        <p style="font-size: 0.75rem; color: var(--text-sub); margin: 0.2rem 0;">ID: {{ $suspect->national_id }}</p>
                        <span style="font-size: 0.7rem; font-weight: 700; color: {{ $suspect->arrest_status == 'arrested' ? '#2ecc71' : '#e67e22' }};">
                            <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> {{ strtoupper($suspect->arrest_status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem; background: #fdfdfd; border-radius: 12px; border: 1px dashed var(--border-soft);">
                    <p style="color: var(--text-sub); margin: 0;">Ma jiro dambiile lagu daray kiiskan weli.</p>
                </div>
                @endforelse
            </div>
        </div>
            <!-- Investigation Diary (Progress Logs) -->
        <div class="glass-card" style="margin-top: 0rem; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="margin: 0; font-family: 'Outfit'; font-weight: 800; color: var(--sidebar-bg); display: flex; align-items: center; gap: 0.8rem;">
                    <i class="fa-solid fa-book-open-reader" style="color: var(--accent-teal);"></i> DIWAANKA BAARISTA (INVESTIGATION DIARY)
                </h3>
                <div style="display: flex; gap: 0.8rem;">
                    @if(($canSubmit || $isAdminOrCommander) && $inInvestigation && !$case->investigation)
                    <a href="{{ route('investigations.create', ['case_id' => $case->id]) }}" class="btn" style="padding: 0.6rem 1.2rem; font-size: 0.85rem; background: #e74c3c; color: white; border: none; text-decoration: none; border-radius: 8px; font-weight: 800; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-clipboard-check"></i> GUDIBI NATIIJADA
                    </a>
                    @endif

                    @if(auth()->user()->role->slug == 'cid' && $case->assigned_to == auth()->id() && $inInvestigation)
                    <button onclick="document.getElementById('add-log-modal').style.display='flex'" class="btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.85rem; background: var(--accent-teal); border: none;">
                        <i class="fa-solid fa-plus-circle"></i> KU DAR XOG CUSUB
                    </button>
                    @endif
                </div>
            </div>

            @if($case->logs->count() > 0)
            <div class="diary-timeline" style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($case->logs->sortByDesc('entry_date') as $log)
                <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid var(--border-soft); box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span style="font-size: 0.7rem; font-weight: 800; background: rgba(52, 152, 219, 0.1); color: #3498db; padding: 4px 10px; border-radius: 20px; text-transform: uppercase;">
                            {{ $log->log_type }}
                        </span>
                        <span style="font-size: 0.75rem; color: var(--text-sub); font-weight: 600;">
                            <i class="fa-regular fa-clock"></i> {{ $log->entry_date->format('d/m/Y | h:i A') }}
                        </span>
                    </div>
                    <p style="margin: 0; line-height: 1.6; color: #34495e; font-size: 0.95rem;">{{ $log->log_entry }}</p>
                    <div style="margin-top: 1rem; border-top: 1px solid #f8fafc; padding-top: 0.8rem; font-size: 0.75rem; color: var(--text-sub);">
                        <i class="fa-solid fa-user-pen"></i> Baaraha: <strong>{{ $log->officer->name }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 3rem; background: #fdfdfd; border-radius: 15px; border: 2px dashed var(--border-soft);">
                <i class="fa-solid fa-pen-nib fa-3x" style="opacity: 0.1; margin-bottom: 1rem;"></i>
                <p style="color: var(--text-sub); font-weight: 600;">Weli ma jiro wax xog ah oo la galiyey diwaanka baarista.</p>
            </div>
            @endif

            <!-- Modal for adding log (Hidden by default) -->
            <div id="add-log-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
                <div class="glass-card" style="width: 500px; padding: 2rem; background: white;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h4 style="margin: 0; font-family: 'Outfit'; font-weight: 800;">KU DAR XOGTA BAARISTA</h4>
                        <button onclick="document.getElementById('add-log-modal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                    </div>
                    <form action="{{ route('investigation-logs.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="case_id" value="{{ $case->id }}">
                        
                        <div style="margin-bottom: 1.2rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">Nooca Xogta (Type)</label>
                            <select name="log_type" class="form-control" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid var(--border-soft);">
                                <option value="General">Warbixin Guud</option>
                                <option value="Evidence">Diiwaangelin Caddeyn</option>
                                <option value="Interview">Waraysi/Su'aalo</option>
                                <option value="Scene Visit">Kormeerka Goobta</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 1.2rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">Taariikhda & Waqtiga</label>
                            <input type="datetime-local" name="entry_date" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid var(--border-soft);">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">Faahfaahinta Xogta</label>
                            <textarea name="log_entry" rows="5" class="form-control" placeholder="Halkan ku qor waxa aad soo ogaatay..." required style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-soft);"></textarea>
                        </div>

                        <button type="submit" class="btn-primary" style="width: 100%; padding: 1rem; border-radius: 10px; background: var(--sidebar-bg); border: none; font-weight: 800;">
                            <i class="fa-solid fa-save"></i> DIIWAANGELI XOGTA
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if($case->investigation)
        <!-- Investigation Findings -->
        <div class="glass-card" style="padding: 2rem; border-left: 5px solid #27ae60;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="color: var(--sidebar-bg); font-family: 'Outfit'; font-weight: 700; margin: 0;">
                    <i class="fa-solid fa-magnifying-glass-chart" style="color: #27ae60; margin-right: 0.5rem;"></i> NATIIJADA BAARISTA (CID)
                </h3>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    @if($case->investigation->outcome)
                    <div style="background: #27ae60; color: white; padding: 0.4rem 1.2rem; border-radius: 30px; font-weight: 900; font-size: 0.85rem; box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3); border: 2px solid white;">
                        <i class="fa-solid fa-gavel"></i> {{ strtoupper($case->investigation->outcome) }}
                    </div>
                    @endif
                    <a href="{{ route('investigations.report', $case->investigation->id) }}" target="_blank" class="btn" style="background: white; border: 1px solid #27ae60; color: #27ae60; text-decoration: none; padding: 0.4rem 1rem; border-radius: 6px; font-weight: 700; font-size: 0.8rem;">
                        <i class="fa-solid fa-print"></i> Daabac Warbixinta
                    </a>
                </div>
            </div>
            <div style="background: rgba(39, 174, 96, 0.05); padding: 2.5rem; border-radius: 12px; border: 1px solid rgba(39, 174, 96, 0.1);">
                <div class="rich-text-content" style="line-height: 1.8; color: #2d3436; font-size: 1.05rem;">
                    {!! $case->investigation->findings !!}
                </div>
                
                @if($case->investigation->evidence_list)
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px dashed rgba(39, 174, 96, 0.2);">
                    <h5 style="margin-bottom: 1rem; color: #27ae60; font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fa-solid fa-box-archive"></i> CADDEYMAHA (EVIDENCE LIST)
                    </h5>
                    <div style="color: #4b6584; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem;">
                        {{ $case->investigation->evidence_list }}
                    </div>

                    @if($case->investigation->files && count($case->investigation->files) > 0)
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem;">
                        @foreach($case->investigation->files as $file)
                        <div style="position: relative; border-radius: 10px; overflow: hidden; border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                <img src="{{ asset('storage/' . $file) }}" style="width: 100%; height: 100px; object-fit: cover;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                @if($case->investigation->statements && count($case->investigation->statements) > 0)
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px dashed rgba(39, 174, 96, 0.2);">
                    <h5 style="margin-bottom: 1.5rem; color: #9b59b6; font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fa-solid fa-comments"></i> HADALLADA Laga QORAY (INTERROGATION LOGS)
                    </h5>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($case->investigation->statements as $statement)
                        <div style="background: white; padding: 1.2rem; border-radius: 12px; border: 1px solid var(--border-soft); box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                                <div style="display: flex; align-items: center; gap: 0.8rem;">
                                    <div style="width: 35px; height: 35px; border-radius: 8px; background: #f3e5f5; color: #9b59b6; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                                        {{ substr($statement->person_type, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 style="margin: 0; font-size: 0.95rem; color: var(--sidebar-bg); font-weight: 700;">{{ $statement->person_name }}</h6>
                                        <span style="font-size: 0.7rem; color: #9b59b6; font-weight: 700; text-transform: uppercase;">{{ $statement->person_type }}</span>
                                    </div>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-sub); font-weight: 600;">
                                    <i class="fa-regular fa-calendar-check"></i> {{ \Carbon\Carbon::parse($statement->statement_date)->format('d/m/Y') }}
                                </div>
                            </div>
                            <p style="margin: 0; font-size: 0.9rem; color: #4b6584; line-height: 1.6; font-style: italic; border-left: 3px solid #ecf0f1; padding-left: 1rem;">
                                "{{ $statement->statement }}"
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Info -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Status Card -->
        <div class="glass-card" style="text-align: center; padding: 2rem;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: #f0f4f8; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--sidebar-bg); border: 2px solid var(--border-soft);">
                <i class="fa-solid fa-shield-halved fa-xl"></i>
            </div>
            <h5 style="color: var(--text-sub); font-size: 0.75rem; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 1px;">Status-ka Kiiskan</h5>
            <div style="padding: 1rem; border-radius: 12px; font-weight: 900; font-size: 1.3rem; background: var(--sidebar-bg); color: white; box-shadow: 0 10px 20px rgba(45, 74, 83, 0.2);">
                {{ $case->status }}
            </div>
        </div>

        <!-- Officer Card -->
        <div class="glass-card" style="padding: 1.5rem;">
            <h5 style="color: var(--text-sub); font-size: 0.75rem; text-transform: uppercase; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                SARKAALKA BAARISTA <i class="fa-solid fa-id-badge"></i>
            </h5>
            @if($case->assignedOfficer)
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($case->assignedOfficer->name) }}&background=F0F4F8&color=3498db&size=50&font-size=0.4" style="border-radius: 12px; border: 1px solid var(--border-soft);">
                <div>
                    <div style="font-weight: 700; color: var(--sidebar-bg); font-size: 1.1rem;">{{ $case->assignedOfficer->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--accent-teal); font-weight: 700;">WAXAXDA CID</div>
                </div>
            </div>
            @else
                <div style="color: #e74c3c; font-weight: 700; text-align: center; padding: 1rem; background: rgba(231, 76, 60, 0.05); border-radius: 8px;">
                    <i class="fa-solid fa-user-slash"></i> Lama magacaabin
                </div>
            @endif
        </div>

        <!-- Actions -->

        @if(($canSubmit || $isAdminOrCommander) && $inInvestigation && !$case->investigation)
        <div class="glass-card" style="background: linear-gradient(135deg, #1abc9c, #16a085); color: white; border: none; padding: 2rem; box-shadow: 0 15px 30px rgba(26, 188, 156, 0.3);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background: rgba(255,255,255,0.2); width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-file-signature fa-lg"></i>
                </div>
                <h4 style="margin: 0; font-family: 'Outfit'; font-weight: 800; letter-spacing: 0.5px;">GUDIBI BAARISTA</h4>
            </div>
            <p style="font-size: 0.8rem; margin-bottom: 1.5rem; opacity: 0.9; line-height: 1.6;">
                @if($isAdminOrCommander && !$isAssignedHero)
                    <i class="fa-solid fa-shield-halved"></i> <strong>Mode: Taliye/Admin</strong><br>
                @endif
                Haddii baaritaanka dhammaaday, halkan ka xaree natiijada rasmiga ah si loo horgaliyo maxkamadda.
            </p>
            <a href="{{ route('investigations.create', ['case_id' => $case->id]) }}" class="btn-primary" style="background: white; color: #16a085; text-align: center; text-decoration: none; display: block; width: 100%; border-radius: 10px; font-weight: 900; border: none; padding: 1.1rem; font-size: 0.95rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-clipboard-check"></i> XAREE NATIIJADA BAARISTA
            </a>
        </div>
        @endif

        @if(auth()->user()->role->slug == 'prosecutor' && $case->status == 'Xeer-Ilaalinta' && $case->investigation)
        <div class="glass-card" style="background: linear-gradient(135deg, #8e44ad, #9b59b6); color: white; border: none;">
            <h4 style="margin-bottom: 0.8rem; font-family: 'Outfit';">GUDBI DACWADDA</h4>
            <p style="font-size: 0.8rem; margin-bottom: 1.5rem; opacity: 0.9; line-height: 1.5;">Ka xeer ilaaliye ahaan, halkan ku diyaari dacwadda si aad ugu gudbiso Maxkamadda.</p>
            <a href="{{ route('prosecutions.create', ['case_id' => $case->id]) }}" class="btn-primary" style="background: white; color: #8e44ad; text-align: center; text-decoration: none; display: block; width: 100%; border-radius: 8px; font-weight: 800; border: none; padding: 1rem;">
                <i class="fa-solid fa-gavel"></i> GUDBI MAXKAMADDA
            </a>
        </div>
        @endif

        @if((auth()->user()->role->slug == 'judge' || auth()->user()->role->slug == 'admin') && $case->status == 'Maxkamadda' && $case->prosecution)
        <div class="glass-card" style="background: linear-gradient(135deg, #2d3436, #636e72); color: white; border: none; padding: 2rem; box-shadow: 0 15px 30px rgba(0,0,0,0.2);">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="background: rgba(255,255,255,0.2); width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-scale-balanced fa-lg"></i>
                </div>
                <h4 style="margin: 0; font-family: 'Outfit'; font-weight: 800;">SOO SAAR XUKUN</h4>
            </div>
            <p style="font-size: 0.8rem; margin-bottom: 1.5rem; opacity: 0.9; line-height: 1.6;">Garsoore, halkan ku soo saar xukunka rasmiga ah ee maxkamadda si kiiska loo soo afjaro.</p>
            <a href="{{ route('court-cases.create', ['prosecution_id' => $case->prosecution->id]) }}" class="btn-primary" style="background: white; color: #2d3436; text-align: center; text-decoration: none; display: block; width: 100%; border-radius: 10px; font-weight: 900; border: none; padding: 1.1rem; font-size: 0.95rem;">
                <i class="fa-solid fa-check-double"></i> XUKUNKA RIDO
            </a>
        </div>
        @endif

        <!-- Event Timeline -->
        <div class="glass-card" style="padding: 1.5rem;">
            <h5 style="color: var(--text-sub); font-size: 0.75rem; text-transform: uppercase; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                TAARIIKHDA DHACDOOYINKA <i class="fa-solid fa-clock-rotate-left"></i>
            </h5>
            <div style="position: relative; padding-left: 1.5rem; border-left: 2px dashed var(--border-soft);">
                <!-- Crime Reported -->
                <div style="position: relative; margin-bottom: 1.5rem;">
                    <div style="position: absolute; left: -1.95rem; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #e74c3c; border: 2px solid white;"></div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">Dambiga waa la diiwaangeliyay</div>
                    <div style="font-size: 0.7rem; color: var(--text-sub);">{{ $case->crime->created_at->format('d M Y | h:i A') }}</div>
                </div>

                <!-- Case Opened -->
                <div style="position: relative; margin-bottom: 1.5rem;">
                    <div style="position: absolute; left: -1.95rem; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #3498db; border: 2px solid white;"></div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">Kiiska waa la furay</div>
                    <div style="font-size: 0.7rem; color: var(--text-sub);">{{ $case->created_at->format('d M Y | h:i A') }}</div>
                </div>

                @if($case->investigation)
                    <!-- Investigation Submitted -->
                    <div style="position: relative; margin-bottom: 1.5rem;">
                        <div style="position: absolute; left: -1.95rem; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #27ae60; border: 2px solid white;"></div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">Baaritaanka CID-da waa la gudbiyay</div>
                        <div style="font-size: 0.7rem; color: var(--text-sub);">{{ $case->investigation->created_at->format('d M Y | h:i A') }}</div>
                    </div>

                    @foreach($case->investigation->statements as $statement)
                        <div style="position: relative; margin-bottom: 1.5rem;">
                            <div style="position: absolute; left: -1.95rem; top: 0; width: 10px; height: 10px; border-radius: 50%; background: #9b59b6; border: 2px solid white;"></div>
                            <div style="font-size: 0.8rem; font-weight: 600; color: var(--sidebar-bg);">Hadal laga qoray: {{ $statement->person_name }}</div>
                            <div style="font-size: 0.65rem; color: var(--text-sub);">{{ \Carbon\Carbon::parse($statement->statement_date)->format('d M Y') }}</div>
                        </div>
                    @endforeach
                @endif

                @if($case->prosecution)
                    <!-- Prosecution Submitted -->
                    <div style="position: relative; margin-bottom: 1.5rem;">
                        <div style="position: absolute; left: -1.95rem; top: 0; width: 12px; height: 12px; border-radius: 50%; background: #8e44ad; border: 2px solid white;"></div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">Dacwad oogista waa la gudbiyay</div>
                        <div style="font-size: 0.7rem; color: var(--text-sub);">{{ $case->prosecution->created_at->format('d M Y | h:i A') }}</div>
                    </div>

                    @if($case->prosecution->courtCase)
                        <!-- Court Verdict Issued -->
                        <div style="position: relative; margin-bottom: 1.5rem;">
                            <div style="position: absolute; left: -1.95rem; top: 0; width: 14px; height: 14px; border-radius: 50%; background: #2d3436; border: 2px solid white;"></div>
                            <div style="font-size: 0.85rem; font-weight: 800; color: var(--sidebar-bg);">XUKUNKA MAXKAMADDA WAA LA SOO SAARAY</div>
                            <div style="font-size: 0.7rem; color: #f39c12; font-weight: 700;">KIISKA WAA XIRAN (CLOSED)</div>
                            <div style="font-size: 0.65rem; color: var(--text-sub);">{{ $case->prosecution->courtCase->created_at->format('d M Y | h:i A') }}</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

