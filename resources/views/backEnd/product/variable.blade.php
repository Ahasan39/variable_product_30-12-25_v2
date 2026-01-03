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
console.log('Variant system initialized');

const selectedColors = new Map(); // Map<colorId, colorName>
const selectedSizes = new Map();   // Map<sizeId, sizeName>
const colorImages = new Map();     // Map<colorId, files[]>

const colorSelect = document.getElementById('colorSelect');
const selectedColorsList = document.getElementById('selectedColorsList');
const colorImagesSection = document.getElementById('colorImagesSection');
const variantsContainer = document.getElementById('variantsContainer');

const colorMap = {
    'Black': '#000000', 'White': '#FFFFFF', 'Red': '#EF4444',
    'Blue': '#3B82F6', 'Green': '#10B981', 'Yellow': '#FDE047',
    'Pink': '#EC4899', 'Gray': '#6B7280', 'Brown': '#92400E',
    'Orange': '#F97316'
};

// Color selection handler
colorSelect.addEventListener('change', function() {
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

function renderColorChips() {
    selectedColorsList.innerHTML = '';
    selectedColors.forEach((colorName, colorId) => {
        const chip = document.createElement('div');
        chip.className = 'color-chip';
        chip.innerHTML = `
            <div class="color-dot" style="background: ${colorMap[colorName] || '#999'}"></div>
            <span>${colorName}</span>
            <button type="button" class="remove-btn" onclick="removeColor('${colorId}')">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        selectedColorsList.appendChild(chip);
    });
}

function removeColor(colorId) {
    selectedColors.delete(colorId);
    colorImages.delete(colorId);
    renderColorChips();
    renderColorImageUpload();
    generateVariants();
}

function renderColorImageUpload() {
    colorImagesSection.innerHTML = '';
    selectedColors.forEach((colorName, colorId) => {
        const box = document.createElement('div');
        box.className = 'color-image-box';
        box.innerHTML = `
            <div class="color-image-header">
                <div class="color-indicator" style="background: ${colorMap[colorName] || '#999'}"></div>
                <span>Upload images for ${colorName}</span>
            </div>
            <label class="upload-btn-custom">
                <i class="bi bi-cloud-upload"></i> Choose Images
                <input type="file" name="color_images[${colorId}][]" multiple accept="image/*" onchange="handleImageUpload('${colorId}', this)">
            </label>
            <div class="image-preview-grid" id="preview_${colorId}"></div>
        `;
        colorImagesSection.appendChild(box);
    });
}

function handleImageUpload(colorId, input) {
    const files = Array.from(input.files);
    if (!colorImages.has(colorId)) {
        colorImages.set(colorId, []);
    }
    colorImages.get(colorId).push(...files);
    renderImagePreview(colorId);
}

function renderImagePreview(colorId) {
    const preview = document.getElementById(`preview_${colorId}`);
    preview.innerHTML = '';

    if (colorImages.has(colorId)) {
        colorImages.get(colorId).forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = 'image-preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-img" onclick="removeImage('${colorId}', ${idx})">
                        <i class="bi bi-x"></i>
                    </button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeImage(colorId, idx) {
    if (colorImages.has(colorId)) {
        colorImages.get(colorId).splice(idx, 1);
        renderImagePreview(colorId);
    }
}

// Size selection handler
document.querySelectorAll('.size-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const sizeId = this.dataset.id;
        const sizeName = this.dataset.name;
        
        if (selectedSizes.has(sizeId)) {
            selectedSizes.delete(sizeId);
            this.classList.remove('active');
        } else {
            selectedSizes.set(sizeId, sizeName);
            this.classList.add('active');
        }
        generateVariants();
    });
});

function addCustomSize() {
    const size = prompt('Enter custom size:');
    if (size && size.trim()) {
        const trimmedSize = size.trim().toUpperCase();
        const customId = 'custom_' + Date.now();
        
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'size-btn active';
        btn.dataset.id = customId;
        btn.dataset.name = trimmedSize;
        btn.textContent = trimmedSize;
        
        btn.addEventListener('click', function() {
            const sId = this.dataset.id;
            const sName = this.dataset.name;
            
            if (selectedSizes.has(sId)) {
                selectedSizes.delete(sId);
                this.classList.remove('active');
            } else {
                selectedSizes.set(sId, sName);
                this.classList.add('active');
            }
            generateVariants();
        });
        
        document.getElementById('sizeButtons').appendChild(btn);
        selectedSizes.set(customId, trimmedSize);
        generateVariants();
    }
}

function generateVariants() {
    variantsContainer.innerHTML = '';

    if (selectedColors.size === 0 || selectedSizes.size === 0) return;

    selectedColors.forEach((colorName, colorId) => {
        const colorGroup = document.createElement('div');
        colorGroup.className = 'color-group';

        let tableRows = '';
        selectedSizes.forEach((sizeName, sizeId) => {
            const variantKey = `${colorId}_${sizeId}`;
            
            // Debug: check if IDs are valid
            console.log('Creating variant row:', {colorId, colorName, sizeId, sizeName});
            
            tableRows += `
                <tr>
                    <td><span class="size-badge">${sizeName}</span></td>
                    <td>
                        <input type="hidden" name="variants[${variantKey}][color_id]" value="${colorId}">
                        <input type="hidden" name="variants[${variantKey}][size_id]" value="${sizeId}">
                        <input type="number" step="0.01" name="variants[${variantKey}][price]" placeholder="৳ 0.00" required>
                    </td>
                    <td><input type="number" name="variants[${variantKey}][stock]" value="0" required></td>
                    <td><input type="text" name="variants[${variantKey}][sku]" placeholder="Auto-generate"></td>
                    <td class="text-center">
                        <label class="toggle-switch">
                            <input type="checkbox" name="variants[${variantKey}][availability]" value="1" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </td>
                </tr>
            `;
        });

        colorGroup.innerHTML = `
            <div class="color-group-header">
                <div class="color-badge" style="background: ${colorMap[colorName] || '#999'}"></div>
                <div class="color-name">${colorName}</div>
            </div>
            <table class="variant-table">
                <thead>
                    <tr>
                        <th style="width: 100px">Size</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Seller SKU</th>
                        <th style="width: 100px" class="text-center">Availability</th>
                    </tr>
                </thead>
                <tbody>${tableRows}</tbody>
            </table>
        `;
        variantsContainer.appendChild(colorGroup);
    });
}

function applyToAll() {
    const price = document.getElementById('applyPrice').value;
    const stock = document.getElementById('applyStock').value;
    const sku = document.getElementById('applySKU').value;

    if (price) {
        document.querySelectorAll('input[name*="[price]"]').forEach(input => {
            input.value = price;
        });
    }
    if (stock) {
        document.querySelectorAll('input[name*="[stock]"]').forEach(input => {
            input.value = stock;
        });
    }
    if (sku) {
        document.querySelectorAll('input[name*="[sku]"]').forEach(input => {
            input.value = sku;
        });
    }
}

// Form validation
document.getElementById('productForm').addEventListener('submit', function(e) {
    if (selectedColors.size === 0) {
        e.preventDefault();
        alert('Please select at least one color');
        return;
    }
    if (selectedSizes.size === 0) {
        e.preventDefault();
        alert('Please select at least one size');
        return;
    }
});
</script>