@props(['name', 'label' => null, 'value' => null, 'error' => null])

<div class="w-full" 
     x-data="{ 
        content: @js($value ?? ''),
        quill: null,
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
                            // Override handler image bawaan
                            image: () => this.selectLocalImage()
                        }
                    },
                    imageResize: {
                        displaySize: true,
                        modules: [ 'Resize', 'DisplaySize', 'Toolbar' ]
                    }
                }
            });

            // Set initial content
            this.quill.root.innerHTML = this.content;

            // Sync editor ke variable content Alpine
            this.quill.on('text-change', () => {
                this.content = this.quill.root.innerHTML;
            });
        },

        selectLocalImage() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (/^image\//.test(file.type)) {
                    await this.uploadImage(file);
                }
            };
        },

        async uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);

            try {
                // Pastikan ganti '/posts/upload-image' sesuai route Anda
                const response = await fetch('/admin/web/posts/upload-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content
                    }
                });

                if (!response.ok) throw new Error('Upload failed');

                const result = await response.json();
                
                // Masukkan URL gambar dari server ke editor
                const range = this.quill.getSelection();
                this.quill.insertEmbed(range.index, 'image', result.url);
            } catch (error) {
                alert('Gagal mengupload gambar ke server.');
                console.error(error);
            }
        }
     }" 
     x-init="initQuill()">
    
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <div class="bg-white rounded-lg overflow-hidden border {{ $error ? 'border-red-500' : 'border-gray-300' }}">
        <div x-ref="quillEditor" class="min-h-[300px]"></div>
    </div>

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif

    <input type="hidden" name="{{ $name }}" x-model="content">
</div>

@once
@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; border-color: #D1D5DB; }
        .ql-container.ql-snow { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-color: #D1D5DB; font-size: 1rem; }
    </style>
@endpush
@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>window.Quill = Quill;</script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
@endpush
@endonce