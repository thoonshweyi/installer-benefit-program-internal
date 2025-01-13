<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{$attach_file_name}}</title>
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
        <img 
            src="{{asset('192.168.2.247/images/attachFile')}}/{{ $attach_file_type }}" 
            width="50%">
            <p>{{asset('192.168.2.247/images/attachFile/').$attach_file_type }}</p>
            <!-- <p>{{asset(env('EXTERNAL_SERVER_URL').'/attachFile')}}/{{ $attach_file_type }}</p> -->
    </div>
    <footer>
       
        <p>Print By : {{$user_name}} | Print On : {{ now(); }}</p>
    </footer>
</body>

</html>