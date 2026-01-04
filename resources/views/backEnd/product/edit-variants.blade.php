<style>
.image-preview-item {
    position: relative;
}

.image-preview-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
}

.image-remove-btn {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 22px;
    height: 22px;
    background: #dc2626;
    color: #fff;
    border-radius: 50%;
    font-size: 14px;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.image-preview-item:hover .image-remove-btn {
    display: flex;
}
</style>


<div>
    <div class="section-card">
        <div class="section-title">
            <i class="bi bi-palette"></i> Variant Options
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label required">Color Family</label>
                <div class="color-selector-area">
                    <select class="form-select color-dropdown" id="colorSelect">
                        <option value="">Select color</option>
                        @foreach($colors as $color)
                            <option 
                                value="{{ $color->id }}"
                                data-name="{{ $color->colorName }}"
                            >
                                {{ $color->colorName }}
                            </option>
                        @endforeach
                    </select>

                    <div class="selected-colors-list" id="selectedColorsList"></div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label required">Size</label>
                <div class="color-selector-area">
                    <div class="size-buttons-grid" id="sizeButtons">
                        @foreach($sizes as $size)
                            <button
                                type="button"
                                class="size-btn"
                                data-id="{{ $size->id }}"
                                data-name="{{ $size->sizeName }}"
                            >
                                {{ $size->sizeName }}
                            </button>
                        @endforeach
                    </div>
                    <a href="javascript:void(0)" class="add-custom-link" onclick="addCustomSize()">
                        <i class="bi bi-plus-circle"></i> Add custom size
                    </a>
                </div>
            </div>
        </div>

        <div class="color-images-section" id="colorImagesSection"></div>
    </div>

    <div class="section-card">
        <div class="section-title">
            <i class="bi bi-grid-3x3-gap"></i> Variants Management
        </div>

        <div class="apply-all-box">
            <div class="apply-all-title">
                <i class="bi bi-lightning-fill"></i> Quick Apply to All Variants
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="applyPrice" placeholder="৳ 0.00">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" id="applyStock" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Seller SKU</label>
                    <input type="text" class="form-control" id="applySKU" placeholder="SKU Code">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="apply-btn w-100" onclick="applyToAll()">
                        <i class="bi bi-arrow-down-circle"></i> Apply To All
                    </button>
                </div>
            </div>
        </div>

        <div class="variants-container" id="variantsContainer"></div>
    </div>
</div>

<script>
console.log('Variant EDIT system initialized');

/* =============================
    DATA FROM BACKEND
============================= */
const existingVariants = @json($existingVariants ?? []);

/* =============================
    STATE
============================= */
const selectedColors = new Map();   // colorId => colorName
const selectedSizes  = new Map();   // sizeId  => sizeName
const colorImages    = new Map();   // colorId => new uploaded files

/* =============================
    ELEMENTS
============================= */
const colorSelect = document.getElementById('colorSelect');
const selectedColorsList = document.getElementById('selectedColorsList');
const colorImagesSection = document.getElementById('colorImagesSection');
const variantsContainer = document.getElementById('variantsContainer');

/* =============================
    COLOR MAP
============================= */
const colorMap = {
    Black:'#000', White:'#fff', Red:'#ef4444',
    Blue:'#3b82f6', Green:'#10b981',
    Yellow:'#fde047', Gray:'#6b7280'
};

/* =============================
    LOAD EXISTING DATA (EDIT)
============================= */
document.addEventListener('DOMContentLoaded', () => {

    Object.keys(existingVariants).forEach(colorId => {
        const variants = existingVariants[colorId];
        if (!variants.length) return;

        const colorName = variants[0].color.colorName;
        selectedColors.set(colorId, colorName);

        variants.forEach(v => {
            const sizeId = v.size_id.toString();
            selectedSizes.set(sizeId, v.size.sizeName);

            const btn = document.querySelector(
                `.size-btn[data-id="${sizeId}"]`
            );
            if (btn) btn.classList.add('active');
        });
    });

    renderColorChips();
    renderColorImageUpload();
    generateVariants();
});

/* =============================
    COLOR SELECT
============================= */
colorSelect.addEventListener('change', function () {
    const colorId = this.value;
    const colorName = this.options[this.selectedIndex].dataset.name;

    if (colorId && !selectedColors.has(colorId)) {
        selectedColors.set(colorId, colorName);
        colorImages.set(colorId, []);
        renderColorChips();
        renderColorImageUpload();
        generateVariants();
    }
    this.value = '';
});

/* =============================
    COLOR CHIPS
============================= */
function renderColorChips() {
    selectedColorsList.innerHTML = '';
    selectedColors.forEach((name, id) => {
        selectedColorsList.innerHTML += `
            <div class="color-chip">
                <div class="color-dot"
                     style="background:${colorMap[name] || '#999'}"></div>
                <span>${name}</span>
                <button type="button"
                    class="remove-btn"
                    onclick="removeColor('${id}')">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;
    });
}

function removeColor(id) {
    selectedColors.delete(id);
    renderColorChips();
    renderColorImageUpload();
    generateVariants();
}

/* =============================
    COLOR IMAGE UPLOAD + SHOW OLD
============================= */
function renderColorImageUpload() {
    colorImagesSection.innerHTML = '';

    selectedColors.forEach((colorName, colorId) => {

        /* OLD IMAGES */
    
let oldImagesHtml = '';
if (existingVariants[colorId]?.[0]?.images?.length) {
    oldImagesHtml = `
        <div class="image-preview-grid">
            ${existingVariants[colorId][0].images.map(img => `
                <div class="image-preview-item">
                    <span class="image-remove-btn"
                        onclick="removeOldImage(this, '${img.id}')">×</span>
                    <img src="{{ asset('') }}${img.image_path}">
                </div>
            `).join('')}
        </div>
    `;
}



        colorImagesSection.innerHTML += `
            <div class="color-image-box">
                <div class="color-image-header">
                    <div class="color-indicator"
                         style="background:${colorMap[colorName] || '#999'}"></div>
                    <span>Upload images for ${colorName}</span>
                </div>

                ${oldImagesHtml}

                <label class="upload-btn-custom">
                    <i class="bi bi-cloud-upload"></i> Choose Images
                    <input type="file"
                        name="color_images[${colorId}][]"
                        multiple accept="image/*"
                        onchange="handleImageUpload('${colorId}', this)">
                </label>

                <div class="image-preview-grid"
                     id="preview_${colorId}"></div>
            </div>
        `;
    });
}

/* =============================
    NEW IMAGE PREVIEW
============================= */
function handleImageUpload(colorId, input) {
    const files = Array.from(input.files);
    colorImages.set(colorId, files);
    renderImagePreview(colorId);
}

function renderImagePreview(colorId) {
    const preview = document.getElementById(`preview_${colorId}`);
    preview.innerHTML = '';

    colorImages.get(colorId)?.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML += `
                <div class="image-preview-item">
                    <span class="image-remove-btn"
                        onclick="removeNewImage('${colorId}', ${index})">×</span>
                    <img src="${e.target.result}">
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });
}
function removeNewImage(colorId, index) {
    const files = colorImages.get(colorId);
    files.splice(index, 1);
    colorImages.set(colorId, files);
    renderImagePreview(colorId);
}


/* =============================
    SIZE SELECT
============================= */
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const name = this.dataset.name;

        if (selectedSizes.has(id)) {
            selectedSizes.delete(id);
            this.classList.remove('active');
        } else {
            selectedSizes.set(id, name);
            this.classList.add('active');
        }
        generateVariants();
    });
});


