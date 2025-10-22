<!-- resources/views/hello.blade.php -->
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Trang Hello</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  <div style="max-width:800px;margin:40px auto;font-family:Arial, sans-serif;">
    <h1>Chào mừng đến với Laravel!</h1>
    <p>Xin chào, <strong>{{ $name ?? 'Khách' }}</strong></p>

    <h3>Ví dụ nội dung HTML</h3>
    <ul>
      <li>Trang demo hiển thị view Blade.</li>
      <li>Bạn có thể truyền biến từ route hoặc controller.</li>
    </ul>

    <p>
      <a href="{{ url('/') }}">Về trang chủ</a>
    </p>
  </div>
</body>
</html>
