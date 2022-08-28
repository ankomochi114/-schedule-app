<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スケジュール一覧</title>
    @vite('resources/css/app.css')
</head>

<body>
    <x-header />
    <div class="bg-blue-50 py-4">
        <div class="container xl:w-8/12 mx-auto px-4">
            <h1 class="text-3xl font-bold">スケジュール一覧</h1>
            <p class="mt-1 text-lg text-gray-500">フォローした芸人のスケジュール一覧を一覧で見ることができます。</p>
            @foreach($schedules as $schedule)
            <div class="bg-white rounded-md box-border shadow-xl mt-4 mb-4 p-4">
                <h2 class="text-xl font-bold">{{ $schedule->title }}</h2>
                <p>{{ $schedule->stage->name }}</p>
                <p>{{ $schedule->date }}</p>
                <p class="text-blue-600">
                    開場時間：{{ $schedule->venue_time }} |
                    開演時間：{{ $schedule->start_time }} |
                    終演時間：{{ $schedule->end_time }}
                </p>
                <p>{{ $schedule->description }} </p>
                <div>
                    <a href="{{ route('schedules.edit', $schedule->id) }}">編集</a>
                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit">削除</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>

</html>
