<header class="max-w-8xl mx-auto">
    <div class="py-4 border-b border-slate-900/10 lg:px-8 lg:border-0 dark:border-slate-300/10 mx-4 lg:mx-0">
        <div class="relative flex items-center">
            <div class="">
                <ul class="flex space-x-8">
                    <li>
                        <a class="hover:text-blue-400 px-2 py-1" href="{{ route('schedules.index') }}">ALL</a>
                    </li>
                    @foreach($players as $player)
                    <li>
                        <a class="hover:text-blue-400 px-2 py-1"
                            href="{{ route('schedules.show', ['schedule'=>$player->id]) }}">{{ $player->name }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative hidden lg:flex items-center ml-auto">
                <ul class="flex space-x-8">
                    <li>
                        <a class=" block rounded-full text-white bg-blue-500 border-blue-400 hover:bg-blue-700 px-2 py-1"
                            href="{{ route('schedules.create') }}">スケジュール登録</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
