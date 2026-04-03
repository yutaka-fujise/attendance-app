<h1>申請詳細（管理者）</h1>

<p>名前：{{ $correction->user->name }}</p>
<p>日付：{{ $correction->attendance->date }}</p>

<p>出勤：{{ $correction->clock_in }}</p>
<p>退勤：{{ $correction->clock_out }}</p>

<h2>休憩</h2>
@foreach ($correction->correctionBreaks as $break)
    <p>{{ $break->break_start }} 〜 {{ $break->break_end }}</p>
@endforeach

<p>備考：{{ $correction->note }}</p>
<p>ステータス：{{ $correction->status }}</p>
@if ($correction->status === 'pending')
    <form method="POST" action="{{ route('admin.corrections.approve', $correction->id) }}">
        @csrf
        <button type="submit">承認する</button>
    </form>
@endif