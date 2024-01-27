// import { CKFinder } from '@ckeditor/ckeditor5-ckfinder';

ClassicEditor
    .create(document.querySelector('#body'), {
        ckfinder: {
            uploadUrl: "{{route('ckeditor.upload').'?_token='.csrf_token()}}"
        }
    })
    .then(editor => {
        editor.model.document.on('change:data', () => {
            // let body = document.getElementById('body').getAttribute('data-body')
            // eval(body).set('body', document.getElementById('body').value)
            // console.log(editor.getData())
            let body_content = editor.getData()
            Livewire.dispatch('body', {
                data: body_content
            })
        });
    })
    .catch(error => {
        console.error(error);
    });