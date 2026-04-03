<h1>申請一覧（管理者）</h1>

<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>ステータス</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($corrections as $correction)
            <tr onclick="location.href='{{ route('admin.corrections.show', $correction->id) }}'" style="cursor: pointer;">
                <td>{{ $correction->user->name }}</td>
                <td>{{ $correction->attendance->date }}</td>
                <td>{{ $correction->clock_in }}</td>
                <td>{{ $correction->clock_out }}</td>
                <td>{{ $correction->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>