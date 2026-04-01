<h1>勤怠修正申請</h1>

<form method="POST" action="{{ route('attendance.update', $attendance->id) }}">
    @csrf

    <p>名前：{{ $attendance->user->name }}</p>
    <p>日付：{{ \Carbon\Carbon::parse($attendance->date)->format('Y年m月d日') }}</p>

    <div>
        <label>出勤</label>
        <input
            type="time"
            name="clock_in"
            value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}"
        >
    </div>

    <div>
        <label>退勤</label>
        <input
            type="time"
            name="clock_out"
            value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}"
        >
    </div>

    <h2>休憩</h2>

    @for ($i = 0; $i < 3; $i++)
        @php
            $break = $attendance->breaks[$i] ?? null;
        @endphp

        <div>
            <label>休憩開始</label>
            <input
                type="time"
                name="breaks[{{ $i }}][break_start]"
                value="{{ $break && $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}"
            >

            <label>休憩終了</label>
            <input
                type="time"
                name="breaks[{{ $i }}][break_end]"
                value="{{ $break && $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}"
            >
        </div>
    @endfor

    <div>
        <label>休憩開始</label>
        <input type="time" name="breaks[new][break_start]" value="">

        <label>休憩終了</label>
        <input type="time" name="breaks[new][break_end]" value="">
    </div>

    <div>
        <label>備考</label>
        <textarea name="note">{{ old('note', $attendance->note) }}</textarea>
    </div>

    <button type="submit">修正</button>
</form>