<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{$product->product_code_no." Attach File"}}</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <style>
        footer {
            font-size: 12px;
            position: fixed;
            bottom: 10;
            text-align: right;
        }
    </style>
</head>

<body>
    <div>

    <img src="{{asset('images/attachFile')}}/{{ $product->product_attach_file }}" width="50%">
    </div>
    <footer>
        <p>Print By : {{$user_name}} | Print On : {{ now(); }}</p>
    </footer>
</body>

</html>