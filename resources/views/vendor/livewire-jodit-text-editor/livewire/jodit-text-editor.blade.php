<div wire:ignore>
    <textarea id="{{ $joditId }}">{!! $value !!}</textarea>
</div>

@script
    <script>
        const buttons = @json($buttons);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const editor = Jodit.make('#' + @js($joditId), {
            "autofocus": true,
            "toolbarSticky": true,
            "uploader": {
                "insertImageAsBase64URI": false,
                "url": @js(route('admin.publik.berita.upload-image')),
                "format": "json",
                "headers": csrfToken ? {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                } : {
                    'Accept': 'application/json'
                },
                "isSuccess": function(resp) {
                    return !resp.error;
                },
                "getMessage": function(resp) {
                    return resp.msg;
                },
                "process": function(resp) {
                    return {
                        files: resp.files || [],
                        path: resp.path || '',
                        baseurl: resp.baseurl || '',
                        error: resp.error || 0,
                        msg: resp.msg || ''
                    };
                },
                "defaultHandlerSuccess": function(data) {
                    const files = data.files || [];

                    files.forEach((file) => {
                        const imageUrl = /^https?:\/\//.test(file) ? file :
                            `${data.baseurl || ''}${file}`;
                        this.s.insertImage(imageUrl);
                    });
                },
                "error": function(e) {
                    this.message.error(e.getMessage());
                }
            },
            "toolbarButtonSize": "large",
            "showCharsCounter": false,
            "showWordsCounter": false,
            "showXPathInStatusbar": false,
            "defaultActionOnPaste": "insert_clear_html",
            "buttons": buttons,
            "theme": "{{ $theme }}"
        });

        editor.events.on('change', function(newValue) {
            @this.set('value', newValue);
        });

        window.addEventListener('update-jodit-content', (event) => {
            if (Array.isArray(event.detail) && event.detail.length > 0) {
                // Check if this is an array with [editorId, content]
                if (Array.isArray(event.detail[0]) && event.detail[0].length === 2) {
                    const [targetId, newContent] = event.detail[0];

                    // Only update if the editor ID matches this instance
                    if (targetId === @js($identifier)) {
                        editor.value = newContent;
                    }
                } else {
                    // Original behavior: update all editors (backward compatibility)
                    editor.value = event.detail[0];
                }
            } else {
                console.warn('Invalid event detail format:', event.detail);
            }
        });
    </script>
@endscript
