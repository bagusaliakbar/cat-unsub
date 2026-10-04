<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nomor Meja Peserta - CAT Universitas Subang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        /* 4 Cards per A4 Sheet (2 columns x 2 rows) */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 95mm);
            gap: 8mm 6mm;
            justify-content: center;
            padding: 10px 0;
        }

        .desk-card {
            width: 95mm;
            height: 135mm;
            background: #ffffff;
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            break-inside: avoid;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Blue Theme (Default Sesi 1) */
        .theme-blue {
            border: 2.5px solid #0284c7;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.15);
        }
        .theme-blue .banner-box {
            background: linear-gradient(135deg, #0f2b5c 0%, #1d4ed8 100%);
            color: #ffffff;
        }
        .theme-blue .session-title {
            color: #0f2b5c;
        }
        .theme-blue .session-time {
            color: #0f2b5c;
        }
        .theme-blue .number-box {
            border: 2.5px solid #0284c7;
        }
        .theme-blue .divider-line {
            background-color: #38bdf8;
        }
        .theme-blue .wave-blue {
            display: block !important;
        }
        .theme-blue .wave-orange {
            display: none !important;
        }

        /* Orange / Yellow Theme (Default Sesi 2) */
        .theme-orange {
            border: 2.5px solid #f59e0b;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.15);
        }
        .theme-orange .banner-box {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            color: #111827;
        }
        .theme-orange .session-title {
            color: #111827;
        }
        .theme-orange .session-time {
            color: #111827;
        }
        .theme-orange .number-box {
            border: 2.5px solid #f59e0b;
        }
        .theme-orange .divider-line {
            background-color: #fbbf24;
        }
        .theme-orange .wave-blue {
            display: none !important;
        }
        .theme-orange .wave-orange {
            display: block !important;
        }

        /* Print Specific Rules */
        @media print {
            @page {
                size: A4 portrait;
                margin: 8mm 6mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .cards-grid {
                padding: 0;
                gap: 8mm 6mm;
            }
            .desk-card {
                box-shadow: none !important;
            }
            .page-break {
                page-break-after: always;
                break-after: page;
            }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Top Toolbar (Screen Only) -->
    <div class="no-print sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 px-4 py-3 shadow-sm">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-800 p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-sm font-bold text-gray-900 leading-tight">Cetak Nomor Meja Peserta CAT</h1>
                    <p class="text-xs text-gray-500">Total: <strong class="text-blue-600">{{ count($participants) }}</strong> nomor meja (4 kartu per lembar A4)</p>
                </div>
            </div>

            <!-- Toolbar Controls -->
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Theme Selector -->
                <div class="flex items-center space-x-1.5 bg-gray-100 p-1 rounded-xl text-xs font-semibold">
                    <button onclick="setGlobalTheme('auto')" id="btn-theme-auto" class="theme-btn px-2.5 py-1 rounded-lg bg-white shadow-xs text-gray-800">
                        Auto (Sesi)
                    </button>
                    <button onclick="setGlobalTheme('blue')" id="btn-theme-blue" class="theme-btn px-2.5 py-1 rounded-lg text-gray-600 hover:text-gray-900">
                        Semua Biru
                    </button>
                    <button onclick="setGlobalTheme('orange')" id="btn-theme-orange" class="theme-btn px-2.5 py-1 rounded-lg text-gray-600 hover:text-gray-900">
                        Semua Oranye
                    </button>
                </div>

                <!-- Show Name Checkbox -->
                <label class="inline-flex items-center space-x-1.5 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-xl border border-gray-200 cursor-pointer">
                    <input type="checkbox" id="toggle-name-checkbox" checked onchange="toggleParticipantNames(this.checked)" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                    <span>Nama Peserta</span>
                </label>

                <!-- Print Button -->
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-xl shadow-md shadow-blue-500/20 text-xs sm:text-sm flex items-center transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Nomor Meja
                </button>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <main class="max-w-[210mm] mx-auto py-6 px-2 sm:px-0">
        <div class="cards-grid">
            @forelse($participants as $index => $participant)
                @php
                    $waveName = $participant->wave->name ?? '';
                    $waveId = $participant->wave_id ?? 1;

                    // Extract session number if any, e.g. "Gelombang 2" -> 2, "Sesi 2" -> 2
                    $sessionNum = 1;
                    if (preg_match('/(\d+)/', $waveName, $m)) {
                        $sessionNum = (int)$m[1];
                    } elseif ($waveId > 1) {
                        $sessionNum = $waveId;
                    }

                    // Automatic theme color based on session (odd: blue, even: orange)
                    $cardTheme = ($sessionNum % 2 === 0) ? 'orange' : 'blue';
                    if (!empty($forcedTheme) && $forcedTheme !== 'auto') {
                        $cardTheme = $forcedTheme;
                    }

                    // Session title
                    $cardSession = $session ?? ('SESI ' . $sessionNum);

                    // Session time
                    $exam = $participant->assignedExams()->first();
                    if (!empty($time)) {
                        $cardTime = $time;
                    } elseif ($exam && $exam->start_time && $exam->end_time) {
                        $cardTime = \Carbon\Carbon::parse($exam->start_time)->format('H.i') . ' – ' . \Carbon\Carbon::parse($exam->end_time)->format('H.i');
                    } elseif ($sessionNum == 2) {
                        $cardTime = '11.10 – 12.40';
                    } elseif ($sessionNum == 3) {
                        $cardTime = '13.30 – 15.00';
                    } else {
                        $cardTime = '09.00 – 10.40';
                    }

                    // Lab name
                    $cardLab = $lab ?? ($exam->location ?? 'LAB CAT 1');
                    if (strlen($cardLab) > 16) {
                        $cardLab = 'LAB CAT 1';
                    }

                    // Number display (format 02, 52)
                    $rawNoMeja = $participant->no_meja;
                    if (!empty($rawNoMeja)) {
                        $displayNoMeja = is_numeric($rawNoMeja) ? sprintf('%02d', (int)$rawNoMeja) : $rawNoMeja;
                    } else {
                        $displayNoMeja = sprintf('%02d', $index + 1);
                    }
                @endphp

                <!-- Card -->
                <div class="desk-card theme-{{ $cardTheme }}" data-theme="{{ $cardTheme }}" data-auto-theme="{{ ($sessionNum % 2 === 0) ? 'orange' : 'blue' }}">
                    
                    <!-- Top Section: Header & Wave -->
                    <div class="relative w-full">
                        <!-- Logo & Title Bar -->
                        <div class="pt-3 px-3.5 pb-1 flex items-center justify-between relative z-10">
                            <!-- Left: Logo & UNSUB -->
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo UNSUB" class="h-9 w-auto object-contain shrink-0">
                                <div class="font-extrabold text-[#0f2b5c] text-[11px] leading-[1.15] tracking-tight">
                                    UNIVERSITAS<br>SUBANG
                                </div>
                            </div>

                            <!-- Vertical Divider -->
                            <div class="h-7 w-[1.5px] divider-line mx-2"></div>

                            <!-- Right: CAT & Subtitle -->
                            <div class="flex items-center space-x-1.5 shrink-0">
                                <span class="font-black text-2xl tracking-tighter text-[#0f2b5c] leading-none">CAT</span>
                                <div class="text-[7.5px] font-bold text-[#0f2b5c] leading-[1.15]">
                                    Seleksi Bakal Calon<br>Kepala Desa {{ date('Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Top Wave Graphic (SVG) -->
                        <div class="w-full -mt-0.5 relative z-0">
                            <!-- Blue Wave -->
                            <svg viewBox="0 0 360 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto wave-blue wave-svg">
                                <path d="M0 0C60 22 140 32 220 18C280 8 330 16 360 24V48H0V0Z" fill="url(#blue_grad_back_{{ $index }})" opacity="0.35"/>
                                <path d="M0 8C70 30 150 36 240 18C290 8 330 14 360 20V0H0V8Z" fill="url(#blue_grad_front_{{ $index }})"/>
                                <defs>
                                    <linearGradient id="blue_grad_front_{{ $index }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#38bdf8"/>
                                        <stop offset="50%" stop-color="#0284c7"/>
                                        <stop offset="100%" stop-color="#1d4ed8"/>
                                    </linearGradient>
                                    <linearGradient id="blue_grad_back_{{ $index }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#bae6fd"/>
                                        <stop offset="100%" stop-color="#0284c7"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <!-- Orange Wave -->
                            <svg viewBox="0 0 360 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto wave-orange wave-svg">
                                <path d="M0 0C60 22 140 32 220 18C280 8 330 16 360 24V48H0V0Z" fill="url(#orange_grad_back_{{ $index }})" opacity="0.45"/>
                                <path d="M0 8C70 30 150 36 240 18C290 8 330 14 360 20V0H0V8Z" fill="url(#orange_grad_front_{{ $index }})"/>
                                <defs>
                                    <linearGradient id="orange_grad_front_{{ $index }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#fde047"/>
                                        <stop offset="50%" stop-color="#f59e0b"/>
                                        <stop offset="100%" stop-color="#d97706"/>
                                    </linearGradient>
                                    <linearGradient id="orange_grad_back_{{ $index }}" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#fef08a"/>
                                        <stop offset="100%" stop-color="#f59e0b"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>

                    <!-- Middle Content Section -->
                    <div class="px-3.5 flex-1 flex flex-col justify-around -mt-2">
                        
                        <!-- Lab CAT Banner -->
                        <div class="banner-box rounded-xl px-3 py-2 flex items-center justify-between shadow-xs">
                            <!-- Left: Computer Icon -->
                            <div class="flex items-center space-x-2.5">
                                <svg class="w-7 h-7 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect x="2" y="3" width="20" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4"/>
                                </svg>
                                <div class="h-6 w-[1.5px] bg-current opacity-40"></div>
                                <div class="leading-none">
                                    <div class="font-black text-sm tracking-wider uppercase">{{ $cardLab }}</div>
                                    <div class="text-[7.5px] font-bold tracking-[0.2em] uppercase opacity-90 mt-0.5">LABORATORIUM KOMPUTER</div>
                                </div>
                            </div>

                            <!-- Right: Modern Dot Grid Accent -->
                            <div class="grid grid-cols-4 gap-1 opacity-50 shrink-0">
                                @for($d = 0; $d < 12; $d++)
                                    <div class="w-1 h-1 rounded-full bg-current"></div>
                                @endfor
                            </div>
                        </div>

                        <!-- Session Title & Time -->
                        <div class="text-center py-1">
                            <h2 class="session-title font-black text-2xl sm:text-[26px] tracking-tight uppercase leading-none">
                                {{ $cardSession }}
                            </h2>
                            <p class="session-time font-bold text-xs sm:text-[13px] tracking-normal mt-1">
                                ({{ $cardTime }})
                            </p>
                        </div>

                        <!-- Nomor Urut Peserta & Big Box -->
                        <div class="w-full">
                            <div class="text-center font-black text-[10.5px] tracking-[0.16em] text-[#1d4ed8] uppercase mb-1">
                                NOMOR URUT PESERTA
                            </div>

                            <div class="number-box mx-4 rounded-2xl bg-white py-1 flex items-center justify-center shadow-xs">
                                <span class="font-black text-5xl sm:text-[52px] leading-none text-black tracking-tight select-none">
                                    {{ $displayNoMeja }}
                                </span>
                            </div>

                            <!-- Participant Identity Info (Toggleable) -->
                            <div class="participant-name-container text-center mt-1.5 px-2">
                                <div class="text-[10px] font-bold text-gray-800 uppercase tracking-tight truncate">
                                    {{ $participant->name }}
                                </div>
                                <div class="text-[8px] font-semibold text-gray-500 uppercase tracking-tight truncate">
                                    {{ $participant->desa ? 'Desa ' . $participant->desa : '' }} {{ $participant->kecamatan ? '• Kec. ' . $participant->kecamatan : '' }}
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Wave Section (SVG) -->
                    <div class="w-full relative z-0 mt-auto">
                        <!-- Blue Bottom Wave -->
                        <svg viewBox="0 0 360 46" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto wave-blue bottom-wave-svg">
                            <path d="M0 24C60 8 130 18 200 32C260 42 320 28 360 14V46H0V24Z" fill="url(#bottom_blue_back_{{ $index }})" opacity="0.45"/>
                            <path d="M0 32C70 14 150 18 230 36C280 44 330 36 360 22V46H0V32Z" fill="url(#bottom_blue_front_{{ $index }})"/>
                            <!-- Dot accents -->
                            <circle cx="20" cy="38" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="28" cy="38" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="36" cy="38" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="20" cy="43" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="28" cy="43" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="36" cy="43" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            
                            <circle cx="324" cy="38" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="332" cy="38" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="340" cy="38" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="324" cy="43" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="332" cy="43" r="1.5" fill="#38bdf8" opacity="0.7"/>
                            <circle cx="340" cy="43" r="1.5" fill="#38bdf8" opacity="0.7"/>

                            <defs>
                                <linearGradient id="bottom_blue_front_{{ $index }}" x1="0%" y1="100%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#0284c7"/>
                                    <stop offset="100%" stop-color="#1e3a8a"/>
                                </linearGradient>
                                <linearGradient id="bottom_blue_back_{{ $index }}" x1="0%" y1="100%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#38bdf8"/>
                                    <stop offset="100%" stop-color="#0284c7"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <!-- Orange Bottom Wave -->
                        <svg viewBox="0 0 360 46" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto wave-orange bottom-wave-svg">
                            <path d="M0 24C60 8 130 18 200 32C260 42 320 28 360 14V46H0V24Z" fill="url(#bottom_orange_back_{{ $index }})" opacity="0.5"/>
                            <path d="M0 32C70 14 150 18 230 36C280 44 330 36 360 22V46H0V32Z" fill="url(#bottom_orange_front_{{ $index }})"/>
                            <!-- Dot accents -->
                            <circle cx="20" cy="38" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="28" cy="38" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="36" cy="38" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="20" cy="43" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="28" cy="43" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="36" cy="43" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            
                            <circle cx="324" cy="38" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="332" cy="38" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="340" cy="38" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="324" cy="43" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="332" cy="43" r="1.5" fill="#f59e0b" opacity="0.8"/>
                            <circle cx="340" cy="43" r="1.5" fill="#f59e0b" opacity="0.8"/>

                            <defs>
                                <linearGradient id="bottom_orange_front_{{ $index }}" x1="0%" y1="100%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#f59e0b"/>
                                    <stop offset="100%" stop-color="#b45309"/>
                                </linearGradient>
                                <linearGradient id="bottom_orange_back_{{ $index }}" x1="0%" y1="100%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#fde047"/>
                                    <stop offset="100%" stop-color="#f59e0b"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>

                <!-- Page Break every 4 cards -->
                @if(($index + 1) % 4 === 0 && ($index + 1) < count($participants))
                    <div class="page-break col-span-2"></div>
                @endif

            @empty
                <div class="col-span-2 bg-white rounded-2xl p-12 text-center border border-gray-200 shadow-sm my-12">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <h3 class="text-base font-bold text-gray-800">Tidak ada peserta yang dipilih</h3>
                    <p class="text-xs text-gray-500 mt-1">Silakan pilih peserta atau gunakan filter gelombang pada manajemen peserta.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Client-side Interactivity Script -->
    <script>
        function toggleParticipantNames(show) {
            const containers = document.querySelectorAll('.participant-name-container');
            containers.forEach(el => {
                el.style.display = show ? 'block' : 'none';
            });
        }

        function setGlobalTheme(theme) {
            const cards = document.querySelectorAll('.desk-card');
            
            // Update button styles
            document.querySelectorAll('.theme-btn').forEach(b => {
                b.classList.remove('bg-white', 'shadow-xs', 'text-gray-800');
                b.classList.add('text-gray-600');
            });
            const activeBtn = document.getElementById('btn-theme-' + theme);
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-600');
                activeBtn.classList.add('bg-white', 'shadow-xs', 'text-gray-800');
            }

            cards.forEach((card, idx) => {
                let targetTheme = theme;
                if (theme === 'auto') {
                    targetTheme = card.getAttribute('data-auto-theme') || 'blue';
                }

                card.classList.remove('theme-blue', 'theme-orange');
                card.classList.add('theme-' + targetTheme);
            });
        }

        // Initialize state
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('theme')) {
                setGlobalTheme(urlParams.get('theme'));
            }
            if (urlParams.get('show_name') === '0') {
                const cb = document.getElementById('toggle-name-checkbox');
                if (cb) {
                    cb.checked = false;
                    toggleParticipantNames(false);
                }
            }
        });
    </script>
</body>
</html>