/* =============================
    VARIANTS TABLE
============================= */
function generateVariants() {
    variantsContainer.innerHTML = '';

    if (!selectedColors.size || !selectedSizes.size) return;

    selectedColors.forEach((colorName, colorId) => {

        let rows = '';

        selectedSizes.forEach((sizeName, sizeId) => {

            let price = '';
            let stock = 0;
            let sku = '';
            let available = true;

            const old = existingVariants[colorId]
                ?.find(v => v.size_id == sizeId);

            if (old) {
                price = old.price;
                stock = old.stock;
                sku = old.sku;
                available = old.availability == 1;
            }

            rows += `
                <tr>
                    <td><span class="size-badge">${sizeName}</span></td>
                    <td>
                        <input type="hidden"
                            name="variants[${colorId}_${sizeId}][color_id]"
                            value="${colorId}">
                        <input type="hidden"
                            name="variants[${colorId}_${sizeId}][size_id]"
                            value="${sizeId}">
                        <input type="number" step="0.01"
                            name="variants[${colorId}_${sizeId}][price]"
                            value="${price}">
                    </td>
                    <td>
                        <input type="number"
                            name="variants[${colorId}_${sizeId}][stock]"
                            value="${stock}">
                    </td>
                    <td>
                        <input type="text"
                            name="variants[${colorId}_${sizeId}][sku]"
                            value="${sku}">
                    </td>
                    <td class="text-center">
                        <input type="checkbox"
                            name="variants[${colorId}_${sizeId}][availability]"
                            value="1"
                            ${available ? 'checked' : ''}>
                    </td>
                </tr>
            `;
        });

        variantsContainer.innerHTML += `
            <div class="color-group">
                <div class="color-group-header">
                    <div class="color-badge"
                         style="background:${colorMap[colorName] || '#999'}"></div>
                    <div class="color-name">${colorName}</div>
                </div>

                <table class="variant-table">
                    <thead>
                        <tr>
                            <th>Size</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>SKU</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    });
}
</script>

