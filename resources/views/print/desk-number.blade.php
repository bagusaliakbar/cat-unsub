@php
    $participants = collect([$participant]);
@endphp
@include('print.desk-numbers-all', ['participants' => $participants, 'forcedTheme' => $forcedTheme ?? 'auto', 'lab' => $lab ?? null, 'session' => $session ?? null, 'time' => $time ?? null])
