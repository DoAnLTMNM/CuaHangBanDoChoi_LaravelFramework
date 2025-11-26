    // Slug tự động
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    nameInput.addEventListener('keyup', function () {
        slugInput.value = this.value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
    });

    // Checkbox hiển thị/ẩn
    const isActiveInput = document.getElementById('is_active');
    const isActiveLabel = document.getElementById('is_active_label');
    isActiveInput.addEventListener('change', () => {
        isActiveLabel.innerText = isActiveInput.checked ? 'Hiển thị' : 'Ẩn';
    });

    // QUẢN LÝ FILE UPLOAD
    const imagesInput = document.getElementById('images');
    const previewImages = document.getElementById('previewImages');
    const dt = new DataTransfer();

    imagesInput.addEventListener('change', function () {
        for (let file of this.files) {
            dt.items.add(file);
        }
        renderPreview();
    });

    function renderPreview() {
        previewImages.innerHTML = '';
        Array.from(dt.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.style.position = 'relative';
                div.style.display = 'inline-block';
                div.style.marginRight = '10px';
                div.style.marginBottom = '10px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.border = '1px solid #ccc';
                img.style.borderRadius = '5px';
                div.appendChild(img);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.innerHTML = '&times;';
                btn.style.position = 'absolute';
                btn.style.top = '0';
                btn.style.right = '0';
                btn.style.background = 'red';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.style.borderRadius = '50%';
                btn.style.width = '20px';
                btn.style.height = '20px';
                btn.style.cursor = 'pointer';

                btn.addEventListener('click', function () {
                    dt.items.remove(index);
                    imagesInput.files = dt.files;
                    renderPreview();
                });

                div.appendChild(btn);
                previewImages.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        imagesInput.files = dt.files;
    }


