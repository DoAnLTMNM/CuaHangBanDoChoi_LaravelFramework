@extends('layouts.app')

@section('content')
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
        @foreach ($banners as $key => $banner)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <a href="{{ $banner->link_url ?? '#' }}">
                    <img src="{{ asset('storage/' . $banner->image_url) }}"
                         class="d-block w-100 banner-img"
                         alt="{{ $banner->title }}">
                </a>
            </div>
        @endforeach
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<div class="container mt-4">
    <h2 class="mb-3">Sản phẩm nổi bật</h2>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 mb-4">
                {{-- Toàn bộ card là 1 link --}}
                <a href="{{ route('product.show', $product->id) }}" class="card-product h-100 text-decoration-none text-dark">
                    <div class="card h-100">
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            {{-- Chỉ hiển thị giá, không phải nút --}}
                            <p class="card-text">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p>Chưa có sản phẩm nào.</p>
        @endforelse
    </div>
</div>



<h1>Xin chào bạn!</h1>
    <p>Đây là nội dung chính của trang chủ, được hiển thị trong layout app.blade.php.</p>
    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Molestias, quis rerum. Obcaecati libero excepturi, optio facere corrupti explicabo fugit repellat minus ipsam, illo accusamus repellendus ad iusto, et tempore beatae?
    Aut nemo doloremque deserunt expedita beatae excepturi facere laboriosam vel, quo architecto asperiores, et aspernatur in minima libero at. Sint qui veniam soluta dolores aliquam praesentium reiciendis ea quae culpa.
    Ipsum totam non, reprehenderit corporis dicta, fugiat eligendi, consequatur porro sint quas doloribus perferendis deleniti exercitationem est repudiandae saepe rem assumenda qui dignissimos ipsa architecto! Eos ad vel quam doloribus!
    Laudantium rem non quam inventore minus dolore iusto neque, temporibus pariatur eius velit optio consequuntur illum magnam? Sint voluptatibus, velit vel explicabo nostrum quae quas neque veniam optio. Saepe, voluptatem!
    Incidunt dolores sit rerum distinctio dignissimos quasi magnam veniam eveniet voluptate consectetur adipisci minima temporibus, iusto possimus deleniti labore sapiente pariatur fugiat accusamus aliquid voluptas architecto hic! Quam, ducimus id.
    Voluptas minus dicta quam hic, nihil quas. Dolorum similique consequuntur officiis possimus ratione sapiente nam repudiandae aperiam, suscipit maxime non, quam perspiciatis accusamus magnam illum. Assumenda voluptate possimus qui natus?
    Quaerat nesciunt excepturi amet omnis quae rem aspernatur fuga sunt eligendi, consequuntur iste, debitis nobis labore nulla distinctio delectus fugit repellat blanditiis dolor obcaecati officiis! Temporibus ut laborum repellat eveniet.
    Ab magnam temporibus consequuntur sint quisquam doloribus distinctio quis, earum, aut eum in dolorum atque sed nulla voluptatum deleniti id, voluptatibus ipsa quibusdam tempore unde! Vel vitae facere quasi voluptatibus!
    Atque maiores nihil iure cumque, dolores accusantium labore saepe quisquam distinctio ad! Enim, tempore sunt quae facere iusto quos laudantium impedit repellat, dolorem error nesciunt molestias. Molestias doloribus inventore sapiente!
    Quis eius voluptates laudantium aliquam repellat error quaerat, obcaecati assumenda molestias vitae aliquid consectetur repudiandae architecto nam labore, laboriosam vel nisi natus aspernatur similique vero, ullam explicabo! Vero, aut voluptates!</p>
@endsection

{{-- <script>
    const myCarousel = document.querySelector('#bannerCarousel');
    const carousel = new bootstrap.Carousel(myCarousel, {
        interval: 2000, 
        ride: 'carousel'
    });
</script> --}}