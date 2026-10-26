<script src="{{ asset('js/vendor/qrcodejs/qrcode.min.js') }}"></script>
<script>
window.productPriceLookupEndpoint = @json(route('product_price_lookup', [], false));

function escapeProductQrAttribute(value) {
    return String(value == null ? '' : value).replace(/[&<>"']/g, function (character) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[character];
    });
}

window.renderProductQrColumn = function (_data, type, row) {
    if (type !== 'display') return '';

    const productCode = String(row && row.product_code ? row.product_code : '').trim();
    if (!productCode) return '<span class="text-muted" aria-label="No product code">—</span>';

    const productName = String(row.product_name || 'Product');
    const safeCode = escapeProductQrAttribute(productCode);
    const safeName = escapeProductQrAttribute(productName);

    return '<button type="button" class="product-qr-thumbnail show-product-qr" data-product-code="' + safeCode + '" data-product-name="' + safeName + '" title="Click to enlarge product QR" aria-label="Open QR code for ' + safeName + '"><span class="product-qr-inline" data-product-code="' + safeCode + '" aria-hidden="true"></span></button>';
};

window.renderProductQrCodes = function (root) {
    if (typeof window.QRCode !== 'function') return;

    const scope = root && typeof root.querySelectorAll === 'function' ? root : document;
    const containers = [];
    if (scope.matches && scope.matches('.product-qr-inline')) containers.push(scope);
    containers.push.apply(containers, Array.from(scope.querySelectorAll('.product-qr-inline')));

    containers.forEach(function (container) {
        if (container.dataset.qrReady === 'true') return;

        const productCode = (container.dataset.productCode || '').trim();
        if (!productCode) return;

        const lookupUrl = new URL(window.productPriceLookupEndpoint, window.location.origin);
        lookupUrl.searchParams.set('code', productCode);

        try {
            container.replaceChildren();
            new window.QRCode(container, {
                text: lookupUrl.toString(),
                width: 72,
                height: 72,
                colorDark: '#173e3a',
                colorLight: '#ffffff',
                correctLevel: window.QRCode.CorrectLevel.M
            });
            container.dataset.qrReady = 'true';
        } catch (error) {
            container.dataset.qrReady = 'error';
            container.textContent = 'QR unavailable';
            container.setAttribute('title', 'Unable to generate this QR code.');
        }
    });
};

$(function () {
    const photoModal = document.getElementById('ProductPhotoPreviewModal');
    const photoPreviewImage = document.getElementById('productPhotoPreviewImage');
    let photoParentModal = null;

    $(document).on('click', '#getUnassignedProductList .product-photo-preview-trigger, #MainProductVariantsTable .product-photo-preview-trigger', function (event) {
        event.preventDefault();
        const photoUrl = this.getAttribute('data-photo-url') || '';
        const productName = this.getAttribute('data-product-name') || 'Product';
        if (!photoModal || !photoPreviewImage || !photoUrl) return;

        $('#ProductPhotoPreviewModalLabel').text(productName);
        $('#productPhotoPreviewError').addClass('d-none');
        photoPreviewImage.classList.remove('d-none');
        photoPreviewImage.alt = productName + ' photo';
        photoPreviewImage.onerror = function () {
            this.classList.add('d-none');
            $('#productPhotoPreviewError').removeClass('d-none');
        };
        photoPreviewImage.src = photoUrl;

        photoParentModal = Array.from(document.querySelectorAll('.modal.show'))
            .filter(function (modal) { return modal !== photoModal; })
            .pop() || null;
        if (photoParentModal) {
            photoParentModal.addEventListener('hidden.bs.modal', function () {
                $(photoModal).modal('show');
            }, { once: true });
            $(photoParentModal).modal('hide');
        } else {
            $(photoModal).modal('show');
        }
    });

    if (photoModal) {
        $(photoModal).on('hidden.bs.modal', function () {
            photoPreviewImage.removeAttribute('src');
            photoParentModal = photoParentModal && document.body.contains(photoParentModal) ? photoParentModal : null;
            if (photoParentModal) $(photoParentModal).modal('show');
            photoParentModal = null;
        });
    }

    const qrModal = document.getElementById('ProductQrModal');
    const qrCanvas = document.getElementById('productQrCanvas');
    if (!qrModal || !qrCanvas) return;

    let currentLookupUrl = '';
    let currentProductCode = '';
    window.productQrParentModal = null;

    const qrTableIds = ['getUnassignedProductList', 'MainProductVariantsTable'];
    $(document).on('draw.dt', function (event, settings) {
        if (settings && settings.nTable && qrTableIds.indexOf(settings.nTable.id) !== -1) {
            window.renderProductQrCodes(settings.nTable);
        }
    });
    window.renderProductQrCodes(document);

    $(document).on('click', '#getUnassignedProductList .show-product-qr, #MainProductVariantsTable .show-product-qr', function (event) {
        event.preventDefault();

        const productCode = (this.getAttribute('data-product-code') || '').trim();
        const productName = (this.getAttribute('data-product-name') || 'Product').trim();
        if (!productCode) return;

        const lookupUrl = new URL(window.productPriceLookupEndpoint, window.location.origin);
        lookupUrl.searchParams.set('code', productCode);
        currentLookupUrl = lookupUrl.toString();
        currentProductCode = productCode;

        $('#productQrName').text(productName);
        $('#productQrCodeLabel').text(productCode);
        $('#productQrOpenPage').attr('href', currentLookupUrl);
        $('#productQrError').prop('hidden', true).text('');
        qrCanvas.replaceChildren();

        const parentModal = Array.from(document.querySelectorAll('.modal.show'))
            .filter(function (modal) { return modal !== qrModal; })
            .pop() || null;

        window.productQrParentModal = parentModal;
        if (parentModal) {
            parentModal.addEventListener('hidden.bs.modal', function () {
                $(qrModal).modal('show');
            }, { once: true });
            $(parentModal).modal('hide');
        } else {
            $(qrModal).modal('show');
        }
    });

    $(qrModal).on('shown.bs.modal', function () {
        qrCanvas.replaceChildren();
        if (typeof window.QRCode !== 'function') {
            $('#productQrError').prop('hidden', false).text('QR generator failed to load. Refresh the page and try again.');
            return;
        }

        try {
            new window.QRCode(qrCanvas, {
                text: currentLookupUrl,
                width: 220,
                height: 220,
                colorDark: '#173e3a',
                colorLight: '#ffffff',
                correctLevel: window.QRCode.CorrectLevel.M
            });
        } catch (error) {
            $('#productQrError').prop('hidden', false).text('Unable to create this QR code.');
        }
    });

    $(qrModal).on('hidden.bs.modal', function () {
        qrCanvas.replaceChildren();
        const parentModal = window.productQrParentModal;
        window.productQrParentModal = null;
        if (parentModal && document.body.contains(parentModal)) {
            $(parentModal).modal('show');
        }
    });

    $('#downloadProductQr').on('click', function () {
        let imageUrl = '';
        const canvas = qrCanvas.querySelector('canvas');
        const image = qrCanvas.querySelector('img');

        if (canvas && typeof canvas.toDataURL === 'function') {
            imageUrl = canvas.toDataURL('image/png');
        } else if (image && image.src.indexOf('data:image/') === 0) {
            imageUrl = image.src;
        }

        if (!imageUrl) return;

        const download = document.createElement('a');
        const safeCode = currentProductCode.replace(/[^a-zA-Z0-9_-]/g, '') || 'product';
        download.href = imageUrl;
        download.download = 'product-' + safeCode + '-qr.png';
        document.body.appendChild(download);
        download.click();
        download.remove();
    });
});
</script>
