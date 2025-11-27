<footer class="main-footer">
    <div class="footer-container container py-5">
        <div class="row">

            <!-- Thông tin cửa hàng -->
            <div class="col-md-4 mb-4">
                <h5 class="footer-title text-white">NSHOP GAME & HOBBY</h5>
                <p class="footer-text text-light">
                    Đăng ký theo giấy chứng nhận đăng ký kinh doanh Công Ty TNHH Thương Mại nShop với MST: 0316732247. Cấp ngày 08/02/2021 tại Sở kế hoạch Đầu tư TP. Hồ Chí Minh. 
                    nShop - Game & Hobby là cửa hàng Video Game & Đồ chơi Nhật Bản ra mắt từ 2011 & chuyển đổi làm mô hình hệ thống doanh nghiệp vào 2021.
                </p>
                <div class="bct-logo mt-3">
                    <img src="path/to/bct_logo.png" alt="Đã thông báo Bộ Công Thương" style="max-width: 120px;">
                </div>
            </div>

            <!-- Địa chỉ liên hệ -->
            <div class="col-md-4 mb-4">
                <h5 class="footer-title text-white">ĐỊA CHỈ LIÊN HỆ</h5>
                <ul class="list-unstyled text-light">
                    <li>Hà Nội: 190 Kim Mã, Q. Ba Đình</li>
                    <li>Lotte Mall Hồ Tây: Tầng 4, Lot 421</li>
                    <li>Q1: 239 Trần Quang Khải Q.1</li>
                    <li>Q10: 68 Thành Thái P.12 Q.10</li>
                    <li>Q7: Kiot X0114 Sunrise Plaza 27 Nguyễn Hữu Thọ Q.7</li>
                    <li>Gò Vấp: 247 Quang Trung, P.10</li>
                    <li>SC Vivo City: 1058 Nguyễn Văn Linh, TP.HCM</li>
                </ul>
                <p class="text-light mb-0">Hotline (HN): <strong>0966190676</strong></p>
                <p class="text-light mb-0">Hotline (HCM): <strong>0903943263</strong></p>
                <p class="text-light">Email: <a href="mailto:game@nshop.com.vn" class="text-decoration-none text-info">game@nshop.com.vn</a></p>
            </div>

            <!-- Chính sách -->
            <div class="col-md-2 mb-4">
                <h5 class="footer-title text-white">CHÍNH SÁCH</h5>
                <ul class="list-unstyled">
                    <li><a href="/policies/general" class="footer-link">Quy định chung</a></li>
                    <li><a href="/policies/security" class="footer-link">Bảo mật thông tin</a></li>
                    <li><a href="/policies/pricing" class="footer-link">Chính sách giá</a></li>
                    <li><a href="/policies/shipping" class="footer-link">Vận chuyển</a></li>
                    <li><a href="/policies/return" class="footer-link">Đổi trả</a></li>
                    <li><a href="/policies/warranty" class="footer-link">Bảo hành</a></li>
                </ul>
            </div>

            <!-- Về nShop -->
            <div class="col-md-2 mb-4">
                <h5 class="footer-title text-white">VỀ NSHOP</h5>
                <ul class="list-unstyled">
                    <li><a href="/contact" class="footer-link">Liên hệ</a></li>
                    <li><a href="/about" class="footer-link">Giới thiệu nShop</a></li>
                    <li><a href="/nintendo-shop" class="footer-link">Nintendo Shop</a></li>
                    <li><a href="/partner" class="footer-link">Phân phối - Hợp tác</a></li>
                    <li><a href="/payment-guide" class="footer-link">Hướng dẫn thanh toán</a></li>
                    <li><a href="/recruitment" class="footer-link">Tuyển dụng</a></li>
                </ul>
            </div>

        </div>
        <hr class="bg-secondary">
        <div class="text-center text-light small">
            &copy; {{ date('Y') }} NSHOP GAME & HOBBY. All rights reserved.
        </div>
    </div>
</footer>

<style>
.main-footer {
    background-color: red;
    color: #fff;
    font-family: Arial, sans-serif;
    font-size: 0.9rem;
}
.footer-title {
    font-weight: 600;
    margin-bottom: 1rem;
}
.footer-text {
    line-height: 1.5;
}
.footer-link {
    color: #adb5bd;
    text-decoration: none;
}
.footer-link:hover {
    color: #0dcaf0;
    text-decoration: underline;
}
@media (max-width: 768px){
    .footer-container .row > div {
        text-align: center;
    }
}
</style>
