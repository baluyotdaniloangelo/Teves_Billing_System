<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $product->product_name }} - Product Price</title>
    <style>
        :root { color-scheme: light; --wine: #471323; --teal: #173e3a; --green: #608967; --cream: #f2eec0; --ink: #1f2937; --muted: #667085; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; padding: 24px 14px; display: grid; place-items: center; background: linear-gradient(145deg, #f7f5e9, #eef3ef); color: var(--ink); font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .lookup-card { width: min(100%, 480px); background: #fff; border: 1px solid rgba(23, 62, 58, .12); border-radius: 24px; overflow: hidden; box-shadow: 0 18px 50px rgba(23, 62, 58, .14); }
        .brand { padding: 22px 24px; color: #fff; background: linear-gradient(115deg, var(--wine), var(--teal) 68%, var(--green)); }
        .eyebrow { font-size: .72rem; font-weight: 700; letter-spacing: .12em; opacity: .76; text-transform: uppercase; }
        h1 { margin: 8px 0 4px; font-size: clamp(1.35rem, 5vw, 1.8rem); line-height: 1.2; }
        .product-meta { color: rgba(255,255,255,.78); font-size: .9rem; }
        .content { padding: 22px 24px 24px; }
        .product-photo { display: block; width: 100%; max-height: 260px; margin: 0 auto 18px; padding: 10px; border: 1px solid #e4e8e4; border-radius: 16px; background: #f8f9f7; object-fit: contain; }
        .price-card { border-radius: 18px; padding: 20px; background: var(--teal); color: #fff; text-align: center; }
        .price-label { color: rgba(255,255,255,.72); font-size: .82rem; }
        .price-value { margin-top: 4px; font-size: clamp(2rem, 10vw, 2.8rem); font-weight: 800; line-height: 1.1; }
        .price-unit { margin-top: 7px; color: rgba(255,255,255,.76); font-size: .85rem; }
        h2 { margin: 22px 0 10px; font-size: .95rem; }
        .branch-list { display: grid; gap: 8px; }
        .branch-item { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid #e4e8e4; border-radius: 12px; }
        .branch-name { font-weight: 650; }
        .branch-code { display: block; margin-top: 2px; color: var(--muted); font-size: .78rem; }
        .branch-price { white-space: nowrap; color: var(--teal); font-weight: 750; }
        .fallback { margin-top: 16px; padding: 12px 14px; border-radius: 12px; background: #f7f6e8; color: #5c5a39; font-size: .86rem; }
        .code { margin-top: 20px; color: var(--muted); text-align: center; font-size: .8rem; }
        .code strong { color: var(--ink); font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
        footer { padding: 13px 22px; border-top: 1px solid #edf0ed; color: var(--muted); text-align: center; font-size: .76rem; }
        @media (max-width: 380px) { .brand, .content { padding-left: 18px; padding-right: 18px; } }
    </style>
</head>
<body>
    <main class="lookup-card">
        <header class="brand">
            <div class="eyebrow">Product price</div>
            <h1>{{ $product->product_name }}</h1>
            <div class="product-meta">{{ $product->category_name ?: 'Uncategorized' }}</div>
        </header>
        <div class="content">
            @if($product->product_photo_path)
                <img class="product-photo" src="{{ asset('storage/' . ltrim($product->product_photo_path, '/')) }}" alt="{{ $product->product_name }}" loading="lazy">
            @endif
            <section class="price-card" aria-label="Default product price">
                <div class="price-label">Default Price</div>
                <div class="price-value"><span aria-hidden="true">&#8369;</span>{{ number_format((float) $product->product_price, 2) }}</div>
                @if($product->product_unit_measurement)
                    <div class="price-unit">per {{ $product->product_unit_measurement }}</div>
                @endif
            </section>

            @if($branchPrices->isNotEmpty())
                <h2>Price per Branch</h2>
                <div class="branch-list">
                    @foreach($branchPrices as $branchPrice)
                        <div class="branch-item">
                            <div>
                                <div class="branch-name">{{ $branchPrice->branch_name }}</div>
                                <span class="branch-code">{{ $branchPrice->branch_code }}</span>
                            </div>
                            <div class="branch-price"><span aria-hidden="true">&#8369;</span>{{ number_format((float) $branchPrice->branch_price, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="fallback">No branch-specific price is set. The default price above applies.</div>
            @endif

            <div class="code">Product code: <strong>{{ $product->product_code }}</strong></div>
        </div>
        <footer>Prices are current as of the time this page is opened. Confirm the final price at the branch.</footer>
    </main>
</body>
</html>
