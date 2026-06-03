@props(['name', 'label' => null, 'value' => null, 'error' => null])

<div class="w-full" 
     x-data="{ 
        content: @js($value ?? ''),
        quill: null,
        isUploading: false,
        initQuill() {
            if (typeof ImageResize !== 'undefined' && !Quill.imports['modules/imageResize']) {
                Quill.register('modules/imageResize', ImageResize);
            }

            this.quill = new Quill(this.$refs.quillEditor, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image', 'video', 'code-block'],
                            ['clean']
                        ],
                        handlers: {
                            image: () => this.selectLocalImage()
                        }
                    },
                    imageResize: {
                        displaySize: true,
                        modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                    }
                }
            });

            // Gunakan quill.root.innerHTML untuk set data awal dengan aman
            this.quill.root.innerHTML = this.content;

            this.quill.on('text-change', () => {
                this.content = this.quill.root.innerHTML;
            });
        },

        selectLocalImage() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.setAttribute('multiple', ''); 
            input.click();

            input.onchange = async () => {
                const files = Array.from(input.files);
                this.isUploading = true;
                
                for (const file of files) {
                    if (/^image\//.test(file.type)) {
                        await this.uploadImage(file);
                    }
                }
                this.isUploading = false;
            };
        },

        async uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);

            try {
                // Perbaikan: Gunakan querySelector meta yang lebih standar untuk CSRF
                const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');

                const response = await fetch('/admin/web/posts/upload-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                if (!response.ok) throw new Error('Upload failed');

                const result = await response.json();
                
                // Ambil range terbaru setiap kali loop agar gambar tidak menumpuk di satu titik
                let range = this.quill.getSelection(true);
                let index = range ? range.index : this.quill.getLength();

                this.quill.insertEmbed(index, 'image', result.url);
                
                // Pindahkan kursor ke setelah gambar agar upload berikutnya berada di sampingnya
                this.quill.setSelection(index + 1);

            } catch (error) {
                console.error('Gagal mengupload:', error);
                alert('Gagal mengupload gambar: ' + file.name);
            }
        }
     }" 
     x-init="initQuill()">
    
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            <template x-if="isUploading">
                <span class="ml-2 text-xs text-blue-600 animate-pulse">(Mengunggah...)</span>
            </template>
        </label>
    @endif

    <div class="bg-white rounded-lg overflow-hidden border {{ $error ? 'border-red-500' : 'border-gray-300' }}">
        <div x-ref="quillEditor" class="min-h-[300px]"></div>
    </div>

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif

    <input type="hidden" name="{{ $name }}" :value="content">
</div>

@once
@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; border-color: #D1D5DB; background: #F9FAFB; }
        .ql-container.ql-snow { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-color: #D1D5DB; font-size: 1rem; }
        /* Memperbaiki masalah z-index resize module pada beberapa layout */
        .ql-tooltip { z-index: 1000 !important; }
    </style>
@endpush
@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>window.Quill = Quill;</script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
@endpush
@endonce