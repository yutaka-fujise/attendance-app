<h1>申請一覧</h1>

<table>
    <thead>
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>ステータス</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($corrections as $correction)
            <tr onclick="location.href='{{ route('attendance.corrections.show', $correction->id) }}'" style="cursor: pointer;">
                <td>{{ $correction->attendance->date }}</td>
                <td>{{ $correction->clock_in }}</td>
                <td>{{ $correction->clock_out }}</td>
                <td>
                    @if ($correction->status === 'pending')
                        申請中
                    @elseif ($correction->status === 'approved')
                        承認済み
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>