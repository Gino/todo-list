<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo list</title>

    <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
</head>
<body class="font-sans bg-grey-lightest antialiased">
    <div id="app">
        <div class="container max-w-lg mx-auto">
            <div class="bg-red text-white p-4 mt-8 font-semibold shadow border-t-4 rounded-t border-red-dark">
                Todo-list app
            </div>
            <div class="leading-normal rounded-b flex shadow">
                <div class="w-1/4 bg-grey-lighter p-4">
                    <div class="font-semibold text-grey-darkest text-sm mb-4">Lijsten</div>
                    @foreach ($lists as $list)
                        <div class="text-sm"><a class="text-red" href="#">{{ $list->name }}</a></div>
                    @endforeach
                </div>
                <div class="w-3/4 bg-white p-4 text-sm">
                    @foreach ($tasks as $task)
                        <div class="pb-2 border-b">{{ $task->body }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
