@extends('layouts.frontend')
@section('title', 'Đặt hàng thành công')
@section('contents')
<style>
    .checkout-success-box {
        max-width: 430px;
        margin: 30px auto 40px auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px #1bc6c64a;
        padding: 34px 30px 28px 30px;
        text-align: center;
    }
    .success-checkmark {
        width: 74px;
        height: 74px;
        border-radius: 50%;
        display: inline-block;
        background: linear-gradient(135deg, #15c5a5 60%, #6fe7df 100%);
        margin-bottom: 18px;
        position: relative;
        box-shadow: 0 2px 12px #1bc6c63c;
        animation: popUp .4s;
    }
    @keyframes popUp {
        0% { transform: scale(0.7); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .success-checkmark i {
        color: #fff;
        font-size: 2.5rem;
        line-height: 74px;
    }
    .order-success-title {
        color: #16a085;
        font-weight: 900;
        font-size: 1.9rem;
        margin-bottom: 12px;
        letter-spacing: 1px;
    }
    .order-success-message {
        color: #2c3e50;
        font-size: 1.08rem;
        margin-bottom: 0.85rem;
    }
    .order-success-detail {
        color: #5b7083;
        font-size: 0.97rem;
        margin-bottom: 22px;
    }
    .order-btn {
        background: linear-gradient(90deg, #16a085 70%, #12c2e9);
        color: #fff !important;
        border-radius: 13px;
        font-weight: 700;
        font-size: 1.08rem;
        padding: 10px 36px;
        transition: background 0.18s;
        box-shadow: 0 3px 15px -5px #16a08533;
    }
    .order-btn:hover {
        background: linear-gradient(90deg, #12c2e9, #16a085 80%);
        color: #fff;
    }
</style>
<div class="checkout-success-box mt-4 mb-5">
    <div class="success-checkmark">
        <i class="fa fa-check"></i>
    </div>
    <div class="order-success-title mb-1">
        Đặt hàng thành công!
    </div>
    <div class="order-success-message">
        🙏 Cảm ơn bạn đã tin tưởng <b>Quà Quê</b>.
    </div>
    <div class="order-success-detail">
        💚 Chúng tôi sẽ xử lý đơn hàng và giao đến bạn trong thời gian sớm nhất.<br>
        Nếu có vấn đề cần hỗ trợ, đừng ngần ngại liên hệ với chúng tôi!
    </div>
    <a href="{{ route('client.orders.index') }}" class="order-btn mt-2">
        <i class="fa fa-list"></i> Xem đơn hàng của tôi
    </a>
</div>
@endsection
