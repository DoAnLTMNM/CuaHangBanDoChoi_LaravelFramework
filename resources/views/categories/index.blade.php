<!DOCTYPE html>
<html>
<head>
    <title>Danh mục sản phẩm</title>
</head>
<body>
    <h1>Danh mục sản phẩm</h1>
    <ul>
        @foreach($categories as $category)
            <li>
                {{ $category->name }} 
                @if($category->is_active)
                    <span>(Đang hoạt động)</span>
                @else
                    <span>(Không hoạt động)</span>
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>