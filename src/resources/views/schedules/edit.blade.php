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
            <h1 class="text-3xl font-bold">スケジュール編集</h1>
            @if ($errors->any())
            <div class="rounded bg-red-300 border-gray-300 shadow-sm my-4 p-4">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <label class="block">
                        <span class="text-gray-700">タイトル</span>
                        <input type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="title" value="{{  $schedule->title }}">
                    </label>
                    <label class="block">
                        <span class="text-gray-700">開催する場所</span>
                        <select
                            class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="stage_id">
                            @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" @selected($stage->id == $schedule->stage->id)>{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block">
                        <span class="text-gray-700">開催日</span>
                        <input type="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            name="date" value="{{ $schedule->date }}">
                    </label>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block">
                                <span class="text-gray-700">開場時間</span>
                                <input type="time"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="venue_time" value="{{ $schedule->venue_time }}">
                            </label>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block">
                                <span class="text-gray-700">開演時間</span>
                                <input type="time"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="start_time" value="{{ $schedule->start_time }}">
                            </label>
                        </div>
                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block">
                                <span class="text-gray-700">終演時間</span>
                                <input type="time"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    name="end_time" value="{{ $schedule->end_time }}">
                            </label>
                        </div>
                    </div>
                    <label class="block">
                        <span class="text-gray-700">詳細</span>
                        <textarea
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            rows="3" name="description">{{ $schedule->description }}</textarea>
                    </label>
                    <div class="block">
                        <span class="text-gray-700">出演者</span>
                        <div class="flex flex-row flex-wrap m-3 mb-2">
                            @foreach($players as $player)
                            <div class="mx-5">
                                <label>
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50"
                                        name="player_id[]" value="{{ $player->id }}" @checked(in_array($player->id, array_column($schedule->player->toArray(), 'id')))>
                                    <span class="ml-2">{{ $player->name }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex flex-row-reverse">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                            登録
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
