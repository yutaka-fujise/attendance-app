<h1>申請詳細</h1>

<p>日付：{{ $correction->attendance->date }}</p>

<p>出勤：{{ $correction->clock_in }}</p>
<p>退勤：{{ $correction->clock_out }}</p>

<h2>休憩</h2>
@foreach ($correction->correctionBreaks as $break)
    <p>
        {{ $break->break_start }} 〜 {{ $break->break_end }}
    </p>
@endforeach

<p>備考：{{ $correction->note }}</p>

<p>ステータス：
    @if ($correction->status === 'pending')
        申請中
    @elseif ($correction->status === 'approved')
        承認済み
    @endif
</p>